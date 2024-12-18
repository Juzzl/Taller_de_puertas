document.addEventListener("DOMContentLoaded", () => {
  console.log("Página cargada: inventario.html");

  const inventarioContainer = document.getElementById("inventario-container");
  const agregarBtn = document.getElementById("agregar-producto");
  const eliminarBtns = document.querySelectorAll(".btn-eliminar");

  // Cargar inventario
  fetch("/backend/inventario.php")
    .then((response) => response.json())
    .then((data) => {
      inventarioContainer.innerHTML = "";
      data.forEach((producto) => {
        const productoElement = document.createElement("div");
        productoElement.className = "producto-item";
        productoElement.innerHTML = `
                    <h3>${producto.nombre}</h3>
                    <p>Precio: $${producto.precio_unitario}</p>
                    <p>Descripción: ${producto.descripcion}</p>
                    <p>Cantidad: ${producto.cantidad_inventario}</p>
                    <button data-id="${producto.id_producto}" class="btn-eliminar">Eliminar</button>
                `;
        inventarioContainer.appendChild(productoElement);
      });
    })
    .catch((error) => console.error("Error al cargar inventario:", error));

  // Agregar producto
  agregarBtn.addEventListener("click", () => {
    const nuevoProducto = {
      nombre: document.getElementById("nombre").value,
      precio_unitario: document.getElementById("precio").value,
      descripcion: document.getElementById("descripcion").value,
      cantidad_inventario: document.getElementById("cantidad").value,
      id_categoria: document.getElementById("categoria").value,
      id_tipo_producto: document.getElementById("tipo").value,
    };

    fetch("/backend/inventario.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(nuevoProducto),
    })
      .then((response) => response.json())
      .then((data) => {
        alert("Producto agregado correctamente");
        location.reload();
      })
      .catch((error) => console.error("Error al agregar producto:", error));
  });

  // Eliminar producto
  inventarioContainer.addEventListener("click", (event) => {
    if (event.target.classList.contains("btn-eliminar")) {
      const idProducto = event.target.dataset.id;

      fetch(`/backend/inventario.php?id_producto=${idProducto}`, {
        method: "DELETE",
      })
        .then((response) => response.json())
        .then((data) => {
          alert("Producto eliminado correctamente");
          location.reload();
        })
        .catch((error) => console.error("Error al eliminar producto:", error));
    }
  });
});
