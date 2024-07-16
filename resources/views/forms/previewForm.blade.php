<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Preview</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset("css/preview.css")}}">
</head>

<body>
    <div class="form_preview">
        <h1 id="form_name"></h1>
        <p id="form_desc"></p>
        <div id="questions_container"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formName = document.getElementById('form_name');
            const formDesc = document.getElementById('form_desc');
            const questionsContainer = document.getElementById('questions_container');

            function getParameterByName(name, url = window.location.href) {
                name = name.replace(/[\[\]]/g, '\\$&');
                const regex = new RegExp(`[?&]${name}(=([^&#]*)|&|#|$)`),
                      results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, ' '));
            }

            const formTitle = getParameterByName('title');
            const formDescription = getParameterByName('description');
            const formData = JSON.parse(getParameterByName('data'));

            formName.textContent = formTitle;
            formDesc.textContent = formDescription;

            formData.forEach((question, index) => {
                const questionType = question.type;
                const questionText = question.text;
                const options = question.options;

                let questionHtml = `<div class="question"><h3 class="question_title">${questionText}</h3><div class="options">`;

                options.forEach(option => {
                    if (questionType === 'multiple_choice') {
                        questionHtml += `<div class="option"><input type="radio" name="q${index}">${option}</div>`;
                    } else if (questionType === 'checkbox') {
                        questionHtml += `<div class="option"><input type="checkbox" name="q${index}">${option}</div>`;
                    } else if (questionType === 'dropdown') {
                        if (option === options[0]) questionHtml += `<select class="form-control">`;
                        questionHtml += `<option>${option}</option>`;
                        if (option === options[options.length - 1]) questionHtml += `</select>`;
                    }
                });

                questionHtml += `</div></div>`;
                questionsContainer.innerHTML += questionHtml;
            });
        });
    </script>
</body>

</html>
