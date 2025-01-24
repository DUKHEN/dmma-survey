
let selectedSurveyId = null;

function setSurveyId(surveyId) {
    selectedSurveyId = surveyId;
    console.log('Selected Survey ID:', selectedSurveyId);

    const surveyIdInput = document.getElementById('trashSurveyId');
    if (surveyIdInput) {
        surveyIdInput.value = surveyId;
    }
}

function deleteQuestionModal(surveyId, questionId) {
    const questionIdInput = document.getElementById('questionIdInput');
    const deleteSurveyId = document.getElementById('deleteSurveyId');
    if (questionIdInput && deleteSurveyId) {
        questionIdInput.value = questionId;
        deleteSurveyId.value = surveyId;
        console.log('Selected Question ID:', questionId);
        console.log('Selected Survey ID:', surveyId);
    }
}

function editQuestionModal(surveyId, questionId, questionText, questionType, dropdownOptions = []) {
    const updateSurveyId = document.getElementById('updateSurveyId');
    const updateQuestionId = document.getElementById('updateQuestionId');
    const updateQuestionText = document.getElementById('updateQuestionText');
    const updateQuestionType = document.getElementById('questionTypeEdit');

    if (updateSurveyId && updateQuestionId && updateQuestionText && updateQuestionType) {
        updateSurveyId.value = surveyId;
        updateQuestionId.value = questionId;
        updateQuestionText.value = questionText;
        updateQuestionType.value = questionType;

        if (questionType === 'dropdown') {
            const dropdownOptionsContainerEdit = document.getElementById('dropdownOptionsContainerEdit');

            dropdownOptionsContainerEdit.style.display = 'block';
            console.log(dropdownOptions);
            
            populateDropdownOptions(dropdownOptions);
        }
    } else {
        console.error("Modal input elements not found.");
    }
}


function populateDropdownOptions(options) {
    const dropdownOptionsEdit = document.getElementById('dropdownOptionsEdit');

    const parsedOptions = typeof options === 'string' ? JSON.parse(options) : options;

    dropdownOptionsEdit.innerHTML = ''; 

    parsedOptions.forEach(option => {
        const optionDiv = document.createElement('div');
        optionDiv.classList.add('d-flex', 'mb-2');
        optionDiv.innerHTML = `
            <input type="text" class="form-control" name="dropdown_options[]" value="${option}" placeholder="Enter option" required>
            <button type="button" class="btn btn-danger ms-2 remove-option">Remove</button>
        `;
        dropdownOptionsEdit.appendChild(optionDiv);
    });
}

function showAlert(message, type) {
    let alertContainer = document.getElementById('alertContainer');

    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'alertContainer';
        document.body.appendChild(alertContainer);
    }

    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.role = 'alert';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    alertContainer.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 5000);
}

// Add Modal Elements
const questionTypeAdd = document.getElementById('questionTypeAdd');
const dropdownOptionsContainerAdd = document.getElementById('dropdownOptionsContainerAdd');
const dropdownOptionsAdd = document.getElementById('dropdownOptionsAdd');
const addOptionButtonAdd = document.getElementById('addOptionButtonAdd');

// Edit Modal Elements
const questionTypeEdit = document.getElementById('questionTypeEdit');
const dropdownOptionsContainerEdit = document.getElementById('dropdownOptionsContainerEdit');
const dropdownOptionsEdit = document.getElementById('dropdownOptionsEdit');
const addOptionButtonEdit = document.getElementById('addOptionButtonEdit');

// Show/Hide Dropdown Options for Add Modal
questionTypeAdd.addEventListener('change', () => {
    if (questionTypeAdd.value === 'dropdown') {
        dropdownOptionsContainerAdd.style.display = 'block';
    } else {
        dropdownOptionsContainerAdd.style.display = 'none';
        dropdownOptionsAdd.innerHTML = `
            <div class="d-flex mb-2">
                <input type="text" class="form-control" name="dropdown_options[]" placeholder="Enter option">
                <button type="button" class="btn btn-danger ms-2 remove-option">Remove</button>
            </div>`;
    }
});

// Show/Hide Dropdown Options for Edit Modal
questionTypeEdit.addEventListener('change', () => {
    if (questionTypeEdit.value === 'dropdown') {
        dropdownOptionsContainerEdit.style.display = 'block';
    } else {
        dropdownOptionsContainerEdit.style.display = 'none';
        dropdownOptionsEdit.innerHTML = `
            <div class="d-flex mb-2">
                <input type="text" class="form-control" name="dropdown_options[]" placeholder="Enter option">
                <button type="button" class="btn btn-danger ms-2 remove-option">Remove</button>
            </div>`;
    }
});

// Add Option for Add Modal
addOptionButtonAdd.addEventListener('click', () => {
    const optionDiv = document.createElement('div');
    optionDiv.classList.add('d-flex', 'mb-2');
    optionDiv.innerHTML = `
        <input type="text" class="form-control" name="dropdown_options[]" placeholder="Enter option">
        <button type="button" class="btn btn-danger ms-2 remove-option">Remove</button>
    `;
    dropdownOptionsAdd.appendChild(optionDiv);
});

// Add Option for Edit Modal
addOptionButtonEdit.addEventListener('click', () => {
    const optionDiv = document.createElement('div');
    optionDiv.classList.add('d-flex', 'mb-2');
    optionDiv.innerHTML = `
        <input type="text" class="form-control" name="dropdown_options[]" placeholder="Enter option">
        <button type="button" class="btn btn-danger ms-2 remove-option">Remove</button>
    `;
    dropdownOptionsEdit.appendChild(optionDiv);
});

// Remove Option Handler
document.body.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-option')) {
        e.target.parentElement.remove();
    }
});


