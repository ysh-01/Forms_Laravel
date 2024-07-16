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
        const optionDiv = span.parentElement;
        optionDiv.remove();
        updateAddButtonPosition();
    }

    function changeQuestionType(select) {
        const questionInput = select.nextElementSibling;
        questionInput.style.display = "block";
        const optionsContainer = select.nextElementSibling.nextElementSibling;
        optionsContainer.innerHTML =
            '<input type="text" class="form-control option-input" placeholder="Option 1">';
    }

    let questionCount = document.querySelectorAll(".question").length;

    function addNewQuestion() {
        const newQuestionDiv = document.createElement("div");
        newQuestionDiv.className = "question";
        newQuestionDiv.innerHTML = `
          <select class="form-control question_type" onchange="changeQuestionType(this)">
            <option value="">Select Question Type</option>
            <option value="multiple_choice">Multiple Choice</option>
            <option value="checkbox">Checkbox</option>
            <option value="dropdown">Dropdown</option>
          </select>
          <input type="text" class="form-control question-input" placeholder="Type your question here" />
          <div class="options-container">
            <div class="option">
              <input type="text" class="form-control option-input" placeholder="Option 1" />
              <span class="delete-option" onclick="deleteOption(this)">&#10005;</span>
            </div>
          </div>
          <button class="btn btn-secondary" onclick="addOption(this)">Add Option</button>
          <button class="btnn" onclick="deleteQuestion(this)">
            <img src={{ asset('images/bin.png') }} alt="" width="20px" height="20px" />
          </button>
        `;
        questionsSection.appendChild(newQuestionDiv);
        questionCount++;
        updateAddButtonPosition();
    }

    function saveForm() {
        const formTitle = document.getElementById("form-title").value;
        const formDescription =
            document.getElementById("form-description").value;
        const questions = document.querySelectorAll(".question");
        let formData = [];

        questions.forEach((question, index) => {
            const questionType = question.querySelector("select").value;
            const questionText =
                question.querySelector(".question-input").value;
            const options = Array.from(
                question.querySelectorAll(".option-input")
            ).map((input) => input.value);
            formData.push({
                type: questionType,
                text: questionText, // Ensure this matches the key in the PHP validation
                options: options,
            });
        });

        // Get CSRF token
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        let csrfToken = "";
        if (csrfTokenMeta) {
            csrfToken = csrfTokenMeta.getAttribute("content");
        } else {
            console.error("CSRF token meta tag not found.");
            // Handle the error condition gracefully or abort further execution
            return;
        }

        const data = {
            title: formTitle,
            description: formDescription,
            questions: formData,
        };

        console.log("Form Data:", data); // Log form data
        console.log("CSRF Token:", csrfToken); // Log CSRF token

        // Send AJAX request to save the form data
        fetch("/forms", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(data),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((result) => {
                console.log("Server Response:", result); // Log server response
                if (result.success) {
                    alert("Form saved successfully!");
                    window.location.href = "/forms"; // Redirect to forms index page
                } else {
                    alert("Failed to save form.");
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
    window.changeQuestionType = changeQuestionType;
    window.saveForm = saveForm;

    window.previewForm = function (formId) {
        const formTitle = document.getElementById("form-title").value;
        const formDescription =
            document.getElementById("form-description").value;
        const questions = document.querySelectorAll(".question");
        let formData = [];

        questions.forEach((question, index) => {
            const questionType = question.querySelector("select").value;
            const questionText =
                question.querySelector(".question-input").value;
            const options = Array.from(
                question.querySelectorAll(".option-input")
            ).map((input) => input.value);
            formData.push({
                type: questionType,
                text: questionText,
                options: options,
            });
        });

        const formParams = new URLSearchParams({
            title: formTitle,
            description: formDescription,
            data: JSON.stringify(formData),
        });

        window.location.href = '/forms/' + formId + '/preview';
    };

    window.addNewQuestion = addNewQuestion;
    window.deleteQuestion = deleteQuestion;
    window.addOption = addOption;
    window.changeQuestionType = changeQuestionType;
});

function deleteQuestion(element) {
    let questionContainer = element.closest(".question");
    if (questionContainer) {
        questionContainer.remove();
        updateAddButtonPosition();
    }
}

function deleteOption(span) {
    const optionDiv = span.parentElement;
    optionDiv.remove();
    updateAddButtonPosition();
}

function updateAddButtonPosition() {
    const questionsSection = document.getElementById("questions_section");
    const lastQuestion = questionsSection.lastElementChild;

    // Check if lastQuestion exists before accessing its properties
    if (lastQuestion) {
        const selectQuestionType = lastQuestion.querySelector(".question_type");

        // Ensure selectQuestionType is not null before accessing offsetTop
        if (selectQuestionType) {
            const sidebar = document.getElementById("moveableDiv");
            const offset = selectQuestionType.offsetTop - sidebar.offsetHeight;
            sidebar.style.transform = `translateY(${offset}px)`;
            console.log(`Moving sidebar to: ${offset}px`);
        } else {
            console.warn("No .question_type found in last question.");
        }
    } else {
        const sidebar = document.getElementById("moveableDiv");
        sidebar.style.transform = `translateY(0px)`;
        console.log(`Moving sidebar to: 0px`);
    }
}
