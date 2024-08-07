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
<body class="bg-gray-100">
    <nav class="bg-white p-2 shadow-gray-lg">
        <div class="mx-auto flex justify-between items-center">
            <a href="{{ url('/forms') }}" style="color: rgb(103,58,183)"
                class="text-3xl font-bold font-sans">LaraForms</a>

            <div class="relative dropdown">
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
                    <div><button type="submit" name="publish" value="publish" class="btnsave btn btn-secondary"><a href="/forms/{{$form->id}}/edit">Edit</a></button></div>
                </div>
            </div>
        </div>
    </nav>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded relative mt-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('delete'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative mt-4" role="alert">
            <span class="block sm:inline">{{ session('delete') }}</span>
        </div>
    @endif
    <div class="question_form bg-gray-100 p-4 rounded shadow-sm">
        <div class="section">
            <div class="question_title_section mb-4">
                <div class="question_form_top">
                    <input style="background-color: white; border:none; border-bottom: 2px solid rgb(103,58,183); border-radius:0;" type="text" id="form-title" name="title" class="form-control form-control-lg mb-2" style="color: black" placeholder="Untitled Form" value="{{ $form->title }}" readonly />
                    <input style="background-color: white; border:none; border-bottom: 2px solid rgb(103,58,183); border-radius:0;" type="text" name="description" id="form-description" class="form-control form-control-sm" style="color: black" placeholder="Form Description" value="{{ $form->description }}" readonly />
                </div>
            </div>
        </div>
        <div class="section shadow-sm" id="questions_section">
            @foreach ($form->questions as $index => $question)
                <div class="question mb-4 p-4 border rounded bg-white shadow-sm">
                    <select style="background-color: white; color:bLack;" class="form-control question_type mb-3" name="questions[{{ $index }}][type]" onchange="changeQuestionType(this)" disabled>
                        <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                        <option value="checkbox" {{ $question->type === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                        <option value="dropdown" {{ $question->type === 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                        <option value="text" {{ $question->type === 'text' ? 'selected' : '' }}>Text</option>
                    </select>
                    <input style="background-color: white; color:bLack;" type="text" name="questions[{{ $index }}][text]" class="form-control question-input mb-3" placeholder="Type your question here" value="{{ $question->question_text }}" readonly />
                    @if ($question->options)
                        <div class="options-container mb-3">
                            @foreach (json_decode($question->options) as $optionIndex => $option)
                                <div class="option d-flex align-items-center mb-2">
                                    <input style="background-color: white; color:bLack;" type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}]" class="form-control option-input" placeholder="Option" value="{{ $option }}" readonly />
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
