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

let slideIndex = 0;

function mostrarVentas(ventas) {
    const contenedor = document.getElementById('venta-slides');
    if (!contenedor) return;

    if (ventas.length === 0) {
        contenedor.innerHTML = '<p>No se encontraron ventas.</p>';
        return;
    }

    contenedor.innerHTML = ''; // Limpiar antes

    ventas.forEach((v, index) => {
        const tarjeta = document.createElement('div');
        tarjeta.className = 'venta-card';
        tarjeta.innerHTML = `
            <p><strong>ID:</strong> ${v.id}</p>
            <p><strong>Usuario:</strong> ${v.nombre_usuario}</p>
            <p><strong>Fecha:</strong> ${v.fecha}</p>
            <p><strong>Método de pago:</strong> ${v.metodo_pago}</p>
            <p><strong>Total:</strong> $${parseFloat(v.total).toFixed(2)}</p>
            <button class="btn-detalle" data-index="${index}">Ver detalles</button>
        `;
        contenedor.appendChild(tarjeta);
    });

    document.querySelectorAll('.btn-detalle').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const index = e.currentTarget.getAttribute('data-index');
            mostrarSidebar(ventas[index]);
        });
    });

    updateCarrusel(ventas.length);
}

function updateCarrusel(totalVentas) {
    const cardsPerView = 3;
    const totalSlides = Math.ceil(totalVentas / cardsPerView);

    const slides = document.querySelector('.venta-slides');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');

    const slideWidth = slides.clientWidth;

    prevBtn.disabled = slideIndex <= 0;
    nextBtn.disabled = slideIndex >= totalSlides - 1;

    slides.style.transform = `translateX(-${slideIndex * 100}%)`;

    prevBtn.onclick = () => {
        if (slideIndex > 0) {
            slideIndex--;
            updateCarrusel(totalVentas);
        }
    };

    nextBtn.onclick = () => {
        if (slideIndex < totalSlides - 1) {
            slideIndex++;
            updateCarrusel(totalVentas);
        }
    };
}


function mostrarSidebar(venta) {
    const sidebar = document.getElementById('sidebar-detalles');
    if (!sidebar) {
        console.error('No se encontró el elemento sidebar-detalles en el DOM.');
        return;
    }

let html = `
    <span class="cerrar-sidebar" onclick="cerrarSidebar()">✖</span>
    <h3>Detalles de la venta</h3>
    <p><strong>ID:</strong> ${venta.id}</p>
    <p><strong>Usuario:</strong> ${venta.nombre_usuario}</p>
    <p><strong>Fecha:</strong> ${venta.fecha}</p>
    <p><strong>Método de pago:</strong> ${venta.metodo_pago}</p>
    <p><strong>Total:</strong> $${parseFloat(venta.total).toFixed(2)}</p>
    <hr>
    <h4>Productos</h4>
    <ul>
        ${venta.detalles.map(d => `
            <li>${d.nombre_producto} - ${d.cantidad} x $${parseFloat(d.precio_unitario).toFixed(2)}</li>
        `).join('')}
    </ul>
    <div style="text-align: center; margin-top: 30px;">
        <button onclick="imprimirSidebar()" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
            🖨️ Imprimir
        </button>
    </div>
`;

    sidebar.innerHTML = html;
    sidebar.classList.add('visible');
}

function cerrarSidebar() {
    document.getElementById('sidebar-detalles').classList.remove('visible');
}

// Cargar ventas al inicio
cargarVentas();

