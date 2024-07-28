@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 md:px-0">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-3xl font-semibold text-gray-900 mb-2">{{ $form->title }} - Response Detail</h1>
        <p class="text-gray-600 mb-6">{{ $form->description }}</p>

        <div id="questions_section">
            @foreach ($responses as $response)
                @php
                    $question = $questions[$response->question_id];
                    $decodedAnswers = json_decode($response->answers, true);
                @endphp
                <div class="mb-6 p-4 border rounded-lg bg-gray-50">
                    <h3 class="text-xl font-medium text-gray-800 mb-3">
                        {{ $question['question_text'] }}
                    </h3>
                    @if ($question['type'] == 'dropdown')
                        <select disabled class="form-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach (json_decode($question['options'] ?? '[]') as $option)
                                <option {{ ($option == $decodedAnswers) ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    @elseif (in_array($question['type'], ['multiple_choice', 'checkbox']))
                        <div class="space-y-2">
                            @foreach (json_decode($question['options'] ?? '[]') as $option)
                                <div class="flex items-center">
                                    <input type="{{ $question['type'] == 'checkbox' ? 'checkbox' : 'radio' }}" disabled {{ in_array($option, (array)$decodedAnswers) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label class="ml-3 block text-sm font-medium text-gray-700">
                                        {{ $option }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-1 p-3 bg-white border border-gray-200 rounded-md text-gray-700">
                            {{ is_array($decodedAnswers) ? implode(', ', $decodedAnswers) : $decodedAnswers }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('forms.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Return to Forms
        </a>
    </div>
</div>
@endsection
