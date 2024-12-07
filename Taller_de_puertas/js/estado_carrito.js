
// Archivo: estado_carrito.js
// Descripción: Maneja la lógica para actualizar el estado del carrito.

document.addEventListener('DOMContentLoaded', () => {
    console.log('Página cargada: carrito.html');

    const actualizarEstadoCarrito = (idCarrito, idEstadoCarrito) => {
        fetch('/api/estado_carrito.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_carrito: idCarrito, id_estado_carrito: idEstadoCarrito })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.message);
            if (data.success) {
                alert('Estado del carrito actualizado correctamente.');
            } else {
                alert('Error al actualizar el estado del carrito.');
            }
        })
        .catch(error => {
            console.error('Error al actualizar el estado del carrito:', error);
        });
    };

    // Ejemplo de uso: actualizar el estado del carrito con ID 1 a estado 2
    const btnActualizarEstado = document.getElementById('btn-actualizar-estado');
    if (btnActualizarEstado) {
        btnActualizarEstado.addEventListener('click', () => {
            const idCarrito = 1; // Cambiar por el ID dinámico del carrito
            const idEstadoCarrito = 2; // Cambiar por el ID dinámico del nuevo estado
            actualizarEstadoCarrito(idCarrito, idEstadoCarrito);
        });
    }
});
