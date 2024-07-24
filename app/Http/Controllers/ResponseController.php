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

    public function showSuccess(Form $form)
    {
        return view('responses.success', compact('form'));
    }



    public function viewResponse(Form $form, $responseId)
    {

        $responses = Response::where('response_id', $responseId)
            ->where('form_id', $form->id)
            ->get();


        $questions = Question::where('form_id', $form->id)->get()->keyBy('id');


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

        $responses = Response::where('form_id', $form->id)
            ->orderBy('submitted_at', 'desc')
            ->get()
            ->groupBy('response_id');


        $questions = Question::where('form_id', $form->id)->get()->keyBy('id');


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
        Log::info($request->all());


        $questions = $form->questions;


        $requiredQuestionIds = $questions->where('required', true)->pluck('id')->toArray();


        $validatedData = $request->validate([
            'answers' => 'array',
            'answers.*' => '',
        ]);


        foreach ($requiredQuestionIds as $requiredQuestionId) {
            if (!isset($validatedData['answers'][$requiredQuestionId]) || empty($validatedData['answers'][$requiredQuestionId])) {
                return redirect()->back()
                    ->withErrors(['errors' => 'Please answer all required questions.'])
                    ->withInput();
            }
        }

        Log::info($validatedData);


        $responseId = Uuid::uuid4()->toString();


        foreach ($validatedData['answers'] as $questionId => $answer) {
            $response = new Response();
            $response->response_id = $responseId;
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
