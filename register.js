function filterOptions() {
    const input = document.getElementById("counter-search").value.toLowerCase();
    const select = document.getElementById("select-state");
    const options = select.getElementsByTagName("option");

    for (let i = 0; i < options.length; i++) {
        const optionText = options[i].text.toLowerCase();
        if (optionText.includes(input)) {
            options[i].style.display = "";
        } else {
            options[i].style.display = "none";
        }
    }
}

function togglePassword() {
    var passwordField = document.getElementById("password");
    var toggleBtn = passwordField.nextElementSibling;

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleBtn.textContent = "👀"; 
    } else {
        passwordField.type = "password";
        toggleBtn.textContent = "🙈"; 
    }
}

function toggleConfirmPassword() {
    var confirmPasswordField = document.getElementById("confirm-password");
    var toggleBtn = confirmPasswordField.nextElementSibling;

    if (confirmPasswordField.type === "password") {
        confirmPasswordField.type = "text";
        toggleBtn.textContent = "👀"; 
    } else {
        confirmPasswordField.type = "password";
        toggleBtn.textContent = "🙈"; 
    }
}
document.querySelector('form').addEventListener('submit', function (e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match. Please try again.');
    }
});
