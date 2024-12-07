
// Archivo: catalogo.js
// Descripción: Maneja la lógica de la página de catálogo.

document.addEventListener('DOMContentLoaded', () => {
    console.log('Página cargada: catalogo.html');

    // Obtener la lista de productos del backend
    fetch('/api/catalogo.php')
        .then(response => response.json())
        .then(data => {
            console.log('Productos del catálogo:', data);
            const catalogoContainer = document.getElementById('catalogo-container');
            
            // Renderizar productos en la página
            data.forEach(producto => {
                const productoElement = document.createElement('div');
                productoElement.className = 'producto';
                productoElement.innerHTML = `
                    <h3>${producto.nombre}</h3>
                    <p>${producto.descripcion}</p>
                    <p>Precio: $${producto.precio.toFixed(2)}</p>
                    <button data-id="${producto.id}" class="btn-agregar-carrito">Agregar al carrito</button>
                `;
                catalogoContainer.appendChild(productoElement);
            });
        })
        .catch(error => {
            console.error('Error al cargar productos del catálogo:', error);
        });
});
