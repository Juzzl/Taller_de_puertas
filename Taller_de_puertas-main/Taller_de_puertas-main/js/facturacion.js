document
  .getElementById("generate-invoice")
  .addEventListener("click", async function () {
    try {
      const carritoId = document.getElementById("carrito-id").value;

      if (!carritoId) {
        alert("Por favor, ingrese el ID del carrito.");
        return;
      }

      const response = await fetch("http://localhost/backend/facturacion.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ carrito_id: carritoId }),
      });

      if (response.ok) {
        const result = await response.text();
        alert(`Factura generada con éxito: ${result}`);
      } else {
        alert("Error al generar la factura");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("Error al generar la factura");
    }
  });
