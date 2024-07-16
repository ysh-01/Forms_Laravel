{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Response Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body style="background-color: #f0ebf8" class="font-roboto">
    <header class="bg-white shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <a href="/forms">
                    <img src="{{ asset('images/google-form.png') }}" class="h-12 w-12 mr-4" alt="Google Form Icon" />
                </a>
                <h1 style="color: rgb(103,58,183)" class="text-xl font-semibold">{{ $form->title }} - Response Detail</h1>
            </div>
        </div>
    </header>

    <main class="container mx-auto mt-8">
        <div class="bg-white p-8 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold mb-6">Response from {{ $responses->first()->user->name ?? 'Anonymous' }} - {{ $responses->first()->submitted_at }}</h2>

            @foreach ($responses as $response)
                @php
                    $question = $questions[$response->question_id] ?? null;
                    $decodedAnswers = json_decode($response->answers, true);
                @endphp

                @if ($question)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-2">{{ $question->question_text }}</h3>
                        @if ($question->type == 'dropdown')
                            <select disabled class="w-full p-3 border border-gray-600 rounded-lg">
                                @foreach (json_decode($question->options) as $option)
                                    <option {{ ($option == $decodedAnswers) ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        @elseif (in_array($question->type, ['multiple_choice', 'checkbox']))
                            <div class="space-y-2">
                                @foreach (json_decode($question->options) as $option)
                                    <label class="block">
                                        <input type="{{ $question->type == 'checkbox' ? 'checkbox' : 'radio' }}" disabled {{ in_array($option, (array)$decodedAnswers) ? 'checked' : '' }} class="mr-2">
                                        {{ $option }}
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-2 p-3 bg-gray-100 rounded-lg">{{ is_array($decodedAnswers) ? implode(', ', $decodedAnswers) : $decodedAnswers }}</p>
                        @endif
                    </div>
                @else
                    <p class="text-red-500">Question not found for ID: {{ $response->question_id }}</p>
                @endif
            @endforeach
        </div>
    </main>

    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html> --}}



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Response Detail - {{ $form->title }}</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>

<body style="background-color: #f0ebf8" class="font-open-sans">
    <header class="bg-white shadow-md py-1">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <a href="/forms">
                    <img src="{{ asset('images/google-form.png') }}" alt="Google Form Icon" class="h-12 w-12 mr-4" />
                </a>
                <h1 style="color: rgb(103,58,183)" class="text-xl font-semibold">{{ $form->title }} - Response Detail</h1>
            </div>
        </div>
    </header>
    <div class="question_form bg-light p-4 rounded shadow-sm">
        <div class="section">
            <div class="question_title_section mb-4">
                <div class="question_form_top">
                    <input type="text" id="form-title" name="title" class="form-control form-control-lg mb-2" style="color: black" placeholder="Untitled Form" value="{{ $form->title }}" readonly />
                    <input type="text" name="description" id="form-description" class="form-control form-control-sm" style="color: black" value="{{$form->description}}" readonly/>
                </div>
            </div>
        </div>
        <div class="section" id="questions_section">
            <h2 class="text-2xl font-bold mb-6">Response from {{ $responses->first()->user->name ?? 'Anonymous' }} - {{ $responses->first()->submitted_at }}</h2>

            @foreach ($responses as $response)
                @php
                    $question = $questions[$response->question_id] ?? null;
                    $decodedAnswers = json_decode($response->answers, true);
                @endphp

                @if ($question)
                    <div class="question mb-4 p-3 border rounded bg-white">
                        <h3 class="text-lg font-medium mb-2">{{ $question->question_text }}</h3>
                        @if ($question->type == 'dropdown')
                            <select disabled class="form-control">
                                @foreach (json_decode($question->options) as $option)
                                    <option {{ ($option == $decodedAnswers) ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        @elseif (in_array($question->type, ['multiple_choice', 'checkbox']))
                            <div class="options-container mb-3">
                                @foreach (json_decode($question->options) as $option)
                                    <div class="option d-flex align-items-center mb-2">
                                        <input type="{{ $question->type == 'checkbox' ? 'checkbox' : 'radio' }}" disabled {{ in_array($option, (array)$decodedAnswers) ? 'checked' : '' }} class="mr-2">
                                        {{ $option }}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-2 p-3 bg-light rounded">{{ is_array($decodedAnswers) ? implode(', ', $decodedAnswers) : $decodedAnswers }}</p>
                        @endif
                    </div>
                @else
                    <p class="text-danger">Question not found for ID: {{ $response->question_id }}</p>
                @endif
            @endforeach
        </div>
    </div>

    <div class="btnsub text-center mt-4">
        <span>
            <a href="{{ route('forms.index') }}" class="btn btn-secondary">Return to Forms</a>
        </span>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
