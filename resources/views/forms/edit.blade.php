<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Form - {{ $form->title }}</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
    <style>
        .shadow-custom {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-light">
    <nav class="bg-white p-1 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <span style="color: rgb(103,58,183)" class="text-3xl font-bold font-sans"><a href="{{ url('/') }}"
                    style="color: rgb(103,58,183)" class="text-3xl font-bold font-sans">LaraForms</a> - Edit</span>
            <div class="relative dropdown">
                <button id="profileMenuButton" class="flex items-center focus:outline-none">
                    <img src="{{ asset('images/user.png') }}" alt="Profile"
                        class="w-10 h-10 rounded-full border-2 border-white">
                </button>
                <div id="profileMenu"
                    class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-200 w-full text-left">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div style="max-width: 700px" class="container">
        <form id="edit-form" method="POST" action="{{ route('forms.update', $form) }}"
            class="bg-white p-4 rounded shadow-sm">
            @csrf
            @method('PUT')
            <div class="form-group">
                <input type="text" id="form-title" name="title" class="form-control form-control-lg text-black"
                    placeholder="Untitled Form" value="{{ $form->title }}" />
            </div>
            <div class="form-group">
                <input type="text" name="description" id="form-description"
                    class="form-control form-control-sm text-black" placeholder="Form Description"
                    value="{{ $form->description }}" />
            </div>
            <div id="questions-section">
                @foreach ($questions as $index => $question)
                    <div class="question mb-4 p-3 border rounded bg-light" data-index="{{ $index }}">
                        <div class="form-group">
                            <select class="form-control question-type" id="question-type-{{ $index }}"
                                name="questions[{{ $index }}][type]">
                                <option value="multiple_choice"
                                    {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice
                                </option>
                                <option value="checkbox" {{ $question->type === 'checkbox' ? 'selected' : '' }}>
                                    Checkbox</option>
                                <option value="dropdown" {{ $question->type === 'dropdown' ? 'selected' : '' }}>
                                    Dropdown</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" id="question-text-{{ $index }}"
                                name="questions[{{ $index }}][text]" class="form-control question-input"
                                value="{{ $question->question_text }}" required>
                        </div>
                        <div class="form-group options-container">
                            <label>Options</label>
                            @if (is_array($question->options))
                                @foreach ($question->options as $optionIndex => $option)
                                    <div class="option d-flex align-items-center mb-2">
                                        <input type="text"
                                            name="questions[{{ $index }}][options][{{ $optionIndex }}]"
                                            class="form-control option-input" value="{{ $option }}">
                                        <span class="delete-option ml-2 text-danger" onclick="deleteOption(this)"
                                            style="cursor: pointer;">&#10005;</span>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" class="btn btn-secondary" onclick="addOption(this)">Add
                                Option</button>
                            <button class="btn btn-md" id="moveUpButton" onclick="deleteQuestion(this);">
                                <img src="{{ asset('images/bin.png') }}" alt="" width="20px"
                                    height="20px" />
                            </button>
                        </div>
                    </div>
                @endforeach
                <div class="sidebar">
                    <div id="moveableDiv">
                        <button class="btn btn-light shadow-sm" type="button" onclick="addNewQuestion();">
                            <img src="{{ asset('images/add.png') }}" alt="ADD" width="20px" height="20px" />
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary mb-4" onclick="addNewQuestion()">Add New Question</button>
            <button type="submit" class="btn btn-success mb-4">Save</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function addOption(button) {
            const optionsContainer = $(button).closest('.options-container');
            const optionIndex = optionsContainer.find('.option').length;
            const questionIndex = optionsContainer.closest('.question').data('index');

            const optionHtml = `
                <div class="option d-flex align-items-center mb-2">
                    <input type="text" name="questions[${questionIndex}][options][${optionIndex}]" class="form-control option-input" placeholder="Option">
                    <span class="delete-option ml-2 text-danger" onclick="deleteOption(this)" style="cursor: pointer;">&#10005;</span>
                </div>
            `;

            optionsContainer.append(optionHtml);
            updateAddButtonPosition();
        }

        function deleteOption(button) {
            $(button).closest('.option').remove();
            updateAddButtonPosition();
        }

        function addNewQuestion() {
            const questionsSection = $('#questions-section');
            const questionIndex = questionsSection.find('.question').length;

            const questionHtml = `
                <div class="question mb-4 p-3 border rounded bg-light" data-index="${questionIndex}">
                    <div class="form-group">
                        <select class="form-control question-type" id="question-type-${questionIndex}" name="questions[${questionIndex}][type]">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="dropdown">Dropdown</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" id="question-text-${questionIndex}" name="questions[${questionIndex}][text]" class="form-control question-input" placeholder="Type your question here" required>
                    </div>
                    <div class="form-group options-container">
                        <label>Options</label>
                        <button type="button" class="btn btn-secondary" onclick="addOption(this)">Add Option</button>
                    </div>
                    <button class="btn btn-md" id="moveUpButton" onclick="deleteQuestion(this);">
                                <img src="{{ asset('images/bin.png') }}" alt="" width="20px" height="20px" />
                    </button>
                </div>
            `;

            questionsSection.append(questionHtml);
            updateAddButtonPosition();
        }

        function deleteQuestion(button) {
            $(button).closest('.question').remove();
            updateAddButtonPosition();
        }

        function updateAddButtonPosition() {
            const questions = document.querySelectorAll("#questions-section .question");
            const sidebar = document.getElementById("moveableDiv");

            if (questions.length > 0) {
                const lastQuestion = questions[questions.length - 1];
                const offsetTop = lastQuestion.offsetTop;
                const sidebarHeight = sidebar.offsetHeight;
                const containerHeight = document.getElementById("questions-section").offsetHeight;

                const newPosition = offsetTop + lastQuestion.offsetHeight;
                if (newPosition + sidebarHeight <= containerHeight) {
                    sidebar.style.transform = `translateY(${newPosition}px)`;
                    console.log(`Moving sidebar to: ${newPosition}px`);
                } else {
                    sidebar.style.transform = `translateY(${containerHeight - sidebarHeight}px)`;
                    console.log(`Moving sidebar to bottom of container`);
                }
            } else {
                sidebar.style.transform = `translateY(0px)`;
                console.log("No questions, moving sidebar to top");
            }
        }

        $(document).ready(function() {
            $('#profileMenuButton').on('click', function() {
                $('#profileMenu').toggleClass('hidden');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#profileMenuButton').length && !$(e.target).closest(
                        '#profileMenu').length) {
                    $('#profileMenu').addClass('hidden');
                }
            });

            updateAddButtonPosition();
        });
    </script>
</body>

</html>
