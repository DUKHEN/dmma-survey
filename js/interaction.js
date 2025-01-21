document.addEventListener('DOMContentLoaded', function () {
    try {
        const slides = document.querySelectorAll('.carousel-slide');
        const thankYouSlide = document.getElementById('thankYouSlide');
        const lottieConfetti = document.getElementById('lottie-confetti');
        const countdownElement = document.getElementById('countdown');
        const reloadNowBtn = document.getElementById('reloadNowBtn');
        const submitButton = document.querySelector('.submit-btn');
        const submitSurveyBtn = document.getElementById('confirmSubmitSurvey');


        const dateFilter = document.getElementById('dateFilter');
        const customDateRange = document.getElementById('customDateRange');
        const responsesContent = document.getElementById('responsesContent');
        const toggleResponses = document.getElementById('toggleResponses');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const applyButton = document.getElementById('applyButton');

        let countdownTimer;

        function handleSlideButtons() {
            slides.forEach((slide, index) => {
                const nextButton = slide.querySelector('.next-btn');
                const radioButtons = slide.querySelectorAll('input[type="radio"]');
        
                // Disable buttons initially
                if (nextButton) nextButton.disabled = true;
                if (submitButton) submitButton.disabled = true;
        
                // Add event listener for radio buttons in the slide
                radioButtons.forEach(radio => {
                    radio.addEventListener('change', () => {
                        // Enable the next button for the current slide when an option is selected
                        if (nextButton) nextButton.disabled = false;
        
                        // Check if all slides are answered
                        const allAnswered = Array.from(slides).every(slide => {
                            const radios = slide.querySelectorAll('input[type="radio"]');
                            return radios.length === 0 || Array.from(radios).some(radio => radio.checked);
                        });
        
                        // Enable or disable the submit button based on allAnswered
                        if (submitButton) submitButton.disabled = !allAnswered;
                    });
                });
            });
        }
        

        function showThankYouSlide() {
            if (!thankYouSlide) return;

            slides.forEach(slide => slide.classList.add('d-none', 'active'));
            thankYouSlide.classList.remove('d-none');
            thankYouSlide.classList.add('active');

            if (lottieConfetti) {
                const animation = lottie.loadAnimation({
                    container: lottieConfetti,
                    renderer: 'svg',
                    loop: false,
                    autoplay: true,
                    path: 'img/animations/confetti.json',
                });

                animation.addEventListener('complete', () => {
                    lottieConfetti.classList.add('fade-out');
                    setTimeout(() => lottieConfetti.remove(), 1000);
                });
            }

            if (countdownElement) {
                let countdown = 10; // Countdown in seconds
                countdownElement.textContent = countdown;

                countdownTimer = setInterval(() => {
                    countdown--;
                    countdownElement.textContent = countdown;

                    if (countdown <= 0) {
                        clearInterval(countdownTimer);
                        location.reload();
                    }
                }, 1000);
            }

            if (reloadNowBtn) {
                reloadNowBtn.addEventListener('click', () => {
                    clearInterval(countdownTimer);
                    location.reload();
                });
            }
        }

        function loadFilterSettings() {
            try {
                const savedFilterValue = localStorage.getItem('dateFilter');
                const savedShowResponses = localStorage.getItem('showResponses') === 'true';
                const savedStartDate = localStorage.getItem('startDate');
                const savedEndDate = localStorage.getItem('endDate');

                if (toggleResponses) toggleResponses.checked = savedShowResponses;

                if (savedFilterValue && dateFilter) {
                    dateFilter.value = savedFilterValue;

                    if (savedFilterValue === 'custom' && customDateRange) {
                        customDateRange.classList.remove('d-none');
                        if (savedStartDate && startDateInput) startDateInput.value = savedStartDate;
                        if (savedEndDate && endDateInput) endDateInput.value = savedEndDate;
                    } else if (customDateRange) {
                        customDateRange.classList.add('d-none');
                    }
                }
            } catch (error) {
                console.error('Error loading filter settings:', error);
            }
        }

        function saveFilterSettings() {
            try {
                if (toggleResponses) localStorage.setItem('showResponses', toggleResponses.checked);
                if (dateFilter) localStorage.setItem('dateFilter', dateFilter.value);

                if (dateFilter && dateFilter.value === 'custom') {
                    if (startDateInput) localStorage.setItem('startDate', startDateInput.value);
                    if (endDateInput) localStorage.setItem('endDate', endDateInput.value);
                } else {
                    localStorage.removeItem('startDate');
                    localStorage.removeItem('endDate');
                }
            } catch (error) {
                console.error('Error saving filter settings:', error);
            }
        }

        function updateResponses() {
            try {
                const showResponses = toggleResponses && toggleResponses.checked;
                const responseElements = document.querySelectorAll('.response-count');

                responseElements.forEach(element => {
                    if (showResponses) {
                        element.classList.remove('d-none'); // Show responses
                    } else {
                        element.classList.add('d-none'); // Hide responses
                    }
                });

                console.log(`Responses are now ${showResponses ? 'visible' : 'hidden'}.`);
            } catch (error) {
                console.error('Error updating responses:', error);
            }
        }

        if (applyButton) {
            applyButton.addEventListener('click', function () {
                updateResponses();
                saveFilterSettings();

                // Construct URL with filters
                const url = new URL(window.location.href);
                if (toggleResponses) url.searchParams.set('showResponses', toggleResponses.checked);
                if (dateFilter) {
                    url.searchParams.set('dateFilter', dateFilter.value);

                    if (dateFilter.value === 'custom') {
                        if (startDateInput && startDateInput.value && endDateInput && endDateInput.value) {
                            url.searchParams.set('startDate', startDateInput.value);
                            url.searchParams.set('endDate', endDateInput.value);
                        } else {
                            alert('Please provide both start and end dates for the custom range.');
                            return;
                        }
                    } else {
                        url.searchParams.delete('startDate');
                        url.searchParams.delete('endDate');
                    }
                }

                console.log('Filters applied. Redirecting with URL:', url.toString());
                window.location.href = url.toString();
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

        if (dateFilter) {
            dateFilter.addEventListener('change', function () {
                if (dateFilter.value === 'custom' && customDateRange) {
                    customDateRange.classList.remove('d-none');
                } else if (customDateRange) {
                    customDateRange.classList.add('d-none');
                }
                saveFilterSettings();
            });
        }

        if (toggleResponses) toggleResponses.addEventListener('change', saveFilterSettings);
        if (startDateInput) startDateInput.addEventListener('change', saveFilterSettings);
        if (endDateInput) endDateInput.addEventListener('change', saveFilterSettings);


        window.printResponses = function () {
            const surveyTitle = document.querySelector("h1.display-5").textContent.trim();
            const surveyDescription = document.querySelector("p.fs-4").textContent.trim();
            const questionElements = document.querySelectorAll(".list-group-item");
        
            let formattedQuestions = "";
        
            questionElements.forEach((element) => {
                const questionText = element.querySelector("h6").textContent.trim();
                const responseIcons = element.querySelectorAll(".test");
                let responseHTML = "";
        
                responseIcons.forEach((icon) => {
                    // Extract the actual icon (e.g., emoji or thumbs-up)
                    const iconHTML = icon.querySelector("label i")?.outerHTML || "";
                
                    // Safely fetch the first <p> inside .response-count
                    const responseCountElement = icon.querySelector(".response-count > p");
                    const responseCount = responseCountElement ? responseCountElement.textContent.trim() : "0";
                
                    // Build the HTML structure
                    responseHTML += `
                        <div class="icon-item text-center">
                            <div class="icon">${iconHTML}</div>
                            <div class="count">${responseCount}</div>
                        </div>
                    `;
                });
                
        
                formattedQuestions += `
                    <div class="border rounded p-3 mb-3">
                        <p class="fw-bold mb-2 fs-6">${questionText}</p>
                        <div class="icon-group d-flex justify-content-center align-items-center">${responseHTML}</div>
                    </div>
                `;
            });
        
            const printContainer = document.createElement("div");
            printContainer.id = "print-container";
            printContainer.innerHTML = `
                <div class="mt-4 text-center">
                    <h1 class="fw-bold mb-3">${surveyTitle}</h1>
                    <p class="fs-6 mb-4">${surveyDescription}</p>
                    <div>${formattedQuestions}</div>
                </div>
            `;
        
            // Append, print, and remove the print container
            document.body.appendChild(printContainer);
            window.print();
            document.body.removeChild(printContainer);
        };

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
        

        handleSlideButtons();
        loadFilterSettings();

    } catch (error) {
        console.error('Error initializing DOMContentLoaded handler:', error);
    }
});
