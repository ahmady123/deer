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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cloudflare-icons/1.0.1/css/cloudflare-icons.min.css" integrity="sha512-+D9jT9iZqK7Z6KvP6m9eYRfKg9eL3P+JqVf6dWwbiXjzOZ3RHb+uP4jETp2T2sNRvA6Fy4yqN2wIjO4B9+OShxg==" crossorigin="anonymous" >
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <link rel="stylesheet" href="login.css">
    <style>
        body{
         background-size: cover;
        }
        .asdf, 
.switchSectionButton {
    background-color: transparent !important;
    border: none !important;
    color: white; /* Don't use !important here to keep transition */
    text-decoration: none;
    position: relative;
    transition: color 0.3s ease-in-out;
}

.asdf:hover, 
.switchSectionButton:hover {
    color: rgba(255, 255, 255, 0.8); /* Slightly dim color on hover */
}

/* Underline effect */
.asdf::after, 
.switchSectionButton::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -3px;
    width: 0;
    height: 2px;
    background-color: white;
    transition: width 0.3s ease-in-out;
}

.asdf:hover::after, 
.switchSectionButton:hover::after {
    width: 100%;
}

/* Mobile adjustments */
@media (max-width: 1200px) {
    .asdf, 
    .switchSectionButton {
        color: #000 !important;
        transition: color 0.3s ease-in-out;
    }

    .asdf:hover, 
    .switchSectionButton:hover {
        color: rgba(0, 0, 0, 0.8); /* Dim color on hover for mobile */
    }

    .asdf::after, 
    .switchSectionButton::after {
        background-color: black; /* Adjust underline for mobile */
    }
}

        #password-suggestion-popup {
    position: absolute;
    top: 50%; 
    left: 50%;
    background-color: #fff;
    border: 1px solid #ccc; 
    border-radius: 5px; 
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

    z-index: 1000; 
    width: max-content; 
    font-size: 14px; 
    color: #333;
}

#password-suggestion-popup strong {
    color: #007bff;
    font-weight: bold;
}

#password-suggestion-popup button {
    margin-top: 8px;
    padding: 5px 10px; 
    background-color: #007bff;
    color: #fff; 
    border: none; 
    border-radius: 3px; 
    cursor: pointer; 
    font-size: 14px; 
}

#password-suggestion-popup button:hover {
    background-color: #0056b3; 
}

.a{
    height: 520px;
}
        .googleBtn {
    display: inline-flex;
    align-items: center;
    background-color: #4fb57b; 
    color: white;
    border: none;
    border-radius: 4px;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.googleBtn img {
    margin-right: 8px; 
}

.googleBtn:hover {
    background-color: #357ae8; 
    transform: translateY(-2px); 
}

.googleBtn:active {
    background-color: #3367D6; 
    transform: translateY(0); 
}

.googleBtn:focus {
    outline: none; 
    box-shadow: 0 0 0 2px rgba(66, 133, 244, 0.5); 
}
.nav-link{
    color: #000 !important;
    font-weight: bold;
}
#formContainer {
    position: fixed; 
    top: 50%; 
    right: 50%;
    transform: translate(50%, -50%); 
    display: none; 
    z-index: 1000; 
}
body {
            margin: 0;
            height: 100vh;
            overflow: hidden; 
        }
        .fd {
            font-family: 'Roboto', sans-serif; /* Change to a stylish font */
            font-size: 1.2em; /* Increase font size */
            font-weight: bold; /* Make the font bold */
            color: #333; /* Set a contrasting color */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); /* Add a subtle text shadow */
            margin: 10px 0; /* Add some margin for spacing */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="mammal.png" alt="Logo" style="max-height: 40px;"> Deer
        </a>
        <ul>
     
            <a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i>Home</a>
     
        </ul>
    </div>
</nav>
<br>
<?php

$secretKey = "6Ld3a5wqAAAAAMCxykgNuVsQSVj5mfuDba6uzWBG";

$captchaVerified = isset($_COOKIE['captcha_verified']) && $_COOKIE['captcha_verified'] === "true";


// Get the user count
$userCount = file_get_contents('user_count.txt'); // Assuming the user count is stored in a file
$userCount = (int)$userCount;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$captchaVerified && $userCount >= 500) { 

        if (isset($_POST['g-recaptcha-response'])) {
            $recaptchaResponse = $_POST['g-recaptcha-response'];

            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");

            $responseData = json_decode($response);

            if ($responseData->success) {
                setcookie("captcha_verified", "true", time() + (86400 * 30), "/");
                $captchaVerified = true;

                file_get_contents('decrease_user_count.php');
            } else {
                echo "reCAPTCHA verification failed.";
            }
        } else {
            echo "reCAPTCHA response not found.";
        }
    }
}

