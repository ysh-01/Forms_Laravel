<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\User;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create(Form $form)
    {
        return view('questions.create', compact('form'));
    }

    public function store(Form $form, Request $request)
    {

        $validatedData = $request->validate([
            'type' => 'required|string|in:multiple_choice,checkbox,dropdown,short_answer,long_answer',
            'question_text' => 'required|string',
            'options' => 'nullable|array',
        ]);


        $question = new Question();
        $question->form_id = $form->id;
        $question->type = $validatedData['type'];
        $question->question_text = $validatedData['question_text'];
        $question->options = $validatedData['options'] ?? null;
        $question->save();

        return redirect()->route('forms.show', $form)->with('success', 'Question added successfully.');
    }

    public function edit(Form $form, Question $question)
    {
        return view('questions.edit', compact('form', 'question'));
    }

    public function update(Form $form, Question $question, Request $request)
    {

        $validatedData = $request->validate([
            'type' => 'required|string|in:multiple_choice,checkbox,dropdown,short_answer,long_answer',
            'question_text' => 'required|string',
            'options' => 'nullable|array',
        ]);


        $question->type = $validatedData['type'];
        $question->question_text = $validatedData['question_text'];
        $question->options = $validatedData['options'] ?? null;
        $question->save();

        return redirect()->route('forms.show', $form)->with('success', 'Question updated successfully.');
    }

    public function destroy(Form $form, Question $question)
    {
        $question->delete();

        return redirect()->route('forms.show', $form)->with('success', 'Question deleted successfully.');
    }
}
