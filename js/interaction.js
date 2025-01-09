document.addEventListener('DOMContentLoaded', function () {
    try {
        // =========================
        // DOM Element References
        // =========================
        const slides = document.querySelectorAll('.carousel-slide');
        const thankYouSlide = document.getElementById('thankYouSlide');
        const lottieConfetti = document.getElementById('lottie-confetti');
        const countdownElement = document.getElementById('countdown');
        const reloadNowBtn = document.getElementById('reloadNowBtn');
        const submitButton = document.querySelector('.submit-btn');

        const dateFilter = document.getElementById('dateFilter');
        const customDateRange = document.getElementById('customDateRange');
        const responsesContent = document.getElementById('responsesContent');
        const toggleResponses = document.getElementById('toggleResponses');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const applyButton = document.getElementById('applyButton');

        let countdownTimer;

        // =========================
        // Slide and Thank You Logic
        // =========================
        function handleSlideButtons() {
            slides.forEach(slide => {
                const nextButton = slide.querySelector('.next-btn');
                const radioButtons = slide.querySelectorAll('input[type="radio"]');

                if (radioButtons.length > 0) {
                    if (nextButton) nextButton.disabled = true;
                    if (submitButton) submitButton.disabled = true;

                    radioButtons.forEach(radio => {
                        radio.addEventListener('change', () => {
                            if (nextButton) nextButton.disabled = false;
                            if (submitButton) submitButton.disabled = false;
                        });
                    });
                }
            });
        }

        function showThankYouSlide() {
            if (!thankYouSlide) return;

            // Hide all slides and show the Thank You slide
            slides.forEach(slide => slide.classList.add('d-none', 'active'));
            thankYouSlide.classList.remove('d-none');
            thankYouSlide.classList.add('active');

            // Lottie animation for confetti
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

            // Countdown timer for page reload
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

            // Immediate reload on button click
            if (reloadNowBtn) {
                reloadNowBtn.addEventListener('click', () => {
                    clearInterval(countdownTimer);
                    location.reload();
                });
            }
        }

        // =========================
        // Filter Settings Management
        // =========================
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

        // =========================
        // Response Management
        // =========================
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

        // =========================
        // Event Listeners
        // =========================
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


        // =========================
        // Print Responses Function
        // =========================
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
        


        // =========================
        // Initialization
        // =========================
        handleSlideButtons();
        loadFilterSettings();

    } catch (error) {
        console.error('Error initializing DOMContentLoaded handler:', error);
    }
});