?>
<?php if (!$captchaVerified && $userCount >= 500): ?>
    <style>
        .row {
            display: none; 
        }
    </style>
    <div id="formContainer">
        <form id="myForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="g-recaptcha" data-sitekey="6Ld3a5wqAAAAADckP9CjfhR7KsABIhP2rnnNvPGn"></div>
            <button class="btn-primary" type="submit">Submit</button>
        </form>
    </div>
<?php else: ?>
    <!-- Optionally, show the content here if captcha is not required or passed -->
<?php endif; ?>

<script>
    $(document).ready(function() {
        // Check if user count is above or below 500
        $.get('update_user_count.php', function(userCount) {
            console.log("Number of users accessing this page: " + userCount);

            if (userCount >= 500) {
                // Show CAPTCHA if user count is 500 or more
                $('#formContainer').show();
            } else {
                // Don't hide .row and don't show CAPTCHA if user count is lower than 500
                $('.row').show();
                $('#formContainer').hide();
            }
        });

        $(window).on('beforeunload', function() {
            $.get('decrease_user_count.php');
        });
    });
</script>

<div class="row"> 
    <div class="col-lg-6 ad mobile-hide">
        <div class="fd" id="registerMessage">
            <img class="fg" src="mammal.png" alt="">
            <p>Thanks for Registering to our website.<br>Login back if you already have an account.</p>
            <button class="nn">Login</button>
        </div>
        <div class="fd" id="loginMessage" style="display:none;">
            <img class="fg" src="mammal.png" alt="">
            <p>Welcome back<br>You can also register if you don't have an account.</p>
            <button class="nn">Register</button>
        </div>
    </div>
    <div class="col-lg-6 as">
    <div class="a" id="signUpSection">
    <h1>Register</h1>
    <div class="input-container">
        <form id="signupForm">
            <input type="email" name="email-signup" id="email-signup" placeholder="Email" required><br>
            <span id="email-error-signup" style="color: red;"></span>
            <div id="email-exists-error" style="color: red; display: none;">Email already registered. Please use another email or sign in.</div>
        </div>
        <br>
        <div class="input-wrapper">
  
    <input type="password" id="password-signup" placeholder="Enter your password" required>
    <i id="toggleSignupPassword" class="fa-regular fa-eye"></i>
</div>
<span id="password-strength-error" style="color: red;"></span>
<span id="password-strength-suggestion" style="color: green;"></span>
<div id="password-suggestion-popup" style="display: none;">
</div>
<br>
<input type="checkbox"> Remember me &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <button type="button" class="asdf" onclick="location.href='forgotpassword.php'">Forgot password</button>
<br>
<input type="submit" class="Btn" value="Register" name="signUp" id="signupButton" disabled>
<p>Already Have an Account?</p>
<button type="button" class="switchSectionButton">Log In</button>

