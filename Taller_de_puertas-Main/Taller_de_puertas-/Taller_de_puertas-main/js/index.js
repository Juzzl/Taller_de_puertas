document.addEventListener("DOMContentLoaded", async () => {
  const productContainer = document.getElementById("product-list");

  async function cargarProductos() {
    try {
      const response = await fetch("http://localhost:8000/backend/index.php");
      if (response.ok) {
        const productos = await response.json();
        mostrarProductos(productos);
      } else {
        throw new Error("Error al cargar los productos");
      }
    } catch (error) {
      console.error(error);
      alert("Error al cargar los productos");
    }
  }

  // Mostrar productos en la página
  function mostrarProductos(productos) {
    productContainer.innerHTML = "";
    productos.forEach((producto) => {
      const productCard = document.createElement("div");
      productCard.className = "col-md-4 mb-3";
      productCard.innerHTML = `
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${producto.nombre}</h5>
                        <p class="card-text">${producto.descripcion}</p>
                        <p class="mt-auto"><strong>Precio:</strong> $${producto.precio}</p>
                        <button class="btn btn-primary agregar-carrito mt-2" 
                                data-id="${producto.id}" 
                                data-nombre="${producto.nombre}" 
                                data-precio="${producto.precio}">
                            Agregar al Carrito
                        </button>
                    </div>
                </div>
            `;
      productContainer.appendChild(productCard);
    });

    document.querySelectorAll(".agregar-carrito").forEach((boton) => {
      boton.addEventListener("click", agregarAlCarrito);
    });
  }

  // Agregar producto al carrito
  async function agregarAlCarrito(event) {
    const boton = event.target;
    const id = boton.getAttribute("data-id");
    const nombre = boton.getAttribute("data-nombre");
    const precio = parseFloat(boton.getAttribute("data-precio"));

    try {
      const response = await fetch(
        "http://localhost:8000/backend/carrito.php",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id, nombre, precio, cantidad: 1 }),
        }
      );

      if (response.ok) {
        const result = await response.json();
        alert(result.message);
      } else {
        throw new Error("Error al agregar el producto al carrito");
      }
    } catch (error) {
      console.error(error);
      alert("Error al agregar el producto al carrito");
    }
  }

  cargarProductos();
});
