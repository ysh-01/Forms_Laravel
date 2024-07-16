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
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>
        .shadow-custom {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-light">
    <div class="form_header shadow-md px-6 py-4 flex justify-between items-center" style="padding: 20px;">
        <div class="form_header_left">
            <img src={{ asset('images/google-form.png') }} class="form_header_icon" height="45px" width="40px" />
            <input type="text" name="title" id="form-title-nav" placeholder="Untitled Form" class="form_name" />
            <img src={{ asset('images/folder.png') }} alt="" class="form_header_icon" height="20px"
                width="20px" />
            <img src={{ asset('images/star.svg') }} alt="" class="form_header_icon" />
        </div>
        <div class="form_header_right">
            <span><img src={{ asset('images/palette-svgrepo-com.svg') }} alt="pallette" height="20px"
                    width="20px" /></span>
            <span><img src={{ asset('images/view.png') }} alt="eye" height="20px" width="20px"
                onclick="previewForm()" /></span>
            <span><img src={{ asset('images/undo.png') }} alt="" height="20px" width="20px" /></span>
            <span><img src={{ asset('images/forward.png') }} alt="" height="20px" width="20px" /></span>
            <button class="btn">Send</button>
            <span><img src={{ asset('images/menu.png') }} alt="menu" height="30px" width="30px" /></span>
            <span><img src={{ asset('images/user.png') }} alt="" height="30px" width="30px" /></span>
        </div>
    </div>


    <div style="max-width: 700px" class="container">
        <form id="edit-form" method="POST" action="{{ route('forms.update', $form) }}" class="bg-white p-4 rounded shadow-sm">
            @csrf
            @method('PUT')
            <div class="form-group">
                <input type="text" id="form-title" name="title" class="form-control form-control-lg mb-2" placeholder="Untitled Form" value="{{ $form->title }}" />
            </div>
            <div class="form-group">
                <input type="text" name="description" id="form-description" class="form-control form-control-sm" placeholder="Form Description" value="{{ $form->description }}" />
            </div>
            <div id="questions-section">
                @foreach ($questions as $index => $question)
                    <div class="question mb-4 p-3 border rounded bg-light" data-index="{{ $index }}">
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
                            <input type="text" id="question-text-{{ $index }}" name="questions[{{ $index }}][text]" class="form-control question-input" value="{{ $question->question_text }}" required>
                        </div>
                        <div class="form-group options-container">
                            <label>Options</label>
                            @if(is_array($question->options))
                                @foreach ($question->options as $optionIndex => $option)
                                    <div class="option d-flex align-items-center mb-2">
                                        <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}]" class="form-control option-input" value="{{ $option }}">
                                        <span class="delete-option ml-2 text-danger" onclick="deleteOption(this)" style="cursor: pointer;">&#10005;</span>
                                    </div>
                                @endforeach
                            @endif
                            <button type="button" class="btn btn-secondary" onclick="addOption(this)">Add Option</button>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteQuestion(this)">Delete Question</button>
                    </div>
                @endforeach
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
        }

        function deleteOption(button) {
            $(button).closest('.option').remove();
        }

        function addNewQuestion() {
            const questionsSection = $('#questions-section');
            const questionIndex = questionsSection.find('.question').length;

            const questionHtml = `
                <div class="question mb-4 p-3 border rounded bg-light" data-index="${questionIndex}">
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
                        <input type="text" id="question-text-${questionIndex}" name="questions[${questionIndex}][text]" class="form-control question-input" placeholder="Type your question here" required>
                    </div>
                    <div class="form-group options-container">
                        <label>Options</label>
                        <button type="button" class="btn btn-secondary" onclick="addOption(this)">Add Option</button>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteQuestion(this)">Delete Question</button>
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
                        alert('Form Updated!');
                        window.location.href = '/forms';
                    }
                });
            });
        });
    </script>
</body>

</html>
