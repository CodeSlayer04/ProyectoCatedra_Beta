/* document.addEventListener("DOMContentLoaded", () => {
    cargarHistorialVentas();
});

async function cargarHistorialVentas() {
    try {
        const response = await fetch("../ventas/read.php");
        const data = await response.json();

        const contenedor = document.getElementById("historial-ventas");
        contenedor.innerHTML = "";

        if (data.length === 0) {
            contenedor.innerHTML = "<p>No hay ventas registradas.</p>";
            return;
        }

        data.forEach(venta => {
            const div = document.createElement("div");
            div.classList.add("venta");

            let html = `
                <h3>Venta #${venta.id}</h3>
                <p><strong>Fecha:</strong> ${venta.fecha}</p>
                <p><strong>Vendedor:</strong> ${venta.nombre_usuario || 'Desconocido'}</p>
                <p><strong>Método de pago:</strong> ${venta.metodo_pago}</p>
                <p><strong>Total:</strong> $${venta.total}</p>
                <h4>Detalles:</h4>
                <ul>
            `;

            if (venta.detalles && venta.detalles.length > 0) {
                venta.detalles.forEach(det => {
                    html += `
                        <li>${det.nombre_producto} - ${det.cantidad} x $${det.precio_unitario} = $${det.subtotal}</li>
                    `;
                });
            } else {
                html += `<li>Sin detalles registrados.</li>`;
            }

            html += `</ul>`;
            div.innerHTML = html;
            contenedor.appendChild(div);
        });

    } catch (error) {
        console.error("Error al cargar historial de ventas:", error);
    }
}
 */


document.getElementById('filtroForm').addEventListener('submit', function (e) {
    e.preventDefault();
    cargarVentas();
});

function cargarVentas() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const usuario = document.getElementById('usuario').value;
    const metodoPago = document.getElementById('metodo_pago').value;

    const params = new URLSearchParams();
    if (fechaInicio) params.append('fecha_inicio', fechaInicio);
    if (fechaFin) params.append('fecha_fin', fechaFin);
    if (usuario) params.append('usuario', usuario);
    if (metodoPago) params.append('metodo_pago', metodoPago);

    fetch('../ventas/read.php?' + params.toString())
        .then(res => res.json())
        .then(data => mostrarVentas(data))
        .catch(err => {
            document.getElementById('historial-ventas').innerHTML = `<p>Error al cargar ventas.</p>`;
            console.error(err);
        });
}

function mostrarVentas(ventas) {
    const contenedor = document.getElementById('historial-ventas');
    if (ventas.length === 0) {
        contenedor.innerHTML = '<p>No se encontraron ventas con los filtros aplicados.</p>';
        return;
    }

    let html = '<table border="1" cellpadding="5" cellspacing="0">';
    html += `
    <tr>
      <th>ID</th>
      <th>Usuario</th>
      <th>Fecha</th>
      <th>Método de Pago</th>
      <th>Total</th>
      <th>Detalles</th>
    </tr>
  `;

    ventas.forEach(v => {
        html += `
      <tr>
        <td>${v.id}</td>
        <td>${v.nombre_usuario}</td>
        <td>${v.fecha}</td>
        <td>${v.metodo_pago}</td>
        <td>$${parseFloat(v.total).toFixed(2)}</td>
        <td>
          <ul>
            ${v.detalles.map(d => `
              <li>${d.nombre_producto} - ${d.cantidad} x $${parseFloat(d.precio_unitario).toFixed(2)}</li>
            `).join('')}
          </ul>
        </td>
      </tr>
    `;
    });

    html += '</table>';
    contenedor.innerHTML = html;
}

// Cargar ventas al inicio
cargarVentas();
