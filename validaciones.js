function validarLogin() {
    const usuario = document.getElementById('usuario').value;
    const contra = document.getElementById('contra').value;
    if (!usuario || !contra) {
        alert("Por favor, completa todos los campos.");
        return false;
    }
    return true;
}

function validarRegistro() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    let errorMessage = '';

    if (password !== confirmPassword) {
        errorMessage += 'Las contraseñas no coinciden.\n';
    }
    if (password.length < 8) {
        errorMessage += 'La contraseña debe tener al menos 8 caracteres.\n';
    }
    if (!/[A-Z]/.test(password)) {
        errorMessage += 'La contraseña debe contener al menos una letra mayúscula.\n';
    }
    if (!/[a-z]/.test(password)) {
        errorMessage += 'La contraseña debe contener al menos una letra minúscula.\n';
    }
    if (!/\d/.test(password)) {
        errorMessage += 'La contraseña debe contener al menos un número.\n';
    }
    if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        errorMessage += 'La contraseña debe contener al menos un carácter especial.\n';
    }
    if (errorMessage) {
        document.getElementById('error-message').innerText = errorMessage;
        return false;
    }
    return true;
}
