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
    <title>Form Responses - {{ $form->title }}</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body>
    <div class="form_header roboto-light">
        <div class="form_header_left">
            <a href="/forms"><img src="{{ asset('images/google-form.png') }}" class="form_header_icon" height="45px"
                    width="40px" /></a>
            <h1 class="form_name">{{ $form->title }} Responses</h1>
        </div>
        <div class="form_header_right">
            <img src="{{ asset('images/menu.png') }}" alt="menu" height="30px" width="30px" />
            <img src="{{ asset('images/user.png') }}" alt="" height="30px" width="30px" />
        </div>
    </div>
    <div class="container">
        <h2>Responses</h2>

        <div class="share-link">
            <input type="text" value="{{ route('responses.submitForm', $form) }}" id="shareLink" readonly>
            <button onclick="copyLink()">Copy Link</button>
        </div>

        <script>
            function copyLink() {
                var copyText = document.getElementById("shareLink");
                copyText.select();
                copyText.setSelectionRange(0, 99999); /* For mobile devices */
                document.execCommand("copy");
                alert("Link copied: " + copyText.value);
            }
        </script>

        @if ($responses->isEmpty())
            <p>No responses available.</p>
        @else
            <ul>
                @foreach ($responses as $response)
                    <li>
                        User: {{ $response->user->name }} - Submitted at: {{ $response->submitted_at }}
                        <a href="{{ route('responses.viewResponse', ['form' => $form, 'response' => $response]) }}"
                            target="_blank">View Response</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
