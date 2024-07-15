{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap"
        rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Response Detail</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body>
    <div class="form_header roboto-light">
        <div class="form_header_left">
            <a href="/forms"><img src="{{ asset('images/google-form.png') }}" class="form_header_icon" height="45px"
                    width="40px" /></a>
            <h1 class="form_name">{{ $form->title }} - Response Detail</h1>
        </div>
    </div>
    <div class="container">
        <div class="response_detail">
            <h2>Response from {{ $response->user->name ?? 'Anonymous' }} - {{ $response->submitted_at }}</h2>
            @php
                $decodedAnswers = json_decode($response->answers, true);
            @endphp
            @if (is_array($decodedAnswers))
                @foreach ($decodedAnswers as $questionId => $answer)
                    <div class="question">
                        <h3>Question {{ $questionId }}:</h3>
                        <p>{{ is_array($answer) ? implode(', ', $answer) : $answer }}</p>
                    </div>
                @endforeach
            @else
                <p>No valid responses available.</p>
            @endif
        </div>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html> --}}




{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Response Detail</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body>
    <div class="form_header roboto-light">
        <div class="form_header_left">
            <a href="/forms"><img src="{{ asset('images/google-form.png') }}" class="form_header_icon" height="45px" width="40px" /></a>
            <h1 class="form_name">{{ $form->title }} - Response Detail</h1>
        </div>
    </div>
    <div class="container">
        <div class="response_detail">
            <h2>Response from {{ $responses->first()->user->name ?? 'Anonymous' }} - {{ $responses->first()->submitted_at }}</h2>
            @foreach ($responses as $response)
                <div class="question">
                    <h3>Question {{ $response->question_id }}:</h3>
                    @php
                        $decodedAnswers = json_decode($response->answers, true);
                    @endphp
                    <p>{{ is_array($decodedAnswers) ? implode(', ', $decodedAnswers) : $decodedAnswers }}</p>
                </div>
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
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap"
        rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Response Detail</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body>
    <div class="form_header roboto-light">
        <div class="form_header_left">
            <a href="/forms"><img src="{{ asset('images/google-form.png') }}" class="form_header_icon" height="45px"
                    width="40px" /></a>
            <h1 class="form_name">{{ $form->title }} - Response Detail</h1>
        </div>
    </div>
    <div class="container">
        <div class="response_detail">
            <h2>Response from {{ $responses->first()->user->name ?? 'Anonymous' }} - {{ $responses->first()->submitted_at }}</h2>
            @foreach ($responses as $response)
                @php
                    $question = $questions[$response->question_id];
                    $decodedAnswers = json_decode($response->answers, true);
                @endphp
                <div class="question">
                    <h3>{{ $question->question_text }}</h3>
                    @if ($question->type == 'multiple_choice' || $question->type == 'checkbox' || $question->type == 'dropdown')
                        @foreach (json_decode($question->options) as $option)
                            <p>
                                <input type="radio" disabled {{ in_array($option, (array)$decodedAnswers) ? 'checked' : '' }}>
                                {{ $option }}
                            </p>
                        @endforeach
                    @else
                        <p>{{ is_array($decodedAnswers) ? implode(', ', $decodedAnswers) : $decodedAnswers }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
