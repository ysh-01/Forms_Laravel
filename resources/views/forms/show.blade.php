<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Show Form - {{ $form->title }}</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="bg-white p-4 shadow-sm">
        <div class="mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" style="color: rgb(103,58,183)"
                class="text-3xl font-bold font-sans">LaraForms</a>
            <div class="relative dropdown">
                <button id="profileMenuButton" class="flex items-center focus:outline-none">
                    <img src="{{ asset('images/user.png') }}" alt="Profile"
                        class="w-10 h-10 rounded-full border-2 border-white">
                </button>
                <div id="profileMenu"
                    class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 w-full text-left">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
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
