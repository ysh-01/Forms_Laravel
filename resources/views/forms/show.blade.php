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
    <div class="question_form bg-light p-4 rounded shadow-sm">
        <div class="section">
            <div class="question_title_section mb-4">
                <div class="question_form_top">
                    <input type="text" id="form-title" name="title" class="form-control form-control-lg mb-2" style="color: black" placeholder="Untitled Form" value="{{ $form->title }}" readonly />
                    <input type="text" name="description" id="form-description" class="form-control form-control-sm" style="color: black" placeholder="Form Description" value="{{ $form->description }}" readonly />
                </div>
            </div>
        </div>
        <div class="section" id="questions_section">
            @foreach ($form->questions as $index => $question)
                <div class="question mb-4 p-3 border rounded bg-white">
                    <select class="form-control question_type mb-3" name="questions[{{ $index }}][type]" onchange="changeQuestionType(this)" disabled>
                        <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                        <option value="checkbox" {{ $question->type === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                        <option value="dropdown" {{ $question->type === 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                        <option value="text" {{ $question->type === 'text' ? 'selected' : '' }}>Text</option>
                    </select>
                    <input type="text" name="questions[{{ $index }}][text]" class="form-control question-input mb-3" placeholder="Type your question here" value="{{ $question->question_text }}" readonly />
                    @if ($question->options)
                        <div class="options-container mb-3">
                            @foreach (json_decode($question->options) as $optionIndex => $option)
                                <div class="option d-flex align-items-center mb-2">
                                    <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}]" class="form-control option-input" placeholder="Option" value="{{ $option }}" readonly />
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <div class="btnsub text-center mt-4">
        <form action="{{ route('forms.publish', $form->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <span><button type="submit" name="publish" value="publish" class="btnsave btn btn-secondary">
                {{ $form->is_published ? 'Unpublish' : 'Publish' }}
            </button></span>
        </form>
        &nbsp;
        &nbsp;
        <span><a href="{{ route('forms.index') }}" class="btnsave btn btn-secondary">Return to Forms</a></span>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
