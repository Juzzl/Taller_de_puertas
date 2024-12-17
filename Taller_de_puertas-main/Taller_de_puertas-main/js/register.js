document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.getElementById('register-form');
    const registerError = document.getElementById('register-error');

    registerForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const nombre = document.getElementById('nombre').value;
        const apellido1 = document.getElementById('apellido1').value;
        const apellido2 = document.getElementById('apellido2').value || '';
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        if (password !== confirmPassword) {
            registerError.innerHTML = `<div class="alert alert-danger fade show" role="alert">
                <strong>Error:</strong> Las contraseñas no coinciden.
            </div>`;
            return;
        }

        const formData = new URLSearchParams();
        formData.append('nombre', nombre);
        formData.append('apellido1', apellido1);
        formData.append('apellido2', apellido2);
        formData.append('password', password);
        formData.append('email', email);

        try {
            const response = await fetch('backend/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData.toString()
            });

            const result = await response.json();

            if (response.ok) {
                registerError.innerHTML = `<div class="alert alert-success fade show" role="alert">
                    <strong>Éxito:</strong> Usuario registrado correctamente. Redirigiendo...
                </div>`;

                setTimeout(function () {
                    window.location.href = "login.html";
                }, 3000);
            } else {
                registerError.innerHTML = `<div class="alert alert-danger fade show" role="alert">
                    <strong>Error:</strong> ${result.error || "No se pudo registrar el usuario"}
                </div>`;
            }
        } catch (error) {
            console.error('Error:', error);
            registerError.innerHTML = `<div class="alert alert-danger fade show" role="alert">
                <strong>Error:</strong> Ocurrió un problema al conectar con el servidor.
            </div>`;
        }
    });
});

