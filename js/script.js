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

function editQuestionModal(surveyId, questionId, questionText, questionType) {
    const updateSurveyId = document.getElementById('updateSurveyId');
    const updateQuestionId = document.getElementById('updateQuestionId');
    const updateQuestionText = document.getElementById('updateQuestionText');
    const updateQuestionType = document.getElementById('updateQuestionType');

    if (updateSurveyId && updateQuestionId && updateQuestionText && updateQuestionType) {
        updateSurveyId.value = surveyId;
        updateQuestionId.value = questionId;
        updateQuestionText.value = questionText;
        updateQuestionType.value = questionType;
    } else {
        console.error("Modal input elements not found.");
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const moveToTrashForm = document.getElementById('moveToTrashForm');
    const createSurveyForm = document.getElementById('createSurveyForm');
    const updateSurveyForm = document.getElementById('updateSurveyForm');
    const updateQuestionForm = document.getElementById('updateQuestionForm');
    const deleteQuestionForm = document.getElementById('deleteQuestionForm');



    const updateQuestionBtn = document.getElementById('confirmUpdateQuestion');
    if (updateQuestionBtn) {
        updateQuestionBtn.addEventListener('click', function () {
            const questionText = document.getElementById('updateQuestionText').value.trim();
            const questionType = document.getElementById('updateQuestionType').value;

            if (questionText && questionType) {
                updateQuestionForm.submit();
            } else {
                showAlert('Question text and type cannot be empty.', 'danger');
            }
        });
    }
});

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
