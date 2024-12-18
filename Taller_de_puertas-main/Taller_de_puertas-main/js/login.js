document
  .getElementById("login-form")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const messageDiv = document.getElementById("message");
    messageDiv.classList.add("d-none");

    try {
      const response = await fetch("http://localhost:8000/backend/login.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ email, password }),
      });

      if (response.ok) {
        const data = await response.json();
        // Mostrar mensaje de éxito
        messageDiv.className = "alert alert-success";
        messageDiv.textContent = "Inicio de sesión exitoso. Redirigiendo...";
        messageDiv.classList.remove("d-none");
        //Metodo para que haya un pequeño delay entre el logueo y la entrada al index
        setTimeout(() => {
          window.location.href = "index.html";
        }, 1500);
      } else {
        // Mostrar mensaje de error
        const errorData = await response.json();
        messageDiv.className = "alert alert-danger";
        messageDiv.textContent = errorData.error || "Error al iniciar sesión";
        messageDiv.classList.remove("d-none");
      }
    } catch (error) {
      console.error("Error:", error);
      messageDiv.className = "alert alert-danger";
      messageDiv.textContent = "Error al conectar con el servidor";
      messageDiv.classList.remove("d-none");
    }
  });
