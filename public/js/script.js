document.addEventListener("DOMContentLoaded", function () {
    const questionsSection = document.getElementById("questions_section");

    questionsSection.addEventListener('click', function(event) {
        const questionDiv = event.target.closest('.question');
        if (questionDiv) {
            setActiveQuestion(questionDiv);
        }
    });

    function addOption(button) {
        const optionContainer = button.previousElementSibling;
        const optionDiv = document.createElement("div");
        optionDiv.className = "option";
        optionDiv.innerHTML = `
            <input type="text" class="form-control option-input mb-1" placeholder="New Option" />
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
        const questionContainer = selectElement.closest(".question");
        const optionsContainer =
            questionContainer.querySelector(".options-container");
        const addOptionButton =
            questionContainer.querySelector(".btn-secondary");
        const questionType = selectElement.value;

        // Clear the options container
        optionsContainer.innerHTML = "";

        if (
            questionType === "multiple_choice" ||
            questionType === "checkbox" ||
            questionType === "dropdown"
        ) {
            const optionDiv = document.createElement("div");
            optionDiv.className = "option d-flex align-items-center mb-2";
            optionDiv.innerHTML = `
                <input type="text" name="option" class="form-control option-input" placeholder="Option 1" />
                <span class="delete-option ml-2 text-danger" onclick="deleteOption(this)" style="cursor: pointer;">&#10005;</span>
            `;
            optionsContainer.appendChild(optionDiv);
            addOptionButton.style.display = "inline-block"; // Show the "Add Option" button
        } else if (questionType === "text") {
            addOptionButton.style.display = "none"; // Hide the "Add Option" button
        }
    }

    let activeQuestion = null;

    function setActiveQuestion(questionElement) {
        if (activeQuestion) {
            activeQuestion.classList.remove('active-question');
        }
        activeQuestion = questionElement;
        activeQuestion.classList.add('active-question');
        updateAddButtonPosition();
    }

    function updateQuestionOrder() {
        const questions = document.querySelectorAll('#questions_section .question');
        questions.forEach((question, index) => {
            question.setAttribute('data-order', index);
        });
    }

    new Sortable(document.getElementById('questions_section'), {
        animation: 150,
        handle: '.question',
        onEnd: function() {
            updateQuestionOrder();
            updateAddButtonPosition();
        }
    });

    let questionCount = document.querySelectorAll(".question").length;

    function addNewQuestion() {
        const newQuestionDiv = document.createElement("div");
        newQuestionDiv.className = "question mb-4 p-4 border rounded bg-white shadow-sm";
        newQuestionDiv.setAttribute('data-order', document.querySelectorAll('#questions_section .question').length);
        newQuestionDiv.innerHTML = `
            <select class="form-control question_type mb-1" onchange="changeQuestionType(this)">
                <option style="border:1px solid rgb(103,58,183);" value="">Select Question Type</option>
                <option value="multiple_choice">Multiple Choice</option>
                <option value="checkbox">Checkbox</option>
                <option value="dropdown">Dropdown</option>
                <option value="text">Text</option>
            </select>
            <input style="border:none; border-bottom: 2px solid rgb(103,58,183); border-radius:0" type="text" name="question" class="form-control question-input mb-3" placeholder="Type your question here" />
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
        `;
        if (activeQuestion) {
            activeQuestion.insertAdjacentElement('afterend', newQuestionDiv);
        } else {
            questionsSection.appendChild(newQuestionDiv);
        }

        setActiveQuestion(newQuestionDiv);
        updateAddButtonPosition();
    }

    function deleteQuestion(element) {
        let questionContainer = element.closest(".question");
        if (questionContainer) {
            questionContainer.remove();
            questionCount--;
            updateQuestionOrder();
            updateAddButtonPosition();
        }
    }

    function updateAddButtonPosition() {
        const sidebar = document.getElementById("moveableDiv");
        const firstQuestion = document.querySelector('.question');

        if (activeQuestion) {
            const rect = activeQuestion.getBoundingClientRect();
            const containerRect = questionsSection.getBoundingClientRect();
            const newPosition = rect.top - containerRect.top + rect.height;

            sidebar.style.transform = `translateY(${newPosition}px)`;
        } else if (firstQuestion) {
            const rect = firstQuestion.getBoundingClientRect();
            const containerRect = questionsSection.getBoundingClientRect();
            const newPosition = rect.top - containerRect.top + rect.height;

            sidebar.style.transform = `translateY(${newPosition}px)`;
        } else {
            sidebar.style.transform = `translateY(0px)`;
        }
    }

    var scrollToTopBtn = document.getElementById("scrollToTopBtn");
    var rootElement = document.documentElement;

    function handleScroll() {
        // Show button when page is scrolled down 100px
        var scrollTotal = rootElement.scrollHeight - rootElement.clientHeight;
        if ((rootElement.scrollTop / scrollTotal) > 0.20) {
            scrollToTopBtn.classList.remove("opacity-0", "invisible");
            scrollToTopBtn.classList.add("opacity-100", "visible");
        } else {
            scrollToTopBtn.classList.remove("opacity-100", "visible");
            scrollToTopBtn.classList.add("opacity-0", "invisible");
        }
    }

    function scrollToTop() {
        rootElement.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }

    scrollToTopBtn.addEventListener("click", scrollToTop);
    document.addEventListener("scroll", handleScroll);

    function saveForm() {
        const formTitle = document.getElementById("form-title").value;
        const formDescription =
            document.getElementById("form-description").value;
        const questions = document.querySelectorAll(".question");
        let formData = [];

        console.log(questions);

        questions.forEach((question, index) => {
            const questionType = question.querySelector("select").value;
            const questionText = question.querySelector(".question-input").value;
            const isRequired = question.querySelector(".required-checkbox").checked;
            let options = [];

            if (
                questionType === "multiple_choice" ||
                questionType === "checkbox" ||
                questionType === "dropdown"
            ) {
                options = Array.from(
                    question.querySelectorAll(".option-input")
                ).map((input) => input.value);
            }

            formData.push({
                type: questionType,
                text: questionText,
                options: options,
                required: isRequired,
                order: parseInt(question.getAttribute('data-order')) // Add this line
            });

            console.log({
                type: questionType,
                text: questionText,
                options: options,
                required: isRequired,
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
            questions: formData,
        };

        console.log(data);

        fetch("/forms", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(data),
        })
            .then((response) => response.json())
            .then((result) => {
                if (result.success) {
                    Swal.fire({
                        title: "Success!",
                        text: "Form saved successfully.",
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "/forms";
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to save form.",
                        icon: "error",
                        confirmButtonText: "OK",
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
    document
        .getElementById("questions_section")
        .addEventListener("DOMNodeInserted", updateAddButtonPosition);
    document
        .getElementById("questions_section")
        .addEventListener("DOMNodeRemoved", updateAddButtonPosition);

    updateAddButtonPosition();
});
