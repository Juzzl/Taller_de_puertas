document
  .getElementById("registro-form")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const nombre = document.getElementById("nombre").value;
    const apellido_paterno = document.getElementById("apellido_paterno").value;
    const apellido_materno =
      document.getElementById("apellido_materno").value || null;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    // Área para mostrar mensajes
    const messageDiv = document.getElementById("message");
    messageDiv.classList.add("d-none");

    try {
      const response = await fetch(
        "http://localhost:8000/backend/register.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            nombre,
            apellido_paterno,
            apellido_materno,
            email,
            password,
          }),
        }
      );

      if (response.ok) {
        const data = await response.json();
        //se mostrara un mensaje de usuario registrado con exito
        messageDiv.className = "alert alert-success";
        messageDiv.textContent =
          "Registro exitoso. Redirigiendo al inicio de sesión...";
        messageDiv.classList.remove("d-none");
        //Metodo para que haya un pequeño delay entre el registro y el redirijamiento al login
        setTimeout(() => {
          window.location.href = "login.html";
        }, 2000);
      } else {
        const errorData = await response.json();
        messageDiv.className = "alert alert-danger";
        messageDiv.textContent =
          errorData.error || "Error al registrar el usuario";
        messageDiv.classList.remove("d-none");
      }
    } catch (error) {
      console.error("Error:", error);
      messageDiv.className = "alert alert-danger";
      messageDiv.textContent = "Error al conectar con el servidor";
      messageDiv.classList.remove("d-none");
    }
  });
