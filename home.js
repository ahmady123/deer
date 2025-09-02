document.addEventListener("DOMContentLoaded", function() {
    let sessionStarted = false; // Variable to store session status

    // Check session status when the page loads
    checkSessionStatus();

    // Target both .button and .button1 classes
    const buttons = document.querySelectorAll('.button, .button1');  

    const logoutConfirmation = document.getElementById('logout-confirmation');
    const closeButton = document.querySelector('.close-button');
    const confirmLogoutButton = document.getElementById('confirm-logout');
    const cancelLogoutButton = document.getElementById('cancel-logout');

    // Ensure buttons exist
    if (buttons.length === 0) {
        console.error('Button elements not found');
    } else {
        buttons.forEach((button) => {
            button.addEventListener('click', (event) => {
                console.log('Button clicked:', event.target.id);  // Log the button click for debugging
                
                if (button.id === 'button2') {  // Check if it's the logout button
                    event.preventDefault();  // Prevent the default action (navigation or form submit)
                    logoutConfirmation.style.display = 'flex';  // Show the logout confirmation dialog
                } else {
                    // Check if session is started
                    if (sessionStarted) {
                        console.log('Session started');
                        window.location.href = 'profile.php';  // Redirect to profile.php
                    } else {
                        console.log('Session not started. Redirecting to login page.');
                        window.location.href = 'login.php';  // Redirect to login
                    }
                }
            });
        });
    }

    // Close logout confirmation when close button is clicked
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            logoutConfirmation.style.display = 'none';  // Hide the confirmation dialog
        });
    }

    // Confirm logout action
    if (confirmLogoutButton) {
        confirmLogoutButton.addEventListener('click', function() {
            window.location.href = 'logout.php';  // Redirect to logout page
        });
    }

    // Cancel logout action
    if (cancelLogoutButton) {
        cancelLogoutButton.addEventListener('click', function() {
            logoutConfirmation.style.display = 'none';  // Hide the confirmation dialog
        });
    }

    // Function to check if the session is started
    function checkSessionStatus() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'check_session.php', true);  // PHP script to check session status
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                sessionStarted = response.sessionStarted;
                console.log('Session status:', sessionStarted);  // Log session status for debugging
            } else {
                console.error('Failed to check session status');
            }
        };
        xhr.onerror = function() {
            console.error('Request failed');
        };
        xhr.send();
    }
});