<p>___Or login with___</p>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordInput = document.getElementById('password-signup');
        const passwordStrengthError = document.getElementById('password-strength-error');
        const passwordStrengthSuggestion = document.getElementById('password-strength-suggestion');
        const passwordSuggestionPopup = document.getElementById('password-suggestion-popup');
        const signupButton = document.getElementById('signupButton');

        function generateRandomPassword() {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+';
            let password = '';
            for (let i = 0; i < 12; i++) {
                password += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            return password;
        }

        passwordInput.addEventListener('input', function() {
    const password = passwordInput.value;
    const passwordStrength = checkPasswordStrength(password);

    if (passwordStrength === 'weak') {
        if (password.length === 0) {
            passwordStrengthError.textContent = ''; 
            passwordStrengthSuggestion.textContent = ''; 
            passwordSuggestionPopup.style.display = 'none'; 
        } else {
            passwordStrengthError.textContent = 'Password is weak';
            passwordStrengthError.style.color = 'red';
            passwordSuggestionPopup.style.display = 'block';

            const suggestedPassword = generateRandomPassword();
            passwordSuggestionPopup.innerHTML = `
                Suggested password: <br><strong>${suggestedPassword}</strong><br>
                <button id="applySuggestedPassword" style="margin-top: 5px; cursor: pointer;">Apply</button>
            `;

            const applyButton = document.getElementById('applySuggestedPassword');
            applyButton.addEventListener('click', function() {
                passwordInput.value = suggestedPassword;
                passwordStrengthError.textContent = '';
                passwordSuggestionPopup.style.display = 'none';
                passwordStrengthSuggestion.textContent = 'Password is strong!';
                passwordStrengthSuggestion.style.color = 'green';
                signupButton.disabled = false; 
            });

            signupButton.disabled = true; 
        }
    } else if (passwordStrength === 'strong') {
        passwordStrengthError.textContent = '';
        passwordSuggestionPopup.style.display = 'none';
        passwordStrengthSuggestion.textContent = 'Password is strong!';
        passwordStrengthSuggestion.style.color = 'green';
        signupButton.disabled = false; 
    } else {
        passwordStrengthError.textContent = '';
        passwordSuggestionPopup.style.display = 'none';
        passwordStrengthSuggestion.textContent = '';
        signupButton.disabled = true;
    }
});
        function checkPasswordStrength(password) {
    if (password.length === 0) return 'weak'; 
    if (password.length < 6) return 'weak'; 
    if (!/[a-zA-Z]/.test(password)) return 'weak'; 
    if (!/\d/.test(password)) return 'weak';

    if (!/[A-Z]/.test(password) && !/[!@#$%^&*]/.test(password)) return 'weak'; 
    return 'strong';
}

        const signUpForm = document.querySelector('#signupForm');
        const otpVerificationSection = document.getElementById('otpVerificationSection');
        const verifyOtpButton = document.getElementById('verifyOtpButton');
        let generatedOtp = null;

        signUpForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const email = document.getElementById('email-signup').value;
            const password = document.getElementById('password-signup').value;

            $.ajax({
                url: 'check_email.php',
                type: 'POST',
                data: { email: email },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        document.getElementById('email-exists-error').style.display = 'block';
                        document.getElementById('email-error-signup').textContent = '';
                    } else {
                        document.getElementById('email-exists-error').style.display = 'none';
                        generatedOtp = Math.floor(100000 + Math.random() * 900000);

                        $.ajax({
                            url: 'send_otp.php',
                            type: 'POST',
                            data: { toEmail: email, otp: generatedOtp },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    otpVerificationSection.style.display = 'block';
                                    document.getElementById('signUpSection').style.display = 'none';
                                } else {
                                    alert('Error sending OTP: ' + response.error);
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('Error sending OTP. Please try again later.');
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error checking email. Please try again later.');
                }
            });
        });

        verifyOtpButton.addEventListener('click', function () {
            const otpInput = document.getElementById('otp').value;
            const otpError = document.getElementById('otp-error');

            if (otpInput == generatedOtp) {
                otpError.textContent = '';
                signUpUser();
            } else {
                otpError.textContent = 'Invalid OTP. Please try again.';
            }
        });

        function signUpUser() {
            const email = document.getElementById('email-signup').value;
            const password = document.getElementById('password-signup').value;

            $.ajax({
                url: 'register.php',
                type: 'POST',
                data: { email: email, password: password },
                success: function(response) {
                    window.location.assign('index.php');
                },
                error: function(xhr, status, error) {
                    alert('Error registering user: ' + error);
                }
            });
        }

        document.addEventListener('click', function(event) {
            if (!passwordSuggestionPopup.contains(event.target) && event.target !== passwordInput) {
                passwordSuggestionPopup.style.display = 'none';
            }
        });
    });
</script>


        <a href="https://accounts.google.com/o/oauth2/auth?client_id=295250178189-lgp6p1s9hnahqrtpck4opc1k0tusb6fa.apps.googleusercontent.com&redirect_uri=https://localhost/website/callback.php&response_type=code&scope=email%20profile&access_type=online">
    <button type="button" class="googleBtn">
        <i class="fa-brands fa-google google-icon"></i>   &nbsp;&nbsp; 
        Continue with Google
    </button>
</a>
<br>
     
       
    </form>
</div>

        <div class="a" id="signInSection" style="display:none;">
    <h1>log In</h1>
    <div class="input-container">
        <form action="signin.php" method="post" id="signInForm">
            <input type="email" id="email-signin" name="email-signin" placeholder="Email" required><br><br>
            <span id="email-error-signinn" style="color: red;"></span>
            <div class="input-wrapper">
        <input type="password" id="password" placeholder="Enter your password">
        <i id="togglePassword" class="fa-regular fa-eye"></i>
    </div>
            <span id="password-error-signin" style="color: red;"></span><br> 
            <input type="checkbox"> Remember me &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <button type="button" class="asdf" onclick="location.href='forgotpassword.php'">Forgot password</button>
            <br>  <button type="submit" class="Btn" name="signIn">log In</button>
            <p>Don't Have an Account?</p>
            <button type="button" class="switchSectionButton">Register</button>
 
            <p>___Or login with___</p>

<a href="https://accounts.google.com/o/oauth2/auth?client_id=295250178189-lgp6p1s9hnahqrtpck4opc1k0tusb6fa.apps.googleusercontent.com&redirect_uri=https://localhost/website/callback.php&response_type=code&scope=email%20profile&access_type=online">
    <button type="button" class="googleBtn">
 <i class="fa-brands fa-google"></i>   &nbsp;&nbsp; 
        Continue with Google
    </button>

</a>
           
        </form>
    </div>
