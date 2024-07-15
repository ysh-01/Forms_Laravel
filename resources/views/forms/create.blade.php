<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Google-Form-Clone</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href={{ asset('css/app.css') }} />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet" />
</head>

<body>
    <div class="form_header">
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
    <div class="question_form">
        <br />
        <div class="section">
            <div class="question_title_section">
                <div class="question_form_top">
                    <input type="text" id="form-title" name="title" class="question_form_top_name form-control"
                        style="color: black" placeholder="Untitled Form" />
                    <input type="text" name="description" id="form-description" class="question_form_top_desc"
                        style="color: black" placeholder="Form Description" />
                </div>
            </div>
        </div>
        <div class="section" id="questions_section">
            <div class="question">
                <select class="form-control question_type" onchange="changeQuestionType(this)">
                    <option value="">Select Question Type</option>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="dropdown">Dropdown</option>
                </select>
                <input type="text" name="question" class="form-control question-input"
                    placeholder="Type your question here" />
                <div class="options-container">
                    <div class="option">
                        <input type="text" name="option" class="form-control option-input"
                            placeholder="Option 1" />
                        <span class="delete-option" onclick="deleteOption(this)">&#10005;</span>
                    </div>
                </div>
                <button class="btn btn-secondary" onclick="addOption(this)">
                    Add Option
                </button>
                <button class="btnn" id="moveUpButton" onclick="deleteQuestion(this);">
                    <img src={{ asset('images/bin.png') }} alt="" width="20px" height="20px" />
                </button>
            </div>
            <div class="sidebar">
                <div id="moveableDiv">
                    <button class="btnp" onclick="addNewQuestion(); moveDown()">
                        <img src={{ asset('images/add.png') }} alt="" width="20px" height="20px" />
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="btnsub">
        <span>
            <button type="submit" name="save" value="save" onclick="saveForm()"
                class="btnsave btn btn-secondary">
                Save
            </button>
        </span>
        &nbsp;
        &nbsp;
        &nbsp;
        <span>
            <button type="submit" name="publish" value="publish" onclick="saveForm()"
                class="btnsave btn btn-secondary">
                Publish
            </button>
        </span>
    </div>
    <script src={{ asset('js/script.js') }}></script>
</body>

</html>
