<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\User;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FormController extends Controller
{
    public function index()
    {
        // Get forms belonging to the authenticated user
        $forms = Form::where('user_id', Auth::id())->get();
        return view('forms.index', compact('forms'));
    }

    public function create()
    {
        return view('forms.create');
    }

    public function edit(Form $form)
{
    // Questions are already fetched with their options cast to array due to the casts property
    $questions = $form->questions;
foreach ($questions as $question) {
    $question->options = json_decode($question->options, true);
}

// Pass the questions to the view
return view('forms.edit', compact('form', 'questions'));
}



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array',
            'questions.*.type' => 'required|string|in:multiple_choice,checkbox,dropdown,short_answer,long_answer',
            'questions.*.text' => 'required|string', // This should match the key used in the JavaScript
            'questions.*.options' => 'nullable|array',
        ]);

        $form = new Form();
        $form->title = $validatedData['title'];
        $form->description = $validatedData['description'];
        $form->is_published = $request->input('is_published', false); // Default to false if not provided
        $form->user_id = Auth::id();
        $form->save();

        foreach ($validatedData['questions'] as $questionData) {
            $question = new Question();
            $question->form_id = $form->id;
            $question->type = $questionData['type'];
            $question->question_text = $questionData['text']; // Ensure this matches the key in the validated data
            $question->options = isset($questionData['options']) ? json_encode($questionData['options']) : null;

            $question->save();
        }

        return response()->json(['success' => true, 'form_id' => $form->id]);
    }


    public function show(Form $form)
    {
        $form->load('questions.responses');

        return view('forms.show', compact('form'));
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
            'questions.*.type' => 'required|string|in:multiple_choice,checkbox,dropdown,short_answer,long_answer',
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

        // Delete questions that were removed
        $form->questions()->whereNotIn('id', $existingQuestionIds)->delete();

        Log::info('Remaining questions: ', $form->questions()->get()->toArray());

        return redirect()->route('forms.show', $form)->with('success', 'Form updated successfully.');
    }











    public function destroy(Form $form)
    {
        // This will also delete all related questions and responses due to foreign key constraints
        $form->delete();

        return redirect()->route('forms.index')->with('success', 'Form deleted successfully.');
    }
}
