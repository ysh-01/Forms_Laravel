document.addEventListener('DOMContentLoaded', () => {
    const questionsContainer = document.getElementById('questions-container');
    const addQuestionButton = document.getElementById('add-question-button');

    addQuestionButton.addEventListener('click', () => {
        const questionCount = document.querySelectorAll('.question-block').length;
        const questionBlock = document.createElement('div');
        questionBlock.className = 'question-block mb-4 p-4 bg-gray-50 rounded-lg shadow-inner';
        questionBlock.innerHTML = `
            <div class="mb-2">
                <label class="block text-gray-700 font-semibold">Question</label>
                <input type="text" name="questions[${questionCount}][text]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="options-container mb-2"></div>
            <button type="button" class="add-option-button text-blue-500">Add Option</button>
        `;
        questionsContainer.appendChild(questionBlock);
        addOptionButtonListener(questionBlock.querySelector('.add-option-button'));
    });

    document.querySelectorAll('.add-option-button').forEach(button => {
        addOptionButtonListener(button);
    });

    function addOptionButtonListener(button) {
        button.addEventListener('click', () => {
            const questionBlock = button.closest('.question-block');
            const optionsContainer = questionBlock.querySelector('.options-container');
            const optionCount = optionsContainer.querySelectorAll('.option-block').length;
            const optionBlock = document.createElement('div');
            optionBlock.className = 'option-block mb-1 flex items-center';
            optionBlock.innerHTML = `
                <input type="text" name="questions[${Array.from(questionsContainer.children).indexOf(questionBlock)}][options][${optionCount}][text]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <button type="button" class="remove-option-button text-red-500 ml-2">Remove</button>
            `;
            optionsContainer.appendChild(optionBlock);
            addRemoveOptionButtonListener(optionBlock.querySelector('.remove-option-button'));
        });
    }

    document.querySelectorAll('.remove-option-button').forEach(button => {
        addRemoveOptionButtonListener(button);
    });

    function addRemoveOptionButtonListener(button) {
        button.addEventListener('click', () => {
            const optionBlock = button.closest('.option-block');
            optionBlock.remove();
        });
    }
});
