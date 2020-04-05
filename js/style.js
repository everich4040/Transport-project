//Selector functions

function $(selector) {
    return document.querySelector(selector);
}

function $All(selector) {
    return document.querySelectorAll(selector);
}
//Validation the Show / Hide Password

function togglePassword() {
    const passwordInputs = $All("input[type=password]");

    passwordInputs.forEach(passwordInput => {
        let eyeIcon = passwordInput.nextElementSibling;
        eyeIcon.onclick = () => {
            if (eyeIcon.classList.contains("fa-eye")) {
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
                passwordInput.removeAttribute("type");
            } else if (eyeIcon.classList.contains("fa-eye-slash")) {
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
                passwordInput.setAttribute("type", "password");
            }
        }
    })

}
togglePassword();

//hide icons on input focus

const inputs = $All("input");
inputs.forEach(input => {
    input.addEventListener('input', () => {
        const inputPreviousSibling = input.previousElementSibling;
        input.value == "" ? inputPreviousSibling.style.display = "block" : inputPreviousSibling.style.display = "none";
    });
})