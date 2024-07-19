document.addEventListener("DOMContentLoaded", function () {
    const questionsSection = document.getElementById("questions_section");

    function addOption(button) {
        const optionContainer = button.previousElementSibling;
        const optionDiv = document.createElement("div");
        optionDiv.className = "option";
        optionDiv.innerHTML = `
            <input type="text" class="form-control option-input" placeholder="New Option" />
            <span class="delete-option" onclick="deleteOption(this)">&#10005;</span>
        `;
        optionContainer.appendChild(optionDiv);
        updateAddButtonPosition();
    }

    function deleteOption(span) {
        console.log("Yash");
        const optionDiv = span.parentElement;
        optionDiv.remove();
        updateAddButtonPosition();
    }

    function changeQuestionType(selectElement) {
        const questionContainer = selectElement.closest('.question');
        const optionsContainer = questionContainer.querySelector('.options-container');
        const addOptionButton = questionContainer.querySelector('.btn-secondary');
        const questionType = selectElement.value;

        // Clear the options container
        optionsContainer.innerHTML = '';

        if (questionType === 'multiple_choice' || questionType === 'checkbox' || questionType === 'dropdown') {
            const optionDiv = document.createElement('div');
            optionDiv.className = 'option d-flex align-items-center mb-2';
            optionDiv.innerHTML = `
                <input type="text" name="option" class="form-control option-input" placeholder="Option 1" />
                <span class="delete-option ml-2 text-danger" onclick="deleteOption(this)" style="cursor: pointer;">&#10005;</span>
            `;
            optionsContainer.appendChild(optionDiv);
            addOptionButton.style.display = 'inline-block'; // Show the "Add Option" button
        } else if (questionType === 'text') {
            addOptionButton.style.display = 'none'; // Hide the "Add Option" button
        }
    }

    let questionCount = document.querySelectorAll(".question").length;

    function addNewQuestion() {
        const newQuestionDiv = document.createElement("div");
        // newQuestionDiv.className = "question";
        newQuestionDiv.innerHTML = `
           <div class="question mb-4 p-4 border rounded bg-white">
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
                    <img src="/images/bin.png" alt="" width="20px" height="20px" />
                </button>
                <label class="ml-3">
                    <input type="checkbox" class="required-checkbox"> Required
                </label>
            </div>
        `;
        questionsSection.appendChild(newQuestionDiv);
        questionCount++;
        updateAddButtonPosition();
    }

    function deleteQuestion(element) {
        let questionContainer = element.closest(".question");
        if (questionContainer) {
            questionContainer.remove();
            questionCount--;
            updateAddButtonPosition();
        }
    }

    function updateAddButtonPosition() {
        const questions = questionsSection.querySelectorAll(".question");
    const sidebar = document.getElementById("moveableDiv");

    if (questions.length > 0) {
        const lastQuestion = questions[questions.length - 1];
        const offsetTop = lastQuestion.offsetTop;
        const sidebarHeight = sidebar.offsetHeight;
        const containerHeight = questionsSection.offsetHeight;

        // Calculate the position of the last question relative to the top of the container
        const newPosition = offsetTop + lastQuestion.offsetHeight;

        // Ensure the sidebar stays within the bounds of the container
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

    function saveForm() {
        const formTitle = document.getElementById("form-title").value;
        const formDescription = document.getElementById("form-description").value;
        const questions = document.querySelectorAll(".question");
        let formData = [];

        console.log(questions);

        questions.forEach((question) => {
            const questionType = question.querySelector("select").value;
            const questionText = question.querySelector(".question-input").value;
            const isRequired = question.querySelector(".required-checkbox").checked;
            let options = [];

            if (questionType === 'multiple_choice' || questionType === 'checkbox' || questionType === 'dropdown') {
                options = Array.from(question.querySelectorAll(".option-input")).map((input) => input.value);
            }

            formData.push({
                type: questionType,
                text: questionText,
                options: options,
                required: isRequired
            });

            console.log({
                type: questionType,
                text: questionText,
                options: options,
                required: isRequired
            });
        });

        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        let csrfToken = "";
        if (csrfTokenMeta) {
            csrfToken = csrfTokenMeta.getAttribute("content");
        } else {
            console.error("CSRF token meta tag not found.");
            return;
        }

        const data = {
            title: formTitle,
            description: formDescription,
            questions: formData
        };

        console.log(data);


        fetch("/forms", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify(data),
        })
        .then((response) => response.json())
        .then((result) => {
            if (result.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Form saved successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "/forms";
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to save form.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch((error) => {
            console.error("Error saving form:", error);
            alert("An error occurred while saving the form.");
        });
    }









    window.addNewQuestion = addNewQuestion;
    window.deleteQuestion = deleteQuestion;
    window.addOption = addOption;
    window.deleteOption = deleteOption;
    window.changeQuestionType = changeQuestionType;
    window.saveForm = saveForm;

    // document.getElementById("add-question-button").addEventListener("click", addNewQuestion);

    document.getElementById("questions_section").addEventListener("DOMNodeInserted", updateAddButtonPosition);
    document.getElementById("questions_section").addEventListener("DOMNodeRemoved", updateAddButtonPosition);
});

