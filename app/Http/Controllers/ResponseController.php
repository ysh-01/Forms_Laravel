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

        if ($responses->isEmpty()) {
            abort(404, 'Response not found');
        }

        $formSnapshot = json_decode($responses->first()->form_snapshot, true);

        if (is_null($formSnapshot) || !isset($formSnapshot['questions'])) {
            Log::error('Form snapshot is null or does not contain questions', [
                'response_id' => $responseId,
                'form_snapshot' => $responses->first()->form_snapshot
            ]);
            abort(500, 'Form snapshot is invalid');
        }

        $questions = collect($formSnapshot['questions'])->keyBy('id');

        return view('responses.viewResponse', compact('form', 'responses', 'questions'));
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
            $options = json_decode($question->options, true) ?? [];
            $statistics[$question->id] = [
                'question_text' => $question->question_text,
                'type' => $question->type,
                'options' => $options,
                'responses' => array_fill_keys($options, 0), // Initialize all options with 0 count
            ];

            foreach ($responses as $responseGroup) {
                foreach ($responseGroup as $response) {
                    $decodedAnswers = json_decode($response->answers, true);
                    if (isset($decodedAnswers[$question->id])) {
                        $answer = $decodedAnswers[$question->id];
                        if (is_array($answer)) {
                            foreach ($answer as $option) {
                                if (isset($statistics[$question->id]['responses'][$option])) {
                                    $statistics[$question->id]['responses'][$option]++;
                                }
                            }
                        } else {
                            if (isset($statistics[$question->id]['responses'][$answer])) {
                                $statistics[$question->id]['responses'][$answer]++;
                            }
                        }
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
        Log::info('Form submission started', $request->all());

        $questions = $form->questions;

        $requiredQuestionIds = $questions->where('required', true)->pluck('id')->toArray();

        $validatedData = $request->validate([
            'answers' => 'array',
            'answers.*' => '',
        ]);

        foreach ($requiredQuestionIds as $requiredQuestionId) {
            if (!isset($validatedData['answers'][$requiredQuestionId]) || empty($validatedData['answers'][$requiredQuestionId])) {
                return response()->json(['success' => false, 'message' => 'Please answer all required questions.']);
            }
        }

        Log::info('Validation passed', $validatedData);

        $responseId = Uuid::uuid4()->toString();

        $formSnapshot = [
            'title' => $form->title,
            'description' => $form->description,
            'questions' => $questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'type' => $question->type,
                    'options' => $question->options,
                ];
            })->toArray(),
        ];

        foreach ($validatedData['answers'] as $questionId => $answer) {
            $response = new Response();
            $response->response_id = $responseId;
            $response->question_id = $questionId;
            $response->form_id = $form->id;
            $response->user_id = auth()->id();
            $response->answers = json_encode($answer);
            $response->submitted_at = now();
            $response->form_snapshot = json_encode($formSnapshot);
            $response->save();

            Log::info('Response saved', $response->toArray());
        }

        return response()->json(['success' => true, 'message' => 'Response submitted successfully.']);
    }
}
