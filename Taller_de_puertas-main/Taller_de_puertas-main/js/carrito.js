document.addEventListener("DOMContentLoaded", async () => {
  const carritoContainer = document.getElementById("carrito-container");
  const realizarCompraBtn = document.getElementById("realizar-compra");

  // Cargar Carrito
  async function cargarCarrito() {
    try {
      const response = await fetch("http://localhost:8000/backend/carrito.php");
      if (response.ok) {
        const carrito = await response.json();
        mostrarCarrito(carrito);
      } else {
        throw new Error("Error al cargar el carrito");
      }
    } catch (error) {
      console.error(error);
      alert("Error al cargar el carrito");
    }
  }

  // Mostrar Carrito
  function mostrarCarrito(carrito) {
    carritoContainer.innerHTML = "";
    if (Object.keys(carrito).length === 0) {
      carritoContainer.innerHTML =
        '<p class="text-center">El carrito está vacío</p>';
      return;
    }

    for (const id in carrito) {
      const producto = carrito[id];
      const cartItem = document.createElement("div");
      cartItem.className = "cart-item mb-3";
      cartItem.innerHTML = `
              <div class="card">
                  <div class="card-body d-flex justify-content-between align-items-center">
                      <div>
                          <h5>${producto.nombre}</h5>
                          <p>Precio: $${producto.precio}</p>
                          <p>Cantidad: ${producto.cantidad}</p>
                      </div>
                      <button class="btn btn-danger eliminar-carrito" data-id="${id}">Eliminar</button>
                  </div>
              </div>
          `;
      carritoContainer.appendChild(cartItem);
    }

    document.querySelectorAll(".eliminar-carrito").forEach((boton) => {
      boton.addEventListener("click", eliminarDelCarrito);
    });
  }

  // Eliminar Producto del Carrito
  async function eliminarDelCarrito(event) {
    const id = event.target.getAttribute("data-id");

    try {
      const response = await fetch(
        "http://localhost:8000/backend/carrito.php",
        {
          method: "DELETE",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id }),
        }
      );

      if (response.ok) {
        const result = await response.json();
        alert(result.message);
        cargarCarrito();
      } else {
        throw new Error("Error al eliminar el producto del carrito");
      }
    } catch (error) {
      console.error(error);
      alert("Error al eliminar el producto del carrito");
    }
  }

  // Realizar Compra
  async function realizarCompra() {
    try {
      const response = await fetch(
        "http://localhost:8000/backend/carrito.php",
        {
          method: "PUT",
        }
      );

      if (response.ok) {
        const result = await response.json();
        alert(result.message);
        cargarCarrito();
      } else {
        throw new Error("Error al realizar la compra");
      }
    } catch (error) {
      console.error(error);
      alert("Error al realizar la compra");
    }
  }

  realizarCompraBtn.addEventListener("click", realizarCompra);
  cargarCarrito();
});
