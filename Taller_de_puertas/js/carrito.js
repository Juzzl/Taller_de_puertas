
// Archivo: carrito.js
// Descripción: Maneja la lógica de la página del carrito de compras.

document.addEventListener('DOMContentLoaded', () => {
    console.log('Página cargada: carrito.html');

    // Obtener los productos del carrito desde el backend
    fetch('/api/carrito.php')
        .then(response => response.json())
        .then(data => {
            console.log('Productos en el carrito:', data);
            const carritoContainer = document.getElementById('carrito-container');
            
            // Renderizar productos en el carrito
            data.forEach(item => {
                const carritoElement = document.createElement('div');
                carritoElement.className = 'carrito-item';
                carritoElement.innerHTML = `
                    <h3>${item.nombre}</h3>
                    <p>Cantidad: ${item.cantidad}</p>
                    <p>Subtotal: $${item.subtotal.toFixed(2)}</p>
                    <button data-id="${item.id}" class="btn-eliminar">Eliminar</button>
                `;
                carritoContainer.appendChild(carritoElement);
            });
        })
        .catch(error => {
            console.error('Error al cargar productos del carrito:', error);
        });

    // Agregar lógica para eliminar productos del carrito (ejemplo)
    document.body.addEventListener('click', (event) => {
        if (event.target.classList.contains('btn-eliminar')) {
            const idProducto = event.target.dataset.id;
            console.log('Eliminar producto con ID:', idProducto);
            
            // Lógica para eliminar en el backend (simulado)
            fetch(`/api/carrito.php?id=${idProducto}`, { method: 'DELETE' })
                .then(response => response.json())
                .then(data => {
                    console.log('Producto eliminado:', data);
                    // Recargar la página o actualizar el DOM
                })
                .catch(error => {
                    console.error('Error al eliminar producto del carrito:', error);
                });
        }
    });
});
