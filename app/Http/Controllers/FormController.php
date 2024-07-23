<?php

namespace App\Http\Controllers;

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
        $totalResponses = Response::count();

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
        $questions = $form->questions;
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
                    // Add more questions as needed
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
                    // Add more questions as needed
                ],
            ];
            break;
    }

    return view('forms.create', ['data' => $data]);
}




    public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array',
            'questions.*.type' => 'required|string|in:multiple_choice,checkbox,dropdown,text',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'nullable|array',
            'questions.*.required' => 'nullable|boolean',
        ]);


        $form = new Form();
        $form->title = $validatedData['title'];
        $form->description = $validatedData['description'];
        $form->is_published = $request->input('is_published', false);
        $form->user_id = Auth::id();
        $form->save();

        foreach ($validatedData['questions'] as $questionData) {
            $question = new Question();
            $question->form_id = $form->id;
            $question->type = $questionData['type'];
            $question->question_text = $questionData['text'];
            $question->options = isset($questionData['options']) ? json_encode($questionData['options']) : null;
            $question->required = isset($questionData['required']) ? $questionData['required'] : false;
            $question->save();
        }



        return response()->json(['success' => true, 'form_id' => $form->id]);
    } catch (\Exception $e) {
        Log::error('Error saving form: ' . $e->getMessage(), ['exception' => $e]);
        return response()->json(['success' => false, 'message' => 'Error saving form'], 500);
    }
}

    public function show(Form $form)
    {
        $form->load('questions.responses');
        return view('forms.show', compact('form'));
    }

    public function preview($id)
    {
        $form = Form::findOrFail($id);
        return view('forms.previewForm', compact('form'));
    }


    public function update(Request $request, Form $form)
    {
        if ($request->has('publish')) {
            $form->is_published = !$form->is_published;
            $form->save();

            return redirect()->route('forms.show', $form);
        }
        Log::info('Incoming request data: ', $request->all());

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'questions' => 'required|array',
            'questions.*.id' => 'nullable|exists:questions,id',
            'questions.*.type' => 'required|string|in:multiple_choice,checkbox,dropdown,text',
            'questions.*.text' => 'required|string|max:255',
            'questions.*.options' => 'nullable|array',
            'questions.*.options.*' => 'nullable|string|max:255',
        ]);

        Log::info('Validated data: ', $validatedData);

        $form->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
        ]);

        $existingQuestionIds = [];
        foreach ($validatedData['questions'] as $questionData) {
            if (isset($questionData['id'])) {
                $question = Question::find($questionData['id']);
            } else {
                $question = new Question();
                $question->form_id = $form->id;
            }

            $question->type = $questionData['type'];
            $question->question_text = $questionData['text'];
            $question->options = isset($questionData['options']) ? json_encode($questionData['options']) : json_encode([]);
            $question->save();

            Log::info('Saved question: ', $question->toArray());

            $existingQuestionIds[] = $question->id;
        }

        $form->questions()->whereNotIn('id', $existingQuestionIds)->delete();

        Log::info('Remaining questions: ', $form->questions()->get()->toArray());

        return redirect()->route('forms.show', $form)->with('success', 'Form updated successfully.');
    }













    public function destroy(Form $form)
    {
        // This will also delete all related questions and responses due to foreign key constraints
        $form->delete();

        return redirect()->route('forms.index')->with('delete', 'Form deleted successfully.');
    }
}
