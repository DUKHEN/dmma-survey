<!-- Edit Survey Modal -->
<div class="modal fade text-start" id="editSurvey" tabindex="-1" aria-labelledby="createSurveyLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title" id="createSurveyLabel">Edit survey</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateSurveyForm" method="POST" action="survey_details_process.php">
                <input type="hidden" name="action" value="updateSurvey">
                <div class="modal-body py-2">
                    <input type="hidden" name="survey_id" id="survey_id" value="<?php echo htmlspecialchars($survey_details['survey_id']); ?>">

                    <div class="mb-3">
                        <label for="title" class="col-form-label">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($survey_details['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="col-form-label">Description:</label>
                        <textarea class="form-control h-2" id="description" name="description" required><?php echo htmlspecialchars($survey_details['description']); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="confirmUpdateSurvey" name="update_survey">Update Survey</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title" id="addQuestionModalLabel">Add New Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <form method="POST" action="survey_details_process.php">
                    <input type="hidden" name="action" value="addQuestion">
                    <input type="hidden" name="survey_id" value="<?php echo htmlspecialchars($survey_details['survey_id']); ?>">
                    <div class="mb-3">
                        <label for="questionText" class="form-label">Question Text</label>
                        <input type="text" class="form-control" id="questionText" name="question_text" placeholder="Enter your question" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="confirmCreateSurvey" name="add_question">Add Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Question Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-4">
            <form id="editForm" method="POST" action="survey_details_process.php">
                <input type="hidden" name="action" value="editQuestion">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-2">
                    <input type="hidden" name="survey_id" id="updateSurveyId">
                    <input type="hidden" name="question_id" id="updateQuestionId">
                    <div class="mb-3">
                        <label for="updateQuestionText" class="form-label">Question Text</label>
                        <input type="text" class="form-control" id="updateQuestionText" name="question_text" required>
                    </div>

                    <!-- Question Type Dropdown -->
                    <div class="mb-3">
                        <label for="updateQuestionType" class="form-label">Question Type</label>
                        <select class="form-select" id="updateQuestionType" name="question_type" required>
                            <option value="rating">Rating</option>
                            <option value="emotion">Emotion</option>
                            <option value="thumbs">Thumbs</option>
                            <option value="text">Text</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="confirmUpdateQuestion" name="edit_question">Update Question</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Question Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <form id="editForm" method="POST" action="survey_details_process.php">
                <input type="hidden" name="action" value="deleteQuestion">
                <input type="type" name="survey_id" id="deleteSurveyId">
                <input type="type" name="question_id" id="questionIdInput">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-2">
                    <p>Are you sure you want to delete this question?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="confirmDeleteQuestion">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Responses Modal -->
<div class="modal fade" id="responsesModal" tabindex="-1" aria-labelledby="responsesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title" id="responsesModalLabel">Responses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <!-- Toggle Show Responses -->
                <div class="d-flex align-items-center mb-4">
                    <label class="form-check-label me-3" for="toggleResponses">Show Responses:</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input fs-4" type="checkbox" id="toggleResponses">
                    </div>
                </div>

                <!-- Date Filter Section -->
                <div class="mb-4">
                    <label for="dateFilter" class="form-label">Filter by Date:</label>
                    <select id="dateFilter" class="form-select w-auto d-inline-block">
                        <option value="this_month" selected>This Month</option>
                        <option value="three_months">Last 3 Months</option>
                        <option value="six_months">Last 6 Months</option>
                        <option value="one_year">Last 1 Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>

                <!-- Custom Date Range -->
                <div id="customDateRange" class="row g-2 d-none">
                    <div class="col">
                        <input type="date" id="startDate" class="form-control" placeholder="Start Date">
                    </div>
                    <div class="col">
                        <input type="date" id="endDate" class="form-control" placeholder="End Date">
                    </div>
                </div>

                <!-- Responses Content Placeholder -->
                <div id="responsesContent" class="border p-3 rounded mt-4 text-muted text-center">
                    Select filters to view responses.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="applyButton" class="btn btn-primary">Apply</button>
            </div>
        </div>
    </div>
</div>

