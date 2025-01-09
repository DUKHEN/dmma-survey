let selectedSurveyId = null;

function setSurveyId(surveyId) {
    selectedSurveyId = surveyId;
    console.log('Survey ID set:', selectedSurveyId);
}

function setQuestionId(questionId) {
    selectedQuestionId = questionId;
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

function submitSurvey() {
    const responses = {};

    // Collect responses
    document.querySelectorAll('input[type="radio"]:checked').forEach(input => {
        const questionId = input.name.split('_')[1]; // Extract question ID from input name
        responses[questionId] = input.value; // Store selected value
    }); 
}

document.addEventListener('DOMContentLoaded', function () {
    const moveToTrashBtn = document.getElementById('confirmMoveToTrash');
    const createSurveyBtn = document.getElementById('confirmCreateSurvey');
    const updateSurveyBtn = document.getElementById('confirmUpdateSurvey');
    const updateQuestionBtn = document.getElementById('confirmUpdateQuestion');
    const deleteQuestionBtn = document.getElementById('confirmDeleteQuestion');
    const submitSurveyBtn = document.getElementById('confirmSubmitSurvey');

    if (moveToTrashBtn) {
        moveToTrashBtn.addEventListener('click', function () {
            if (selectedSurveyId !== null) {
                sendAjaxRequest(
                    'survey_handler.php',
                    `action=moveToTrash&survey_id=${selectedSurveyId}`,
                    'Survey moved to trash successfully.',
                    'Failed to move the survey to trash.'
                );
            } else {
                showAlert('No survey selected to move to trash.', 'warning');
            }
        });
    }

    if (createSurveyBtn) {
        createSurveyBtn.addEventListener('click', function () {
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();

            if (title && description) {
                sendAjaxRequest(
                    'survey_handler.php',
                    `action=createSurvey&title=${encodeURIComponent(title)}&description=${encodeURIComponent(description)}`,
                    'Survey successfully created.',
                    'Failed to create survey.'
                );
            } else {
                showAlert('Title and description cannot be empty.', 'danger');
            }
        });
    }

    if (updateSurveyBtn) {
        updateSurveyBtn.addEventListener('click', function () {
            const survey_id = document.getElementById('survey_id').value;
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();
            const status = document.getElementById('status').checked ? 'active' : 'inactive';

            if (title && description) {
                sendAjaxRequest(
                    'survey_handler.php',
                    `action=updateSurvey&survey_id=${survey_id}&title=${encodeURIComponent(title)}&description=${encodeURIComponent(description)}&status=${status}`,
                    'Survey successfully updated.',
                    'Failed to update survey.'
                );
            } else {
                showAlert('Title and description cannot be empty.', 'danger');
            }
        });
    }

    if (updateQuestionBtn) {
        updateQuestionBtn.addEventListener('click', function () {
            const question_id = document.getElementById('updateQuestionId').value;
            const question_text = document.getElementById('updateQuestionText').value.trim();
            const survey_id = document.getElementById('updateSurveyId').value;
            const question_type = document.getElementById('updateQuestionType').value;
    
            if (question_text && question_type) {
                sendAjaxRequest(
                    'survey_handler.php',
                    `action=updateQuestion&survey_id=${survey_id}&question_id=${question_id}&question_text=${encodeURIComponent(question_text)}&question_type=${encodeURIComponent(question_type)}`,
                    'Question successfully updated.',
                    'Failed to update question.'
                );
            } else {
                showAlert('Question text and type cannot be empty.', 'danger');
            }
        });
    }
    
    if (deleteQuestionBtn) {
        deleteQuestionBtn.addEventListener('click', function () {
            if (selectedQuestionId !== null) {
                sendAjaxRequest(
                    'survey_handler.php',
                    `action=deleteQuestion&question_id=${selectedQuestionId}`,
                    'Question successfully deleted.',
                    'Failed to delete question.'
                );
            } else {
                showAlert('No question selected to delete.', 'warning');
            }
        });
    }

    if (submitSurveyBtn) {
        submitSurveyBtn.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default behavior
    
            const responses = {};
            const slides = document.querySelectorAll('.carousel-slide');
    
            // Collect responses
            slides.forEach(slide => {
                const questionId = slide.dataset.questionId; // Get question ID from data attribute
                const selectedInput = slide.querySelector('input[type="radio"]:checked');
    
                if (selectedInput) {
                    responses[questionId] = selectedInput.value; // Store response by question ID
                }
            });
    
            // Check if responses are collected
            if (Object.keys(responses).length > 0) {
                // Show the "Thank You" slide immediately
                showThankYouSlide();
    
                // Send AJAX request to submit responses
                const surveyId = selectedSurveyId; // Use global selectedSurveyId
                sendAjaxRequest(
                    'response_handler.php', // Handler file for responses
                    `action=submitSurvey&survey_id=${surveyId}&responses=${encodeURIComponent(JSON.stringify(responses))}`,
                    'Responses successfully submitted.',
                    'Failed to submit responses.',
                    function () {
                        console.log('Server processing completed.');
                    }
                );
            } else {
                showAlert('No responses selected. Please answer the questions.', 'warning');
            }
        });
    }

    function sendAjaxRequest(url, params, successMessage, errorMessage, onSuccess) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                localStorage.setItem('alertMessage', successMessage);
                localStorage.setItem('alertType', 'success');
                if (onSuccess) onSuccess();
            } else {
                showAlert(errorMessage, 'danger');
            }
        };
        xhr.onerror = function () {
            showAlert('Failed to communicate with the server.', 'danger');
        };
        xhr.send(params);
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
});
