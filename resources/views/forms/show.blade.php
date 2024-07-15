{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Show Form - {{ $form->title }}</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>

<body>
    <div class="form_header">
        <div class="form_header_left">
            <a href="/forms"><img src="{{ asset('images/google-form.png') }}" class="form_header_icon" height="45px" width="40px" /></a>
            <input type="text" name="title" id="form-title-nav" placeholder="Untitled Form" class="form_name" value="{{ $form->title }}" readonly />
            <img src="{{ asset('images/folder.png') }}" alt="" class="form_header_icon" height="20px" width="20px" />
            <img src="{{ asset('images/star.svg') }}" alt="" class="form_header_icon" />
        </div>
        <div class="form_header_right">
            <span><img src="{{ asset('images/palette-svgrepo-com.svg') }}" alt="pallette" height="20px" width="20px" /></span>
            <span><img src="{{ asset('images/view.png') }}" alt="eye" height="20px" width="20px" onclick="previewForm()" /></span>
            <span><img src="{{ asset('images/undo.png') }}" alt="" height="20px" width="20px" /></span>
            <span><img src="{{ asset('images/forward.png') }}" alt="" height="20px" width="20px" /></span>
            <button class="btn">Send</button>
            <span><img src="{{ asset('images/menu.png') }}" alt="menu" height="30px" width="30px" /></span>
            <span><img src="{{ asset('images/user.png') }}" alt="" height="30px" width="30px" /></span>
        </div>
    </div>
    <div class="container">
        <div class="box">
            <input type="radio" class="tab-toggle" name="tab-toggle" id="tab1" checked />
            <input type="radio" class="tab-toggle" name="tab-toggle" id="tab2" />
            <input type="radio" class="tab-toggle" name="tab-toggle" id="tab3" />

            <ul class="tab-list">
                <li class="tab-item">
                    <label class="tab-trigger" for="tab1"><b>Questions</b></label>
                </li>
                <li class="tab-item">
                    <label class="tab-trigger" for="tab2"><b>Responses</b></label>
                </li>
                <li class="tab-item">
                    <label class="tab-trigger" for="tab3"><b>Settings</b></label>
                </li>
            </ul>
        </div>
    </div>
    <form id="publish-form" method="POST" action="{{ route('forms.update', $form) }}">
        @csrf
        @method('PUT')
        <div class="question_form">
            <br />
            <div class="section">
                <div class="question_title_section">
                    <div class="question_form_top">
                        <input type="text" id="form-title" name="title" class="question_form_top_name form-control" style="color: black" placeholder="Untitled Form" value="{{ $form->title }}" readonly />
                        <input type="text" name="description" id="form-description" class="question_form_top_desc" style="color: black" placeholder="Form Description" value="{{ $form->description }}" readonly />
                    </div>
                </div>
            </div>
            <div class="section" id="questions_section">
                @foreach ($form->questions as $index => $question)
                    <div class="question">
                        <select class="form-control question_type" name="questions[{{ $index }}][type]" onchange="changeQuestionType(this)" disabled>
                            <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="checkbox" {{ $question->type === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                            <option value="dropdown" {{ $question->type === 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                        </select>
                        <input type="text" name="questions[{{ $index }}][text]" class="form-control question-input" placeholder="Type your question here" value="{{ $question->question_text }}" readonly />
                        @if ($question->options)
                            <div class="options-container">
                                @foreach (json_decode($question->options) as $optionIndex => $option)
                                    <div class="option">
                                        <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}]" class="form-control option-input" placeholder="Option" value="{{ $option }}" readonly />
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <div class="btnsub">
            <span>
                <a href="{{ route('forms.edit', $form) }}" class="btnsave btn btn-secondary">Edit</a>
            </span>
            &nbsp;
            &nbsp;
            &nbsp;
            <span>
                <button type="submit" name="publish" value="publish" class="btnsave btn btn-secondary">
                    {{ $form->is_published ? 'Unpublish' : 'Publish' }}
                </button>
            </span>
            &nbsp;
            &nbsp;
            &nbsp;
            <span>
                <a href="/forms" class="btnsave btn btn-secondary">Return</a>
            </span>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('button[name="publish"]').addEventListener('click', function(event) {
                event.preventDefault();
                document.getElementById('publish-form').action = "{{ route('forms.update', $form) }}";
                document.getElementById('publish-form').submit();
            });
        });
    </script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>







 --}}


 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Show Form - {{ $form->title }}</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="form_header">
        <div class="form_header_left">
            <a href="/forms"><img src="{{ asset('images/google-form.png') }}" class="form_header_icon" height="45px" width="40px" /></a>
            <input type="text" name="title" id="form-title-nav" placeholder="Untitled Form" class="form_name" value="{{ $form->title }}" readonly />
            <img src="{{ asset('images/folder.png') }}" alt="" class="form_header_icon" height="20px" width="20px" />
            <img src="{{ asset('images/star.svg') }}" alt="" class="form_header_icon" />
        </div>
        <div class="form_header_right">
            <span><img src="{{ asset('images/palette-svgrepo-com.svg') }}" alt="palette" height="20px" width="20px" /></span>
            <span><img src="{{ asset('images/view.png') }}" alt="eye" height="20px" width="20px" onclick="previewForm()" /></span>
            <span><img src="{{ asset('images/undo.png') }}" alt="" height="20px" width="20px" /></span>
            <span><img src="{{ asset('images/forward.png') }}" alt="" height="20px" width="20px" /></span>
            <button class="btn">Send</button>
            <span><img src="{{ asset('images/menu.png') }}" alt="menu" height="30px" width="30px" /></span>
            <span><img src="{{ asset('images/user.png') }}" alt="" height="30px" width="30px" /></span>
        </div>
    </div>
    <div class="container">
        <div class="box">
            <input type="radio" class="tab-toggle" name="tab-toggle" id="tab1" checked />
            <input type="radio" class="tab-toggle" name="tab-toggle" id="tab2" />
            <input type="radio" class="tab-toggle" name="tab-toggle" id="tab3" />

            <ul class="tab-list">
                <li class="tab-item">
                    <label class="tab-trigger" for="tab1"><b>Questions</b></label>
                </li>
                <li class="tab-item">
                    <label class="tab-trigger" for="tab2"><b>Responses</b></label>
                </li>
                <li class="tab-item">
                    <label class="tab-trigger" for="tab3"><b>Settings</b></label>
                </li>
            </ul>
        </div>
    </div>
    <form id="edit-form" method="POST" action="{{ route('forms.update', $form) }}">
        @csrf
        @method('PUT')
        <div class="question_form">
            <br />
            <div class="section">
                <div class="question_title_section">
                    <div class="question_form_top">
                        <input type="text" id="form-title" name="title" class="question_form_top_name form-control" style="color: black" placeholder="Untitled Form" value="{{ $form->title }}" readonly />
                        <input type="text" name="description" id="form-description" class="question_form_top_desc" style="color: black" placeholder="Form Description" value="{{ $form->description }}" readonly />
                    </div>
                </div>
            </div>
            <div class="section" id="questions_section">
                @foreach ($form->questions as $index => $question)
                    <div class="question">
                        <select class="form-control question_type" name="questions[{{ $index }}][type]" onchange="changeQuestionType(this)" disabled>
                            <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="checkbox" {{ $question->type === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                            <option value="dropdown" {{ $question->type === 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                        </select>
                        <input type="text" name="questions[{{ $index }}][text]" class="form-control question-input" placeholder="Type your question here" value="{{ $question->question_text }}" readonly />
                        @if ($question->options)
                            <div class="options-container">
                                @foreach (json_decode($question->options) as $optionIndex => $option)
                                    <div class="option">
                                        <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}]" class="form-control option-input" placeholder="Option" value="{{ $option }}" readonly />
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <div class="btnsub">
            <span>
                <button type="submit" name="publish" value="publish" class="btnsave btn btn-secondary">
                    {{ $form->is_published ? 'Unpublish' : 'Publish' }}
                </button>
            </span>
            &nbsp;
            &nbsp;
            &nbsp;
            <span>
                <a href="{{ route('forms.index') }}" class="btnsave btn btn-secondary">Return to Forms</a>
            </span>
        </div>
    </form>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
