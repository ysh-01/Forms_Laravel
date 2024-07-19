<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use App\Models\Form;
use App\Models\Response;
use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function index(Form $form)
    {
        $responses = Response::where('form_id', $form->id)
            ->with('user')
            ->orderBy('submitted_at', 'desc')
            ->get();

        return view('responses.index', compact('form', 'responses'));
    }

    // Display a specific response
    public function viewResponse(Form $form, $responseId)
{
    // Get all responses with the same response_id
    $responses = Response::where('response_id', $responseId)
        ->where('form_id', $form->id)
        ->get();

    // Get all questions for the form
    $questions = Question::where('form_id', $form->id)->get()->keyBy('id');

    // Aggregate data for statistics
    $statistics = [];
    foreach ($questions as $question) {
        $statistics[$question->id] = [
            'question_text' => $question->question_text,
            'type' => $question->type,
            'options' => json_decode($question->options),
            'responses' => []
        ];

        foreach ($responses as $response) {
            $decodedAnswers = json_decode($response->answers, true);
            if (isset($decodedAnswers[$question->id])) {
                $statistics[$question->id]['responses'][] = $decodedAnswers[$question->id];
            }
        }
    }

    return view('responses.viewResponse', compact('form', 'responses', 'questions', 'statistics'));
}


public function viewResponses(Form $form)
{
    // Get all responses for the form, grouped by response_id
    $responses = Response::where('form_id', $form->id)
        ->orderBy('submitted_at', 'desc')
        ->get()
        ->groupBy('response_id');

    // Get all questions for the form
    $questions = Question::where('form_id', $form->id)->get()->keyBy('id');

    // Aggregate data for statistics
    $statistics = [];
    foreach ($questions as $question) {
        $statistics[$question->id] = [
            'question_text' => $question->question_text,
            'type' => $question->type,
            'options' => json_decode($question->options, true),
            'responses' => [],
        ];

        foreach ($responses as $responseGroup) {
            foreach ($responseGroup as $response) {
                $decodedAnswers = json_decode($response->answers, true);
                if (isset($decodedAnswers[$question->id])) {
                    $statistics[$question->id]['responses'][] = $decodedAnswers[$question->id];
                }
            }
        }
    }

    return view('responses.viewResponses', compact('form', 'responses', 'statistics', 'questions'));
}


    public function showForm(Form $form)
    {
        $questions = $form->questions;
        if (!$form->is_published) {
            return redirect('/forms')->with('delete', 'This form is not published.');
        }

        return view('responses.showForm', compact('form', 'questions'));
    }

    public function submitForm(Request $request, Form $form)
{
    Log::info($request->all()); // Log the entire request data for debugging

    // Fetch all questions for the form
    $questions = $form->questions;

    // Extract IDs of required questions
    $requiredQuestionIds = $questions->where('required', true)->pluck('id')->toArray();

    // Validate and process form submission
    $validatedData = $request->validate([
        'answers' => 'required|array',
        'answers.*' => 'required', // Ensure all answers are provided
    ]);

    // Ensure all required questions are answered
    foreach ($requiredQuestionIds as $requiredQuestionId) {
        if (!array_key_exists($requiredQuestionId, $validatedData['answers'])) {
            return redirect()->back()
                             ->withErrors(['errors' => 'Please answer all required questions.'])
                             ->withInput();
        }
    }

    Log::info($validatedData); // Log the validated data for debugging

    // Generate a UUID for response_id
    $responseId = Uuid::uuid4()->toString();

    // Save each question's response
    foreach ($validatedData['answers'] as $questionId => $answer) {
        $response = new Response();
        $response->response_id = $responseId; // Assign the generated UUID
        $response->question_id = $questionId;
        $response->form_id = $form->id;
        $response->user_id = auth()->id();
        $response->answers = json_encode($answer);
        $response->submitted_at = now();
        $response->save();
    }

    return redirect()->route('responses.showForm', $form)
                     ->with('success', 'Response submitted successfully.');
}





}
