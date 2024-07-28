<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\User;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Unique;

class FormController extends Controller
{
    public function index()
    {
        $totalForms = Form::count();
        $publishedForms = Form::where('is_published', true)->count();
        $totalResponses = Response::distinct('response_id')->count('response_id');

        $forms = Form::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('forms.index', [
            'forms' => $forms,
            'totalForms' => $totalForms,
            'publishedForms' => $publishedForms,
            'totalResponses' => $totalResponses,
        ]);
    }

    public function create()
    {
        return view('forms.create');
    }

    public function togglePublish(Form $form)
    {
        $form->is_published = !$form->is_published;
        $form->save();

        return redirect()->route('forms.show', $form->id)->with('success', 'Form publish status updated.');
    }


    public function edit(Form $form)
    {
        $questions = $form->questions()->orderBy('order')->get();
        foreach ($questions as $question) {
            $question->options = json_decode($question->options, true);
        }

        return view('forms.edit', compact('form', 'questions'));
    }


    public function createWithTemplate($template)
    {
        $data = [];

        switch ($template) {
            case 'contact':
                $data = [
                    'title' => 'Contact Information',
                    'description' => 'Template for collecting contact information.',
                    'questions' => [
                        ['type' => 'text', 'question_text' => 'Name'],
                        ['type' => 'text', 'question_text' => 'Email'],
                        // Add more questions as needed
                    ],
                ];
                break;

            case 'rsvp':
                $data = [
                    'title' => 'RSVP',
                    'description' => 'Event Address: 123 Your Street Your City, ST 12345
Contact us at (123) 456-7890 or no_reply@example.com
',
                    'questions' => [
                        ['type' => 'text', 'question_text' => 'Can you attend?'],
                        ['type' => 'text', 'question_text' => 'Number of Guests'],
                    ],
                ];
                break;

            case 'party':
                $data = [
                    'title' => 'Party Invite',
                    'description' => 'Template for party invitations.',
                    'questions' => [
                        ['type' => 'text', 'question_text' => 'Name'],
                        ['type' => 'text', 'question_text' => 'RSVP Status'],
                    ],
                ];
                break;
        }

        return view('forms.create', ['data' => $data]);
    }




    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array',
            'questions.*.type' => 'required|string|in:multiple_choice,checkbox,dropdown,text',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'nullable|array',
            'questions.*.required' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            $form = Form::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'user_id' => Auth::id(),
            ]);

            foreach ($validatedData['questions'] as $index => $questionData) {
                $form->questions()->create([
                    'type' => $questionData['type'],
                    'question_text' => $questionData['text'],
                    'options' => json_encode($questionData['options'] ?? []),
                    'required' => $questionData['required'],
                    'order' => $index,
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Form saved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving form: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to save form.']);
        }
    }

    public function show(Form $form)
    {
        $form->load(['questions' => function ($query) {
            $query->orderBy('order');
        }, 'questions.responses']);
        return view('forms.show', compact('form'));
    }

    public function preview($id)
    {
        $form = Form::with(['questions' => function ($query) {
            $query->orderBy('order');
        }])->findOrFail($id);
        return view('forms.previewForm', compact('form'));
    }


    public function update(Request $request, Form $form)
    {
        try {
            // Normalize the 'required' field to boolean
            if ($request->has('questions')) {
                $questions = $request->input('questions');
                foreach ($questions as $index => $question) {
                    $questions[$index]['required'] = isset($question['required']) && $question['required'] === 'on';
                }
                $request->merge(['questions' => $questions]);
            }

            // Validate the request
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
                'questions' => 'required|array',
                'questions.*.id' => 'nullable|exists:questions,id',
                'questions.*.type' => 'required|string|in:multiple_choice,checkbox,dropdown,text',
                'questions.*.text' => 'required|string|max:255',
                'questions.*.options' => 'nullable|array',
                'questions.*.options.*' => 'nullable|string|max:255',
                'questions.*.required' => 'boolean',
            ]);

            DB::beginTransaction();

            // Update form
            $form->update([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
            ]);

            // Get existing question IDs
            $existingQuestionIds = $form->questions()->pluck('id')->toArray();

            // Update or create questions
            foreach ($validatedData['questions'] as $index => $questionData) {
                if (isset($questionData['id'])) {
                    if($questionData['type'] == 'text'){
                        $questionData['options'] = [];
                    }
                    // Update existing question
                    $question = Question::find($questionData['id']);
                    if ($question) {
                        $question->update([
                            'type' => $questionData['type'],
                            'question_text' => $questionData['text'],
                            'options' => json_encode($questionData['options'] ?? []),
                            'required' => $questionData['required'],
                            'order' => $index,
                        ]);
                        // Remove this ID from existingQuestionIds
                        $existingQuestionIds = array_diff($existingQuestionIds, [$questionData['id']]);
                    }
                } else {
                    // Create new question
                    $form->questions()->create([
                        'type' => $questionData['type'],
                        'question_text' => $questionData['text'],
                        'options' => json_encode($questionData['options']),
                        'required' => $questionData['required'],
                        'order' => $index,
                    ]);
                }
            }

            // Delete questions that are no longer in the form
            if (!empty($existingQuestionIds)) {
                Question::whereIn('id', $existingQuestionIds)->delete();
            }

            DB::commit();
            return redirect()->route('forms.show', $form)->with('success', 'Form updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating form: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while updating the form. Please try again.'])->withInput();
        }
    }



    public function destroy(Form $form)
    {
        // This will also delete all related questions and responses due to foreign key constraints
        $form->delete();

        return redirect()->route('forms.index')->with('delete', 'Form deleted successfully.');
    }
}
