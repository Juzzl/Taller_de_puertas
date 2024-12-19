document.addEventListener("DOMContentLoaded", () => {
  const modalAgregar = document.getElementById("modal-agregar-producto");
  const modalEditar = document.getElementById("modal-editar-producto");
  const formAgregar = document.getElementById("form-agregar-producto");
  const formEditar = document.getElementById("form-editar-producto");
  const inventarioContainer = document.querySelector("#inventario-tabla tbody");

  const btnCerrarModalAgregar = document.getElementById("cerrar-modal");
  const btnCerrarModalEditar = document.getElementById("cerrar-modal-editar");
  const btnAbrirModalAgregar = document.getElementById("btn-agregar-producto");

  // Abrir modal de agregar
  btnAbrirModalAgregar.addEventListener("click", () => {
    modalAgregar.style.display = "block";
  });

  // Cerrar modal de agregar
  btnCerrarModalAgregar.addEventListener("click", () => {
    modalAgregar.style.display = "none";
  });

  // Cerrar modal de editar
  btnCerrarModalEditar.addEventListener("click", () => {
    modalEditar.style.display = "none";
  });

  // Cargar productos en la tabla
  async function cargarInventario() {
    try {
      const response = await fetch("http://localhost:8000/backend/inventario.php");
      const productos = await response.json();
      mostrarInventario(productos);
    } catch (error) {
      console.error("Error al cargar el inventario:", error);
    }
  }

  function mostrarInventario(productos) {
    inventarioContainer.innerHTML = "";
    productos.forEach((producto) => {
      const row = `
        <tr>
          <td>${producto.id}</td>
          <td>${producto.nombre}</td>
          <td>${producto.descripcion}</td>
          <td>${producto.precio}</td>
          <td>${producto.cantidad}</td>
          <td>${producto.categoria}</td>
          <td>${producto.tipo}</td>
          <td>
            <button class="btn-editar" data-id="${producto.id}">Editar</button>
            <button class="btn-eliminar" data-id="${producto.id}">Eliminar</button>
          </td>
        </tr>`;
      inventarioContainer.insertAdjacentHTML("beforeend", row);
    });

    document.querySelectorAll(".btn-editar").forEach((btn) =>
      btn.addEventListener("click", abrirModalEditar)
    );

    document.querySelectorAll(".btn-eliminar").forEach((btn) =>
      btn.addEventListener("click", eliminarProducto)
    );
  }

  async function abrirModalEditar(event) {
    const id = event.target.getAttribute("data-id");
    try {
      const response = await fetch(`http://localhost:8000/backend/inventario.php?id=${id}`);
      const producto = await response.json();

      document.getElementById("editar-id").value = producto.id;
      document.getElementById("editar-nombre").value = producto.nombre;
      document.getElementById("editar-precio").value = producto.precio;
      document.getElementById("editar-cantidad").value = producto.cantidad;
      document.getElementById("editar-descripcion").value = producto.descripcion;
      document.getElementById("editar-categoria").value = producto.categoria_id;
      document.getElementById("editar-tipo_producto").value = producto.tipo_id;

      modalEditar.style.display = "block";
    } catch (error) {
      console.error("Error al cargar el producto:", error);
    }
  }

  formEditar.addEventListener("submit", async (e) => {
    e.preventDefault();
  
    const formData = new FormData(formEditar); 
  
    try {
      const response = await fetch("http://localhost:8000/backend/inventario.php", {
        method: "POST", 
        body: formData, 
      });
  
      if (response.ok) {
        const result = await response.json();
        alert(result.message || "Producto actualizado exitosamente");
        modalEditar.style.display = "none";
        cargarInventario();
      } else {
        const error = await response.json();
        console.error("Error al actualizar producto:", error);
        alert(error.error || "Error al actualizar el producto.");
      }
    } catch (error) {
      console.error("Error al actualizar producto:", error);
      alert("Ocurrió un error inesperado al actualizar el producto.");
    }
  });
  
  // Agregar un nuevo producto
  formAgregar.addEventListener("submit", async (e) => {
    e.preventDefault();
  
    const formData = new FormData(formAgregar); 
  
    try {
      const response = await fetch("http://localhost:8000/backend/upload_producto.php", {
        method: "POST",
        body: formData, 
      });
  
      if (response.ok) {
        const result = await response.json();
        alert(result.message);
        modalAgregar.style.display = "none";
        formAgregar.reset();
        cargarInventario();
      } else {
        const error = await response.json();
        console.error("Error al agregar producto:", error);
        alert(error.error || "Error al agregar el producto.");
      }
    } catch (error) {
      console.error("Error al agregar producto:", error);
      alert("Ocurrió un error inesperado al agregar el producto.");
    }
  });
  
  

  // Eliminar un producto
  async function eliminarProducto(event) {
    const id = event.target.getAttribute("data-id");
    if (confirm("¿Estás seguro de eliminar este producto?")) {
      try {
        const response = await fetch("http://localhost:8000/backend/inventario.php", {
          method: "DELETE",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id }),
        });

        if (response.ok) {
          alert("Producto eliminado exitosamente");
          cargarInventario();
        }
      } catch (error) {
        console.error("Error al eliminar producto:", error);
      }
    }
  }

  // Cargar opciones de categorías y tipos
  async function cargarOpciones() {
    const categoriaSelect = document.getElementById("categoria");
    const tipoSelect = document.getElementById("tipo_producto");
    const editarCategoriaSelect = document.getElementById("editar-categoria");
    const editarTipoSelect = document.getElementById("editar-tipo_producto");

    try {
      const [categorias, tipos] = await Promise.all([
        fetch("http://localhost:8000/backend/inventario.php?categorias=1").then((res) => res.json()),
        fetch("http://localhost:8000/backend/inventario.php?tipos=1").then((res) => res.json()),
      ]);

      // Limpiar selectores antes de rellenar
      categoriaSelect.innerHTML = '<option value="" disabled selected>Seleccione una categoría</option>';
      tipoSelect.innerHTML = '<option value="" disabled selected>Seleccione un tipo</option>';
      editarCategoriaSelect.innerHTML = '<option value="" disabled selected>Seleccione una categoría</option>';
      editarTipoSelect.innerHTML = '<option value="" disabled selected>Seleccione un tipo</option>';

      // Rellenar categorías
      categorias.forEach((categoria) => {
        const option = `<option value="${categoria.id}">${categoria.nombre}</option>`;
        categoriaSelect.insertAdjacentHTML("beforeend", option);
        editarCategoriaSelect.insertAdjacentHTML("beforeend", option);
      });

      // Rellenar tipos
      tipos.forEach((tipo) => {
        const option = `<option value="${tipo.id}">${tipo.nombre}</option>`;
        tipoSelect.insertAdjacentHTML("beforeend", option);
        editarTipoSelect.insertAdjacentHTML("beforeend", option);
      });
    } catch (error) {
      console.error("Error al cargar categorías y tipos:", error);
      alert("No se pudieron cargar las opciones de categorías y tipos.");
    }
  }

  // Inicializar la carga de datos
  cargarOpciones();
  cargarInventario();
});
