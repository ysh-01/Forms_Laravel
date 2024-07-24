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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body class="font-open-sans">
    <header class="bg-white shadow-md py-1">
        <div class="container d-flex justify-content-start align-items-center shadow-md">
            <a href="/forms" class="d-flex align-items-center">
                <img src="{{ asset('images/google-form.png') }}" alt="Google Form Icon" height="40px" width="40px" />
            </a>
            <h4 style="color: rgb(103,58,183)" class="ml-3">{{ $form->title }} - Response Detail</h4>
        </div>
    </header>


    <div class="container mt-4">
        <!-- Responses Section -->
        <div class="question_form bg-light p-4 rounded shadow-md" id="responses_section">
            <div class="section">
                <div class="question_title_section mb-4">
                    <div class="question_form_top">
                        <input type="text" id="form-title" name="title" class="form-control form-control-lg mb-2" style="color: black" placeholder="Untitled Form" value="{{ $form->title }}" readonly />
                        <input type="text" name="description" id="form-description" class="form-control form-control-sm" style="color: black" value="{{ $form->description }}" readonly/>
                    </div>
                </div>
            </div>
            <div class="section shadow-md" id="questions_section">
                @foreach ($responses as $response)
                    @php
                        $question = $questions[$response->question_id];
                        $decodedAnswers = json_decode($response->answers, true);
                    @endphp
                    <div class="question mb-4 p-3 border rounded bg-white shadow-md">
                        <h3 class="text-lg font-medium mb-2">
                            {{ $question['question_text'] }}
                        </h3>
                        @if ($question['type'] == 'dropdown')
                            <select disabled class="form-control">
                                @foreach (json_decode($question['options'] ?? '[]') as $option)
                                    <option {{ ($option == $decodedAnswers) ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        @elseif (in_array($question['type'], ['multiple_choice', 'checkbox']))
                            <div class="options-container mb-3">
                                @foreach (json_decode($question['options'] ?? '[]') as $option)
                                    <div class="option d-flex align-items-center mb-2">
                                        <input type="{{ $question['type'] == 'checkbox' ? 'checkbox' : 'radio' }}" disabled {{ in_array($option, (array)$decodedAnswers) ? 'checked' : '' }} class="mr-2">
                                        {{ $option }}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-2 p-3 bg-light rounded">{{ is_array($decodedAnswers) ? implode(', ', $decodedAnswers) : $decodedAnswers }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
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
