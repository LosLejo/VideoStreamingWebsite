const togglePassword = document.getElementById("togglePassword");
const togglePasswordConfirm = document.getElementById("togglePasswordConfirm");
const passwordInput = document.getElementById("password");
const confirmInput = document.getElementById("passwordConfirm");

togglePassword.addEventListener("click", () => {
    const type = passwordInput.type === "password" ? "text" : "password";
    passwordInput.type = type;
    togglePassword.innerHTML = type === "password"
        ? '<i class="far fa-eye-slash"></i>'
        : '<i class="far fa-eye"></i>';
});

togglePasswordConfirm.addEventListener("click", () => {
    const type = confirmInput.type === "password" ? "text" : "password";
    confirmInput.type = type;
    togglePasswordConfirm.innerHTML = type === "password"
        ? '<i class="far fa-eye-slash"></i>'
        : '<i class="far fa-eye"></i>';
});