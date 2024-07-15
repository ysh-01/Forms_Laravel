{{-- @extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@section('content')
    <div class="container">
        <h1>{{ $form->title }}</h1>
        <p>{{ $form->description }}</p>

        <form action="{{ route('responses.submitForm', $form) }}" method="POST">
            @csrf
            @foreach ($questions as $question)
                <div class="form-group">
                    <label>{{ $question->question_text }}</label>
                    @if ($question->type == 'multiple_choice')
                        @foreach (json_decode($question->options) as $option)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]"
                                    value="{{ $option }}">
                                <label class="form-check-label">{{ $option }}</label>
                            </div>
                        @endforeach
                    @elseif($question->type == 'checkbox')
                        @foreach (json_decode($question->options) as $option)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="answers[{{ $question->id }}][]"
                                    value="{{ $option }}">
                                <label class="form-check-label">{{ $option }}</label>
                            </div>
                        @endforeach
                    @elseif($question->type == 'dropdown')
                        <select class="form-control" name="answers[{{ $question->id }}]">
                            @foreach (json_decode($question->options) as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            @endforeach
            <br>
            <br>
            <br>
            <button type="submit" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"">Submit</button>
        </form>
    </div>
@endsection --}}


@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-semibold">{{ $form->title }}</h1>
        <p class="text-gray-700 mt-2">{{ $form->description }}</p>

        <form action="{{ route('responses.submitForm', $form) }}" method="POST" class="mt-8">
            @csrf
            @foreach ($questions as $question)
                <div class="mt-6">
                    <label class="block font-medium text-gray-800">{{ $question->question_text }}</label>
                    @if ($question->type == 'multiple_choice')
                        @foreach (json_decode($question->options) as $option)
                            <label class="inline-flex items-center mt-2">
                                <input class="form-radio text-purple-600" type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}">
                                <span class="ml-2 text-gray-700">{{ $option }}</span>
                            </label>
                        @endforeach
                    @elseif($question->type == 'checkbox')
                        @foreach (json_decode($question->options) as $option)
                            <label class="inline-flex items-center mt-2">
                                <input class="form-checkbox text-purple-600" type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option }}">
                                <span class="ml-2 text-gray-700">{{ $option }}</span>
                            </label>
                        @endforeach
                    @elseif($question->type == 'dropdown')
                        <select class="form-select mt-2 block w-full p-2 border border-gray-300 rounded-md bg-white shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" name="answers[{{ $question->id }}]">
                            @foreach (json_decode($question->options) as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    @elseif($question->type == 'short_answer')
                        <input type="text" name="answers[{{ $question->id }}]" class="form-input mt-2 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    @elseif($question->type == 'long_answer')
                        <textarea name="answers[{{ $question->id }}]" class="form-textarea mt-2 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"></textarea>
                    @endif
                </div>
            @endforeach

            <button type="submit" class="mt-6 inline-flex items-center px-4 py-2 bg-purple-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200">
                Submit
            </button>
        </form>
    </div>
@endsection

