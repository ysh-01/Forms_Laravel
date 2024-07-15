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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        .shadow-custom {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-purple-100">
    <nav class="bg-white p-4 shadow-md">
        <div class="mx-auto flex justify-between">
            <a href="{{ url('/') }}" class="text-purple-600 text-3xl font-bold">LaraForms</a>
            <div class="relative dropdown">
                <button id="profileMenuButton" class="flex items-center focus:outline-none">
                    <img src="{{ asset('images/user.png') }}" alt="Profile" class="w-10 h-10 rounded-full border-2 border-white">
                </button>
                <div id="profileMenu" class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2">
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
    <br><br>
    <div style="max-width: 800px; border-top: 10px solid rgb(103, 58, 183); border-radius: 8px;" class="mx-auto rounded-md bg-white">
        <h1>Edit Form</h1>
        <br>
        <form id="edit-form" method="POST" action="{{ route('forms.update', $form->id) }}" class="px-2">
            @csrf
            @method('PUT')
            <div class="form-group">
                <input type="text" id="form-title" name="title" class="question_form_top_name form-control"
                        style="color: black" placeholder="Untitled Form" value="{{$form->title}}"/>
            </div>
            <div class="form-group">
                <input type="text" name="description" id="form-description" class="question_form_top_desc"
                        style="color: black" placeholder="Form Description" value="{{$form->description}}"/>
            </div>
            <div id="questions-section">
                @foreach ($questions as $index => $question)
                <div class="py-3">
                <div class="question" data-index="{{ $index }}">
                    <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                    <div class="form-group">
                        <label for="question-type-{{ $index }}">Question Type</label>
                        <select class="form-control question-type" id="question-type-{{ $index }}" name="questions[{{ $index }}][type]">
                            <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="checkbox" {{ $question->type === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                            <option value="dropdown" {{ $question->type === 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="question-text-{{ $index }}">Question Text</label>
                        <input type="text" id="question-text-{{ $index }}" name="questions[{{ $index }}][text]" class="form-control" value="{{ $question->question_text }}" required>
                    </div>
                    <div class="form-group options-container">
                        <label>Options</label>
                        @if(is_array($question->options))
                        @foreach ($question->options as $optionIndex => $option)
                        <div class="option">
                            <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}]" class="form-control option-input" value="{{ $option }}">
                            <span class="delete-option" onclick="deleteOption(this)">&#10005;</span>
                        </div>
                        @endforeach
                        @endif
                        <button type="button" class="btn btn-secondary" onclick="addOption(this)">Add Option</button>
                    </div>
                    <button type="button" class="btn btn-danger" onclick="deleteQuestion(this)">Delete Question</button>
                </div>
            </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-primary" onclick="addNewQuestion()">Add New Question</button>
            <button type="submit" class="btn btn-success">Save</button>
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
                <div class="option">
                    <input type="text" name="questions[${questionIndex}][options][${optionIndex}]" class="form-control option-input" placeholder="Option">
                    <span class="delete-option" onclick="deleteOption(this)">&#10005;</span>
                </div>
            `;

            optionsContainer.append(optionHtml);
        }

        function deleteOption(button) {
            $(button).closest('.option').remove();
        }

        function addNewQuestion() {
            const questionsSection = $('#questions-section');
            const questionIndex = questionsSection.find('.question').length;

            const questionHtml = `
                <div class="question" data-index="${questionIndex}">
                    <div class="form-group">
                        <label for="question-type-${questionIndex}">Question Type</label>
                        <select class="form-control question-type" id="question-type-${questionIndex}" name="questions[${questionIndex}][type]">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="dropdown">Dropdown</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="question-text-${questionIndex}">Question Text</label>
                        <input type="text" id="question-text-${questionIndex}" name="questions[${questionIndex}][text]" class="form-control" placeholder="Type your question here" required>
                    </div>
                    <div class="form-group options-container">
                        <label>Options</label>
                        <button type="button" class="btn btn-secondary" onclick="addOption(this)">Add Option</button>
                    </div>
                    <button type="button" class="btn btn-danger" onclick="deleteQuestion(this)">Delete Question</button>
                </div>
            `;

            questionsSection.append(questionHtml);
        }

        function deleteQuestion(button) {
            $(button).closest('.question').remove();
        }

        $(document).ready(function() {
            $('#edit-form').on('submit', function(e) {
                e.preventDefault();
                const formId = '{{ $form->id }}';
                $.ajax({
                    url: '/forms/' + formId,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log('Form updated successfully.');
                        window.location.href = '/forms/' + formId;
                    },
                    error: function(xhr) {
                        console.error('Error updating form:', xhr.responseText);
                        alert('An error occurred while updating the form.');
                    }
                });
            });
        });
    </script>
</body>

</html>
