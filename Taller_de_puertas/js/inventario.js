
// Archivo: inventario.js
// Descripción: Maneja la lógica de la página de gestión de inventario.

document.addEventListener('DOMContentLoaded', () => {
    console.log('Página cargada: inventario.html');

    const inventarioContainer = document.getElementById('inventario-container');

    // Función para cargar productos del inventario
    const cargarInventario = () => {
        fetch('/api/inventario.php')
            .then(response => response.json())
            .then(data => {
                inventarioContainer.innerHTML = ''; // Limpiar el contenedor
                data.forEach(producto => {
                    const productoElement = document.createElement('div');
                    productoElement.className = 'inventario-item';
                    productoElement.innerHTML = `
                        <h3>${producto.nombre}</h3>
                        <p>${producto.descripcion}</p>
                        <p>Precio: $${producto.precio_unitario.toFixed(2)}</p>
                        <p>Cantidad: ${producto.cantidad_inventario}</p>
                        <button data-id="${producto.id_producto}" class="btn-editar">Editar</button>
                        <button data-id="${producto.id_producto}" class="btn-eliminar">Eliminar</button>
                    `;
                    inventarioContainer.appendChild(productoElement);
                });
            })
            .catch(error => {
                console.error('Error al cargar el inventario:', error);
            });
    };

    // Función para eliminar un producto
    const eliminarProducto = (idProducto) => {
        fetch(`/api/inventario.php?id_producto=${idProducto}`, { method: 'DELETE' })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
                cargarInventario();
            })
            .catch(error => {
                console.error('Error al eliminar el producto:', error);
            });
    };

  // Listener para botones de eliminar
inventarioContainer.addEventListener('click', (event) => {
    if (event.target.classList.contains('btn-eliminar')) {
        const idProducto = event.target.dataset.id;
        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            eliminarProducto(idProducto);
        }
    }
});

    // Inicializar carga del inventario
    cargarInventario();
});
