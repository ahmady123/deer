<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <link rel="icon" href="mammal.png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="forgotpass.css">
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <style>
        .nav-link {
            font-weight: bold;
            color: black !important;
        }
        body {
            background-size: cover;
        }
        .login-back-link {
    color: black; /* Default text color */
    font-size: 18px; /* Font size */
    font-weight: bold; /* Bold text */
    text-decoration: none; /* No underline */
    position: relative; /* For pseudo-element */
    transition: color 0.3s ease-in-out; /* Smooth transition */
}

.login-back-link::after {
    content: ""; /* Empty content for effect */
    position: absolute;
    left: 0;
    bottom: -3px; /* Position slightly below text */
    width: 0;
    height: 2px; /* Thin underline */
    background-color: black; /* Same as text color */
    transition: width 0.3s ease-in-out; /* Smooth expand effect */
}

.login-back-link:hover {
    color: black; /* Keep text color the same */
}

.login-back-link:hover::after {
    width: 100%; /* Expand underline */
}



    </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="mammal.png" alt="Logo" style="max-height: 40px;"> Deer
            </a>
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i>Home</a>
                    </li>
                </ul>
        </div>
    </nav>
<br><br><br>
<div class="row">
    <div class="col-sm-12">
        <div class="a">
            <h1>Forgot Password</h1>
            <div class="input-container">
                <form id="signupForm">
                    <input type="email" name="email-signup" id="email-signup" placeholder="Email" required><br>
                    <span id="email-error-signup" style="color: red;"></span>
                    <span id="email-error" style="color: red;"></span>
                    <input type="submit" class="Btn" value="Verify Otp"><br>
                </form><br>
                <a href="login.php" class="login-back-link"><i class="fa-solid fa-right-to-bracket"></i>Login Back</a>
            </div>
            <div id="otpVerificationSection" style="display:none;">
                <h2>Enter OTP</h2><br>
                <input type="text" id="otp" placeholder="Enter OTP" required><br>
                <span id="otp-error" style="color: red;"></span> <br>
                <button id="verifyOtpButton">Verify OTP</button>
            </div>
            <div id="newPasswordSection" style="display:none;">
                <h2>Reset Password</h2>
                <div class="input-wrapper">
                    <input type="password" id="new-password" placeholder="Enter your password" oninput="validatePassword()">
                    <i id="togglePassword" class="fa-regular fa-eye"></i>
                </div>
                <span id="error-message" style="color:red; display:none;">Password is not strong enough</span>
                <button id="resetPasswordButton" disabled>Reset Password</button>
            </div>
            <div id="passwordSuggestions" style="display:none; border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9; position: absolute; z-index: 1000;">
                <h3>Suggested Strong Passwords:</h3>
                <ul id="suggestion-list"></ul>
                <button id="ghg" class="btn-primary">Apply</button>
            </div>
            <script>
                function validatePassword() {
                    const password = document.getElementById("new-password").value;
                    const errorMessage = document.getElementById("error-message");
                    const resetButton = document.getElementById("resetPasswordButton");
                    const suggestions = document.getElementById("passwordSuggestions");

                    const strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

                    if (strongPasswordRegex.test(password)) {
                        errorMessage.style.display = "none";
                        resetButton.disabled = false; 
                        suggestions.style.display = "none";
                    } else {
                        errorMessage.style.display = "block";
                        errorMessage.textContent = "Password must be at least 8 characters long and include uppercase, lowercase, number, and a special character.";
                        resetButton.disabled = true; 
                        showPasswordSuggestions(); 
                    }
                }

                function showPasswordSuggestions() {
                    const suggestions = document.getElementById("passwordSuggestions");
                    const inputWrapper = document.querySelector(".input-wrapper");
                    const rect = inputWrapper.getBoundingClientRect();

                    suggestions.style.display = "block";
                    suggestions.style.top = `${rect.bottom + window.scrollY + 10}px`; 
                    suggestions.style.left = `${rect.left + window.scrollX + 200}px`;

                    generateRandomPasswords();

                    document.addEventListener("click", closeSuggestionsOnClickOutside);
                }

                function closeSuggestionsOnClickOutside(event) {
                    const suggestions = document.getElementById("passwordSuggestions");
                    if (!suggestions.contains(event.target) && event.target.id !== "new-password") {
                        suggestions.style.display = "none";
                        document.removeEventListener("click", closeSuggestionsOnClickOutside);
                    }
                }

                function generateRandomPasswords() {
                    const suggestionList = document.getElementById("suggestion-list");
                    suggestionList.innerHTML = ""; 
                    for (let i = 0; i < 1; i++) {
                        const randomPassword = generateStrongPassword();
                        const listItem = document.createElement("li");
                        listItem.textContent = randomPassword;
                        suggestionList.appendChild(listItem);
                        document.getElementById('ghg').onclick = () => applySuggestedPassword(randomPassword);
                    }
                }

                function generateStrongPassword() {
                    const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
                    let password = "";

                    for (let i = 0; i < 12; i++) {
                        const randomIndex = Math.floor(Math.random() * chars.length);
                        password += chars[randomIndex];
                    }

                    return password;
                }

                function applySuggestedPassword(password) {
                    const passwordInput = document.getElementById("new-password");
                    passwordInput.value = password;
                    validatePassword(); 
                }
            </script>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const signUpForm = document.querySelector('#signupForm');
        const otpVerificationSection = document.getElementById('otpVerificationSection');
        const verifyOtpButton = document.getElementById('verifyOtpButton');
        const newPasswordSection = document.getElementById('newPasswordSection');
        const emailError = document.getElementById('email-error'); 
        let generatedOtp = null;
        let email = null;

        signUpForm.addEventListener('submit', function (e) {
            e.preventDefault();
            email = document.getElementById('email-signup').value;

            // Check if email exists in the database
            $.ajax({
                type: 'POST',
                url: 'check_mail.php', 
                data: { email: email },
                success: function (response) {
                    if (response.exists) {
                        // Email exists, proceed to send OTP
                        generatedOtp = Math.floor(100000 + Math.random() * 900000);

                        $.ajax({
                            type: 'POST',
                            url: 'send_otp.php',
                            data: { toEmail: email, otp: generatedOtp },
                            success: function (response) {
                                console.log('OTP send response:', response);
                                try {
                                    const result = JSON.parse(response);
                                    if (result.success) {
                                        console.log('OTP sent to:', email);
                                        otpVerificationSection.style.display = 'block';
                                        signUpForm.style.display = 'none';
                                    } else {
                                        console.error('Failed to send OTP:', result.error);
                                        alert('Error sending OTP: ' + result.error);
                                    }
                                } catch (e) {
                                    console.error('Failed to parse response:', response);
                                    alert('Error sending OTP. Please check the server logs.');
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Failed to send OTP:', error);
                                alert('Error sending OTP: ' + error);
                            }
                        });
                    } else {
                        // Email not found, display error
                        emailError.textContent = 'Email is not registered. Please try again.';
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error checking email:', error);
                    alert('Error checking email. Please try again later.');
                }
            });
        });

        verifyOtpButton.addEventListener('click', function () {
            const otpInput = document.getElementById('otp').value;
            const otpError = document.getElementById('otp-error');

            if (otpInput == generatedOtp) {
                otpError.textContent = '';
                alert('OTP verified successfully! You can now reset your password.');

                otpVerificationSection.style.display = 'none';
                newPasswordSection.style.display = 'block';

                document.getElementById('resetPasswordButton').addEventListener('click', function () {
                    const newPassword = document.getElementById('new-password').value;

                    $.ajax({
                        type: 'POST',
                        url: 'reset_password.php',
                        data: { email: email, newPassword: newPassword },
                        success: function (response) {
                            alert('Password reset successfully!');
                            window.location.assign('login.php');
                        },
                        error: function (xhr, status, error) {
                            alert('Error resetting password: ' + error);
                        }
                    });
                });
            } else {
                otpError.textContent = 'Invalid OTP. Please try again.';
            }
        });

        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('new-password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            if (type === 'password') {
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            } else {
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            }
        });
    });
</script>
</body>
</html>
