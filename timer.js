// Timer Functions
function validateTime() {
    const timeRegex = /^\d{1,2}:\d{2}$/;
    const timerText = timerDisplay.textContent;

    if (!timeRegex.test(timerText)) {
        timerDisplay.textContent = "00:00"; // Reset to default if invalid
        alert("Please enter time in MM:SS format.");
    }
}

function parseTime() {
    const [minutes, seconds] = timerDisplay.textContent.split(':').map(Number);
    return minutes * 60 + seconds;
}

async function startTimer() {
    const totalSeconds = parseTime();

    if (totalSeconds <= 0) {
        alert('Please enter a valid time');
        return;
    }

    try {
        const response = await fetch(`${API_URL}/start`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ seconds: totalSeconds }),
        });

        if (response.ok) {
            startButton.disabled = true;
            stopButton.disabled = false;
            clearButton.disabled = false;
            pollTimer();
        }
    } catch (error) {
        console.error('Error starting timer:', error);
        alert('Failed to start timer. Please try again.');
    }
}

// Adjust Clear Function
async function clearTimer() {
    try {
        const response = await fetch(`${API_URL}/clear`, {
            method: 'POST',
        });

        if (response.ok) {
            timerDisplay.textContent = '00:00';
            progressBar.style.width = '0%';
            startButton.disabled = false;
            stopButton.disabled = true;
            clearButton.disabled = true;
        }
    } catch (error) {
        console.error('Error clearing timer:', error);
        alert('Failed to clear timer. Please try again.');
    }
}
