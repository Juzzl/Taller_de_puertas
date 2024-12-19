document.addEventListener("DOMContentLoaded", () => {
  const generarFacturaBtn = document.getElementById("generar-factura");
  const facturaContainer = document.getElementById("factura-container");

  // Generar Factura
  async function generarFactura() {
    try {
      const response = await fetch("http://localhost:8000/backend/facturacion.php", {
        method: "POST",
      });

      if (response.ok) {
        const result = await response.json();
        mostrarFactura(result.factura);
      } else {
        const error = await response.json();
        alert(error.error || "Error al generar la factura");
      }
    } catch (error) {
      console.error(error);
      alert("Error al generar la factura");
    }
  }

  // Mostrar Factura
  function mostrarFactura(factura) {
    facturaContainer.innerHTML = `
      <div class="card">
        <div class="card-body">
          <h4>Factura ID: ${factura.id_factura}</h4>
          <p>Fecha: ${factura.fecha}</p>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              ${factura.detalles
                .map(
                  (detalle) => `
                  <tr>
                    <td>${detalle.nombre}</td>
                    <td>${detalle.cantidad}</td>
                    <td>$${detalle.precio}</td>
                    <td>$${detalle.subtotal}</td>
                  </tr>`
                )
                .join("")}
            </tbody>
          </table>
          <h5 class="text-right">Total: $${factura.total}</h5>
        </div>
      </div>
    `;
  }

  generarFacturaBtn.addEventListener("click", generarFactura);
});