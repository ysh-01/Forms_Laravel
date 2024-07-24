



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Google-Form-Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href={{ asset('css/app.css') }} />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet" />
</head>

<body style="background-color: #f4f4f9;" >
    <nav class="bg-white p-4 shadow-md">
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
    <br><br>
    <div style="background-color: #f4f4f9; max-width: 100%" class="question_form p-4 rounded">
        <div class="section">
            <div class="question_title_section mb-4">
                <div style="border-bottom:5px; border-radius:5px;" class="question_form_top shadow-sm">
                    <input type="text" id="form-title" name="title" value="{{ $data['title'] ?? '' }}" class="form-control form-control-lg p-2 mb-2" placeholder="Untitled Form" />
                    <input type="text" name="description" id="form-description" value="{{ $data['description'] ?? '' }}" class="form-control form-control-sm" placeholder="Form Description" />
                </div>
            </div>
        </div>
        <div class="section" id="questions_section">
            <div class="question mb-4 p-4 border rounded bg-white shadow-sm">
                <select class="form-control question_type mb-1" onchange="changeQuestionType(this)">
                    <option value="">Select Question Type</option>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="dropdown">Dropdown</option>
                    <option value="text">Text</option>
                </select>
                <input type="text" name="question" class="form-control question-input mb-3" placeholder="Type your question here" />
                <div class="options-container mb-3">

                </div>
                <button class="btn btn-secondary add-option-btn" onclick="addOption(this)">
                    Add Option
                </button>
                <button class="btn btn-md" id="moveUpButton" onclick="deleteQuestion(this);">
                    <img src="{{ asset('images/bin.png') }}" alt="" width="20px" height="20px" />
                </button>
                <label class="ml-3">
                    <input type="checkbox" class="required-checkbox"> Required
                </label>
            </div>
            <div class="sidebar">
                <div id="moveableDiv">
                    <button class="btn btn-light shadow-sm" onclick="addNewQuestion();">
                        <img src="{{ asset('images/add.png') }}" alt="" width="20px" height="20px" />
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div style="background-color: #f4f4f9;" class="btnsub">
        <span>
            <button type="submit" name="save" data-action:"save" value="save" onclick="saveForm()"
                class="btnsave btn btn-secondary">
                Save
            </button>
        </span>
        &nbsp;
        &nbsp;
        &nbsp;
        <span>
            <button type="submit" name="publish" data-action:"publish" value="publish" onclick="saveForm()"
                class="btnsave btn btn-secondary">
                Publish
            </button>
        </span>
        &nbsp;
        &nbsp;
        &nbsp;
        &nbsp;
        &nbsp;
        &nbsp;
    </div>
    <script src={{ asset('js/script.js') }}></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