</div>

        <div class="a" id="otpVerificationSection" style="display:none;">
            <h1>Verify OTP</h1>
            <p>A verification code has been sent to your email. Please enter it below:</p>
            <input type="text" id="otp" placeholder="Enter OTP" required><br>
            <span id="otp-error" style="color: red;"></span><br>
            <button id="verifyOtpButton">Verify OTP</button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    validateEmail('email-signup', 'email-error-signup');
    validateEmail('email-signin', 'email-error-signin');

    document.querySelectorAll('.nn, .switchSectionButton').forEach(button => {
        button.addEventListener('click', toggleForms);
    });

    const signUpForm = document.querySelector('#signupForm');
    const otpVerificationSection = document.getElementById('otpVerificationSection');
    const verifyOtpButton = document.getElementById('verifyOtpButton');
    let generatedOtp = null;

    signUpForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const email = document.getElementById('email-signup').value;
        const password = document.getElementById('password-signup').value;

        $.ajax({
            url: 'check_email.php',
            type: 'POST',
            data: { email: email },
            dataType: 'json',
            success: function(response) {
                if (response.exists) {
                    document.getElementById('email-exists-error').style.display = 'block'; 
                    document.getElementById('email-error-signup').textContent = ''; 
     
                } else {
                    document.getElementById('email-exists-error').style.display = 'none';
                    generatedOtp = Math.floor(100000 + Math.random() * 900000);

                    $.ajax({
                        url: 'send_otp.php',
                        type: 'POST',
                        data: { toEmail: email, otp: generatedOtp },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                otpVerificationSection.style.display = 'block';
                                document.getElementById('signUpSection').style.display = 'none';
                            } else {
                                alert('Error sending OTP: ' + response.error);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Error sending OTP. Please try again later.');
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                alert('Error checking email. Please try again later.');
            }
        });
    });

    verifyOtpButton.addEventListener('click', function () {
        const otpInput = document.getElementById('otp').value;
        const otpError = document.getElementById('otp-error');

        if (otpInput == generatedOtp) {
            otpError.textContent = '';
        
            signUpUser();
        } else {
            otpError.textContent = 'Invalid OTP. Please try again.';
        }
    });

    function signUpUser() {
        const email = document.getElementById('email-signup').value;
        const password = document.getElementById('password-signup').value;

        $.ajax({
            url: 'register.php',
            type: 'POST',
            data: { email: email, password: password },
            success: function(response) {
                window.location.assign('index.php');
            },
            error: function(xhr, status, error) {
                alert('Error registering user: ' + error);
            }
        });
    }

    function validateEmail(inputId, errorId) {
        const emailInput = document.getElementById(inputId);
        const errorMessage = document.getElementById(errorId);
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        emailInput.addEventListener('input', () => {
            const email = emailInput.value;
            if (emailPattern.test(email)) {
                errorMessage.textContent = 'Email format is valid';
                errorMessage.style.color = 'green';
            } else if (email !== "") {
                errorMessage.textContent = 'Invalid email format';
                errorMessage.style.color = 'red';
            } else {
                errorMessage.textContent = '';
            }
        });
    }

    function toggleForms() {
        const registerMessage = document.getElementById('registerMessage');
        const loginMessage = document.getElementById('loginMessage');
        registerMessage.style.display = registerMessage.style.display === 'none' ? 'block' : 'none';
        loginMessage.style.display = loginMessage.style.display === 'none' ? 'block' : 'none';

        const signUpSection = document.getElementById('signUpSection');
        const signInSection = document.getElementById('signInSection');
        signUpSection.style.display = signUpSection.style.display === 'none' ? 'block' : 'none';
        signInSection.style.display = signInSection.style.display === 'none' ? 'block' : 'none';
    }
});

  document.querySelectorAll('.Btn, .nn').forEach(button => {
    button.addEventListener('click', function() {
      this.classList.add('clicked');
      
      setTimeout(() => {
        this.classList.remove('clicked');
      }, 900); 
    });
  });
const signInForm = document.querySelector('#signInForm');

signInForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const email = document.getElementById('email-signin').value;
    const password = document.getElementById('password').value;
    const emailError = document.getElementById('email-error-signinn');
    const passwordError = document.getElementById('password-error-signin');

    emailError.textContent = '';
    passwordError.textContent = '';

    $.ajax({
    url: 'signin.php',
    type: 'POST',
    data: { email: email, password: password },
    dataType: 'json',
    success: function(response) {
        console.log('AJAX Success:', response); 
        if (response.success) {
            window.location.assign('index.php');
        } else {
            if (response.error === 'unregistered_email') {
                emailError.textContent = 'Email is not registered.';
            } else if (response.error === 'wrong_password') {
                passwordError.textContent = 'Incorrect password. Please try again.';
            }
        }
    },
    error: function(xhr, status, error) {
        alert('Error during sign in. Please try again later.');
        console.error('AJAX Error:', status, error);
    }
});

});
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
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
document.getElementById('toggleSignupPassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password-signup');
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
</script>
</body>
</html>
