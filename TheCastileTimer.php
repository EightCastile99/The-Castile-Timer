<?php
// PHP code for any server-side functionality can be added here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Castile Timer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <a href="index.php"><div class="logo">The Castile Timer</div></a>
        <nav>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </nav>
    </header>

    <!-- Announcement -->
    <div class="announcement">
        <div class="announcement-content">
            <strong>New Feature:</strong> Now you can save your timer presets! <a href="#learn-more">Learn more</a>
        </div>
    </div>

    <!-- Side Announcements -->
    <div class="side-announcements">
        <div class="side-announcement left">
            <h3>Did you know?</h3>
            <p>You can use keyboard shortcuts!</p>
            <a href="#shortcuts">Learn More</a>
        </div>
    </div>

    <!-- Timer Section -->
    <section class="timer-section">
        <div class="timer-container">
            <div id="timer">00:00:00</div>
            <div class="buttons">
                <button class="button start" id="startButton">Start</button>
                <button class="button stop" id="stopButton">Stop</button>
                <button class="button clear" id="clearButton">Clear</button>
            </div>
            <div class="time-input">
                <input type="text" id="hours" placeholder="HH" maxlength="2">
                <input type="text" id="minutes" placeholder="MM" maxlength="2">
                <input type="text" id="seconds" placeholder="SS" maxlength="2">
            </div>
            <div class="progress-container">
                <div id="progress-bar"></div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> The Castile Timer. All rights reserved.</p>
        <p><a href="#privacy">Privacy Policy</a> | <a href="#terms">Terms of Service</a></p>
    </footer>

    <script>
        // Timer variables
        let timeLeft = 0;
        let timerId = null;
        let totalTime = 0;

        // DOM Elements
        const timer = document.getElementById('timer');
        const startButton = document.getElementById('startButton');
        const stopButton = document.getElementById('stopButton');
        const clearButton = document.getElementById('clearButton');
        const hoursInput = document.getElementById('hours');
        const minutesInput = document.getElementById('minutes');
        const secondsInput = document.getElementById('seconds');
        const progressBar = document.getElementById('progress-bar');

        function validateTime() {
            const hours = parseInt(hoursInput.value) || 0;
            const minutes = parseInt(minutesInput.value) || 0;
            const seconds = parseInt(secondsInput.value) || 0;
            return hours * 3600 + minutes * 60 + seconds;
        }

        function parseTime() {
            const totalSeconds = Math.floor(timeLeft);
            return { hours: Math.floor(totalSeconds / 3600), minutes: Math.floor((totalSeconds % 3600) / 60), seconds: totalSeconds % 60 };
        }

        function updateDisplay(seconds) {
            const time = parseTime();
            timer.textContent = `${String(time.hours).padStart(2, '0')}:${String(time.minutes).padStart(2, '0')}:${String(time.seconds).padStart(2, '0')}`;
            updateProgressBar(timeLeft, totalTime);
        }

        function updateProgressBar(remaining, total) {
            const percentage = ((total - remaining) / total) * 100;
            progressBar.style.width = `${percentage}%`;
        }

        function startTimer() {
            if (timerId === null) {
                const inputTime = validateTime();
                if (inputTime > 0) {
                    timeLeft = inputTime;
                    totalTime = inputTime;
                }
                if (timeLeft <= 0) return;

                timerId = setInterval(() => {
                    timeLeft -= 1;
                    if (timeLeft <= 0) {
                        clearInterval(timerId);
                        timerId = null;
                        timeLeft = 0;
                        alert('Timer finished!');
                    }
                    updateDisplay();
                }, 1000);

                startButton.textContent = 'Pause';
            } else {
                clearInterval(timerId);
                timerId = null;
                startButton.textContent = 'Start';
            }
        }

        function stopTimer() {
            if (timerId !== null) {
                clearInterval(timerId);
                timerId = null;
                startButton.textContent = 'Start';
            }
        }

        function clearTimer() {
            stopTimer();
            timeLeft = 0;
            totalTime = 0;
            updateDisplay();
            hoursInput.value = '';
            minutesInput.value = '';
            secondsInput.value = '';
        }

        // Event Listeners
        startButton.addEventListener('click', startTimer);
        stopButton.addEventListener('click', stopTimer);
        clearButton.addEventListener('click', clearTimer);

        // Initial display
        updateDisplay();
    </script>
</body>
</html>
