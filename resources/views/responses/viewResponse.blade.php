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

<body class="bg-gray-50 font-roboto">
    <div class="bg-white shadow-md p-4 flex justify-between items-center">
        <div class="flex items-center">
            <a href="/forms">
                <img src="{{ asset('images/google-form.png') }}" class="h-12 w-12 mr-4" alt="Google Form Icon" />
            </a>
            <h1 class="text-xl font-semibold">{{ $form->title }} - Response Detail</h1>
        </div>
    </div>

    <div class="container mx-auto mt-8">
        <div class="bg-white p-6 shadow-md rounded-lg">
            <h2 class="text-2xl font-bold mb-4">Response from {{ $responses->first()->user->name ?? 'Anonymous' }} - {{ $responses->first()->submitted_at }}</h2>

            @foreach ($responses as $response)
                @php
                    $question = $questions[$response->question_id] ?? null;
                    $decodedAnswers = json_decode($response->answers, true);
                @endphp

                @if ($question)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium">{{ $question->question_text }}</h3>
                        @if ($question->type == 'dropdown')
                            <select disabled class="w-full p-2 mt-2 border rounded">
                                @foreach (json_decode($question->options) as $option)
                                    <option {{ ($option == $decodedAnswers) ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        @elseif (in_array($question->type, ['multiple_choice', 'checkbox']))
                            <div class="mt-2">
                                @foreach (json_decode($question->options) as $option)
                                    <label class="block">
                                        <input type="{{ $question->type == 'checkbox' ? 'checkbox' : 'radio' }}" disabled {{ in_array($option, (array)$decodedAnswers) ? 'checked' : '' }} class="mr-2">
                                        {{ $option }}
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-2 p-2 bg-gray-100 rounded">{{ is_array($decodedAnswers) ? implode(', ', $decodedAnswers) : $decodedAnswers }}</p>
                        @endif
                    </div>
                @else
                    <p class="text-red-500">Question not found for ID: {{ $response->question_id }}</p>
                @endif
            @endforeach
        </div>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html> --}}



<!DOCTYPE html>
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

</html>
