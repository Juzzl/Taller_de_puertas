document.addEventListener("DOMContentLoaded", async () => {
  const productContainer = document.getElementById("product-list");
  const filterCategoria = document.getElementById("filter-categoria");
  const filterTipo = document.getElementById("filter-tipo");
  const filterBtn = document.getElementById("filter-btn");
 
  async function cargarFiltros() {
    try {
      const response = await fetch("http://localhost:8000/backend/catalogo.php?action=filtros");
      if (response.ok) {
        const { categorias, tipos } = await response.json();
        categorias.forEach((categoria) => {
          const option = document.createElement("option");
          option.value = categoria.id;
          option.textContent = categoria.nombre;
          filterCategoria.appendChild(option);
        });
        tipos.forEach((tipo) => {
          const option = document.createElement("option");
          option.value = tipo.id;
          option.textContent = tipo.nombre;
          filterTipo.appendChild(option);
        });
      } else {
        throw new Error("Error al cargar los filtros");
      }
    } catch (error) {
      console.error(error);
      alert("Error al cargar los filtros");
    }
  }
 
  async function cargarProductos(categoria = "", tipo = "") {
    try {
      const params = new URLSearchParams({ categoria, tipo });
      const response = await fetch(`http://localhost:8000/backend/catalogo.php?${params}`);
      if (response.ok) {
        const productos = await response.json();
        mostrarProductos(productos);
      } else {
        throw new Error("Error al cargar el catálogo");
      }
    } catch (error) {
      console.error(error);
      alert("Error al cargar los productos");
    }
  }
 
  function mostrarProductos(productos) {
    productContainer.innerHTML = "";
    if (productos.length === 0) {
      document.getElementById("message").classList.remove("d-none");
      return;
    }
    document.getElementById("message").classList.add("d-none");
 
    productos.forEach((producto) => {
      const productCard = document.createElement("div");
      productCard.className = "col-md-4 mb-3";
      productCard.innerHTML = `
        <div class="card">
            <img src="${producto.imagen}" class="card-img-top" alt="${producto.nombre}">
            <div class="card-body">
                <h5 class="card-title">${producto.nombre}</h5>
                <p class="card-text">${producto.descripcion}</p>
                <p><strong>Precio:</strong> $${producto.precio}</p>
                <button class="btn btn-primary agregar-carrito"
                        data-id="${producto.id}"
                        data-nombre="${producto.nombre}"
                        data-precio="${producto.precio}">
                    Agregar al Carrito
                </button>
            </div>
        </div>`;
      productContainer.appendChild(productCard);
    });
 
    // Asociar el evento 'click' a los botones de agregar al carrito
    document.querySelectorAll(".agregar-carrito").forEach((boton) => {
      boton.addEventListener("click", agregarAlCarrito);
    });
  }
 
  async function agregarAlCarrito(event) {
    const boton = event.target;
    const id = boton.getAttribute("data-id");
    const nombre = boton.getAttribute("data-nombre");
    const precio = parseFloat(boton.getAttribute("data-precio"));
 
    try {
      const response = await fetch("http://localhost:8000/backend/carrito.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id, nombre, precio, cantidad: 1 }),
      });
 
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
 
  filterBtn.addEventListener("click", () => {
    const categoria = filterCategoria.value;
    const tipo = filterTipo.value;
    cargarProductos(categoria, tipo);
  });
 
  cargarFiltros();
  cargarProductos();
});