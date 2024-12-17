document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('loginForm');
    const loginError = document.getElementById('login-error');

    form.addEventListener('submit', async function(e){
        e.preventDefault();

        // Obtener valores del formulario
        const email = document.getElementById('email').value;
        const password = document.getElementById('contraseña').value;
      
        try {
            // Enviar la solicitud al servidor (login.php)
            const response = await fetch('backend/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ email: email, contraseña: password }) // 'contraseña' coincide con el nombre en PHP
            });

            // Manejar la respuesta del servidor
            const result = await response.json();

            if(response.ok){
                // Login exitoso: Redirigir al usuario al dashboard
                window.location.href = "dashboard.html";
            } else {
                // Mostrar error en pantalla
                loginError.style.display = 'block';
                loginError.textContent = result.error || 'Email o contraseña incorrectos';
            }
        } catch(error) {
            // Manejo de errores inesperados
            loginError.style.display = 'block';
            loginError.textContent = 'Hubo un error al procesar la solicitud';
        }
    });
});
