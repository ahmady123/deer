
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


document.addEventListener("DOMContentLoaded", function() {

    validateEmail('email-signup', 'email-error-signup');
    validateEmail('email-signin', 'email-error-signin');

    document.querySelectorAll('.nn, .switchSectionButton').forEach(button => {
        button.addEventListener('click', toggleForms);
    });
});
