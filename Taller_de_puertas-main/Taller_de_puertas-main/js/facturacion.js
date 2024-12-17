
// Archivo: facturacion.js
// Descripción: Maneja la lógica de la página de facturación.

document.addEventListener('DOMContentLoaded', () => {
    console.log('Página cargada: facturacion.html');

    const facturasContainer = document.getElementById('facturas-container');

    // Función para cargar facturas
    const cargarFacturas = (idUsuario) => {
        fetch(`/api/facturacion.php?id_usuario=${idUsuario}`)
            .then(response => response.json())
            .then(data => {
                facturasContainer.innerHTML = ''; // Limpiar el contenedor
                data.forEach(factura => {
                    const facturaElement = document.createElement('div');
                    facturaElement.className = 'factura-item';
                    facturaElement.innerHTML = `
                        <h3>Factura #${factura.id_factura}</h3>
                        <p>Fecha: ${factura.fecha_creacion}</p>
                        <p>Total: $${factura.total.toFixed(2)}</p>
                        <p>Estado: ${factura.estado}</p>
                        <p>Método de Pago: ${factura.metodo_pago}</p>
                    `;
                    facturasContainer.appendChild(facturaElement);
                });
            })
            .catch(error => {
                console.error('Error al cargar las facturas:', error);
            });
    };

    // Ejemplo de carga con ID de usuario 1 (puedes cambiarlo dinámicamente)
    cargarFacturas(1);
});
