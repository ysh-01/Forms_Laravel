{{-- <!DOCTYPE html>
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
        .active-question {
            border: 2px solid rgb(103,58,183) !important;
        }
    </style>
</head>

<body class="bg-purple-100">
    <nav class="bg-white p-0.5 shadow-md">
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
                <input style="border:none; border-bottom:2px solid rgb(103,58,183); border-radius:0" type="text" id="form-title" name="title" class="form-control form-control-lg text-black"
                    placeholder="Untitled Form" value="{{ $form->title }}" />
            </div>
            <div class="form-group">
                <input style="border:none; border-bottom:2px solid rgb(103,58,183); border-radius:0" type="text" name="description" id="form-description"
                    class="form-control form-control-sm text-black" placeholder="Form Description"
                    value="{{ $form->description }}" />
            </div>
            <div id="questions-section">
                @foreach ($questions as $index => $question)
                    <div class="question mb-4 p-3 border rounded bg-light" data-index="{{ $index }}" onclick="setActiveQuestion(this)">
                        <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                        <div class="form-group">
                            <select class="form-control question-type" id="question-type-{{ $index }}" name="questions[{{ $index }}][type]">
                                <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                <option value="checkbox" {{ $question->type === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                <option value="dropdown" {{ $question->type === 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                                <option value="text" {{ $question->type === 'text' ? 'selected' : '' }}>Text</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" id="question-text-{{ $index }}"
                                name="questions[{{ $index }}][text]" class="form-control question-input"
                                value="{{ $question->question_text }}" required>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" id="question-required-{{ $index }}"
                                name="questions[{{ $index }}][required]" class="form-check-input"
                                {{ $question->required ? 'checked' : '' }}>
                            <label for="question-required-{{ $index }}" class="form-check-label">Required</label>
                        </div>
                        <div class="form-group options-container" style="{{ $question->type === 'text' ? 'display:none;' : '' }}">
                            <label>Options</label>
                            @if (is_array($question->options))
                                @foreach ($question->options as $optionIndex => $option)
                                    <div class="option d-flex align-items-center mb-2">
                                        <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}]" class="form-control option-input" value="{{ $option }}">
                                        <span class="delete-option ml-2 text-danger" onclick="deleteOption(this)" style="cursor: pointer;">&#10005;</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <button type="button" class="btn btn-secondary mr-2" onclick="addOption(this)">Add Option</button>
                            <button class="btn btn-md" id="moveUpButton" onclick="deleteQuestion(this);">
                                <img src="{{ asset('images/bin.png') }}" alt="" width="20px" height="20px" />
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
            <button type="submit" class="btn btn-success mb-4">Save</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function addOption(button) {
            const optionsContainer = $(button).closest('.question').find('.options-container');
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
                <div class="question mb-4 p-3 border rounded bg-light" data-index="${questionIndex}" onclick="setActiveQuestion(this)">
                    <input type="hidden" name="questions[${questionIndex}][id]" value="">
                    <div class="form-group">
                        <select class="form-control question-type" id="question-type-${questionIndex}" name="questions[${questionIndex}][type]" onchange="handleQuestionTypeChange(this)">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="dropdown">Dropdown</option>
                            <option value="text">Text</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" id="question-text-${questionIndex}" name="questions[${questionIndex}][text]" class="form-control question-input" placeholder="Type your question here" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" id="question-required-${questionIndex}"
                            name="questions[${questionIndex}][required]" class="form-check-input">
                        <label for="question-required-${questionIndex}" class="form-check-label">Required</label>
                    </div>
                    <div class="form-group options-container">
                        <label>Options</label>
                    </div>
                    <div class="d-flex align-items-center mt-2">
                        <button type="button" class="btn btn-secondary mr-2" onclick="addOption(this)">Add Option</button>
                        <button class="btn btn-md" id="moveUpButton" onclick="deleteQuestion(this);">
                            <img src="{{ asset('images/bin.png') }}" alt="" width="20px" height="20px" />
                        </button>
                    </div>
                </div>
            `;

            if (activeQuestion) {
                $(activeQuestion).after(questionHtml);
            } else {
                questionsSection.append(questionHtml);
            }

            updateQuestionIndices();
            updateAddButtonPosition();
        }

        function deleteQuestion(button) {
            $(button).closest('.question').remove();
            updateQuestionIndices();
            updateAddButtonPosition();
        }

        function updateQuestionIndices() {
            $('#questions-section .question').each((index, element) => {
                $(element).attr('data-index', index);
                $(element).find('.question-type').attr('name', `questions[${index}][type]`);
                $(element).find('.question-input').attr('name', `questions[${index}][text]`);
                $(element).find('.question-input').attr('id', `question-text-${index}`);
                $(element).find('.form-check-input').attr('name', `questions[${index}][required]`);
                $(element).find('.form-check-input').attr('id', `question-required-${index}`);
                $(element).find('.options-container').find('.option-input').each((optionIndex, optionElement) => {
                    $(optionElement).attr('name', `questions[${index}][options][${optionIndex}]`);
                });
            });
        }

        function handleQuestionTypeChange(selectElement) {
            const selectedType = $(selectElement).val();
            const optionsContainer = $(selectElement).closest('.question').find('.options-container');

            if (selectedType === 'text') {
                optionsContainer.hide();
            } else {
                optionsContainer.show();
            }
        }

        function updateAddButtonPosition() {
            const sidebar = document.querySelector(".sidebar");
            const questionsSection = document.getElementById("questions-section");
            const questions = questionsSection.querySelectorAll('.question');

            if (activeQuestion) {
                const activeIndex = Array.from(questions).indexOf(activeQuestion);
                const top = activeQuestion.offsetTop + activeQuestion.offsetHeight / 2 - sidebar.offsetHeight / 2;
                sidebar.style.transform = `translateY(${top}px)`;
            } else if (questions.length > 0) {
                const firstQuestion = questions[0];
                const top = firstQuestion.offsetTop + firstQuestion.offsetHeight / 2 - sidebar.offsetHeight / 2;
                sidebar.style.transform = `translateY(${top}px)`;
            } else {
                sidebar.style.transform = 'translateY(0)';
            }
        }

        $(document).ready(function () {
            $('#profileMenuButton').click(function () {
                $('#profileMenu').toggleClass('hidden');
            });

            $(document).click(function (event) {
                if (!$(event.target).closest('#profileMenuButton, #profileMenu').length) {
                    $('#profileMenu').addClass('hidden');
                }
            });

            $('.question-type').each((index, element) => {
                handleQuestionTypeChange(element);
            });

            updateAddButtonPosition();

            $('#edit-form').on('submit', function(e) {
                e.preventDefault();
                updateQuestionIndices();
                this.submit();
            });
        });

        let activeQuestion = null;

        function setActiveQuestion(questionElement) {
            if (activeQuestion) {
                activeQuestion.classList.remove('active-question');
            }
            activeQuestion = questionElement;
            activeQuestion.classList.add('active-question');
            updateAddButtonPosition();
        }
    </script>
</body>

</html> --}}




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Form - {{ $form->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>
        .active-question {
            border: 2px solid rgb(103,58,183) !important;
        }
        .sidebar {
            position: absolute;
            right: -60px;
            transition: transform 0.3s ease;
        }
    </style>
</head>

<body style="background-color: #f4f4f9;">
    <nav class="bg-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" style="color: rgb(103,58,183)" class="text-3xl font-bold font-sans">LaraForms - Edit</a>
            <div class="relative dropdown">
                <button id="profileMenuButton" class="flex items-center focus:outline-none">
                    <img src="{{ asset('images/user.png') }}" alt="Profile" class="w-10 h-10 rounded-full border-2 border-white">
                </button>
                <div id="profileMenu" class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 w-full text-left">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div style="background-color: #f4f4f9; max-width: 50%" class="container mt-8">
        <form id="edit-form" method="POST" action="{{ route('forms.update', $form) }}">
            @csrf
            @method('PUT')
            <div class="bg-white p-4 rounded shadow-sm mb-4">
                <input style="border: none; border-bottom:2px solid rgb(103,58,183); border-radius:0" type="text" id="form-title" name="title" class="form-control form-control-lg p-2 mb-2" placeholder="Untitled Form" value="{{ $form->title }}" />
                <input style="border: none; border-bottom:2px solid rgb(103,58,183); border-radius:0" type="text" name="description" id="form-description" class="form-control form-control-sm" placeholder="Form Description" value="{{ $form->description }}" />
            </div>
            <div id="questions-section">
                @foreach ($questions as $index => $question)
                    <div class="question mb-4 p-4 border rounded bg-white shadow-sm" data-index="{{ $index }}" onclick="setActiveQuestion(this)">
                        <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                        <select class="form-control question-type mb-3" id="question-type-{{ $index }}" name="questions[{{ $index }}][type]" onchange="handleQuestionTypeChange(this)">
                            <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="checkbox" {{ $question->type === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                            <option value="dropdown" {{ $question->type === 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                            <option value="text" {{ $question->type === 'text' ? 'selected' : '' }}>Text</option>
                        </select>
                        <input style="border:none; border-bottom: 2px solid rgb(103,58,183); border-radius:0; color:black" type="text" id="question-text-{{ $index }}" name="questions[{{ $index }}][text]" class="form-control question-input mb-3" value="{{ $question->question_text }}" required>
                        <div class="form-group options-container" style="{{ $question->type === 'text' ? 'display:none;' : '' }}">
                            @if (is_array($question->options))
                                @foreach ($question->options as $optionIndex => $option)
                                    <div class="option d-flex align-items-center mb-2">
                                        <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}]" class="form-control option-input" value="{{ $option }}">
                                        <span class="delete-option ml-2 text-danger" onclick="deleteOption(this)" style="cursor: pointer;">&#10005;</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <button type="button" class="btn btn-secondary mr-2" onclick="addOption(this)">Add Option</button>
                            <button type="button" class="btn btn-danger mr-2" onclick="deleteQuestion(this)">
                                <img src="{{ asset('images/bin.png') }}" alt="Delete" width="20" height="20">
                            </button>
                            <label class="m-0 ml-2">
                                <input type="checkbox" id="question-required-{{ $index }}" name="questions[{{ $index }}][required]" {{ $question->required ? 'checked' : '' }}> Required
                            </label>
                        </div>
                    </div>
                @endforeach
                <div class="sidebar">
                    <button type="button" class="btn btn-light shadow-sm" onclick="addNewQuestion();">
                        <img src="{{ asset('images/add.png') }}" alt="Add" width="20" height="20">
                    </button>
                </div>
            </div>
            <div class="text-center mb-4">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function addOption(button) {
            const optionsContainer = $(button).closest('.question').find('.options-container');
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
                <div class="question mb-4 p-3 border rounded bg-light" data-index="${questionIndex}" onclick="setActiveQuestion(this)">
                    <input type="hidden" name="questions[${questionIndex}][id]" value="">
                    <div class="form-group">
                        <select class="form-control question-type" id="question-type-${questionIndex}" name="questions[${questionIndex}][type]" onchange="handleQuestionTypeChange(this)">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="dropdown">Dropdown</option>
                            <option value="text">Text</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" id="question-text-${questionIndex}" name="questions[${questionIndex}][text]" class="form-control question-input" placeholder="Type your question here" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" id="question-required-${questionIndex}"
                            name="questions[${questionIndex}][required]" class="form-check-input">
                        <label for="question-required-${questionIndex}" class="form-check-label">Required</label>
                    </div>
                    <div class="form-group options-container">
                        <label>Options</label>
                    </div>
                    <div class="d-flex align-items-center mt-2">
                        <button type="button" class="btn btn-secondary mr-2" onclick="addOption(this)">Add Option</button>
                        <button class="btn btn-md" id="moveUpButton" onclick="deleteQuestion(this);">
                            <img src="{{ asset('images/bin.png') }}" alt="" width="20px" height="20px" />
                        </button>
                    </div>
                </div>
            `;

            if (activeQuestion) {
                $(activeQuestion).after(questionHtml);
            } else {
                questionsSection.append(questionHtml);
            }

            updateQuestionIndices();
            updateAddButtonPosition();
        }

        function deleteQuestion(button) {
            $(button).closest('.question').remove();
            updateQuestionIndices();
            updateAddButtonPosition();
        }

        function updateQuestionIndices() {
            $('#questions-section .question').each((index, element) => {
                $(element).attr('data-index', index);
                $(element).find('.question-type').attr('name', `questions[${index}][type]`);
                $(element).find('.question-input').attr('name', `questions[${index}][text]`);
                $(element).find('.question-input').attr('id', `question-text-${index}`);
                $(element).find('.form-check-input').attr('name', `questions[${index}][required]`);
                $(element).find('.form-check-input').attr('id', `question-required-${index}`);
                $(element).find('.options-container').find('.option-input').each((optionIndex, optionElement) => {
                    $(optionElement).attr('name', `questions[${index}][options][${optionIndex}]`);
                });
            });
        }

        function handleQuestionTypeChange(selectElement) {
            const selectedType = $(selectElement).val();
            const optionsContainer = $(selectElement).closest('.question').find('.options-container');

            if (selectedType === 'text') {
                optionsContainer.hide();
            } else {
                optionsContainer.show();
            }
        }

        function updateAddButtonPosition() {
            const sidebar = document.querySelector(".sidebar");
            const questionsSection = document.getElementById("questions-section");
            const questions = questionsSection.querySelectorAll('.question');

            if (activeQuestion) {
                const activeIndex = Array.from(questions).indexOf(activeQuestion);
                const top = activeQuestion.offsetTop + activeQuestion.offsetHeight / 2 - sidebar.offsetHeight / 2;
                sidebar.style.transform = `translateY(${top}px)`;
            } else if (questions.length > 0) {
                const firstQuestion = questions[0];
                const top = firstQuestion.offsetTop + firstQuestion.offsetHeight / 2 - sidebar.offsetHeight / 2;
                sidebar.style.transform = `translateY(${top}px)`;
            } else {
                sidebar.style.transform = 'translateY(0)';
            }
        }

        $(document).ready(function () {
            $('#profileMenuButton').click(function () {
                $('#profileMenu').toggleClass('hidden');
            });

            $(document).click(function (event) {
                if (!$(event.target).closest('#profileMenuButton, #profileMenu').length) {
                    $('#profileMenu').addClass('hidden');
                }
            });

            $('.question-type').each((index, element) => {
                handleQuestionTypeChange(element);
            });

            updateAddButtonPosition();

            $('#edit-form').on('submit', function(e) {
                e.preventDefault();
                updateQuestionIndices();
                this.submit();
            });
        });

        let activeQuestion = null;

        function setActiveQuestion(questionElement) {
            if (activeQuestion) {
                activeQuestion.classList.remove('active-question');
            }
            activeQuestion = questionElement;
            activeQuestion.classList.add('active-question');
            updateAddButtonPosition();
        }
    </script>
</body>

</html>
