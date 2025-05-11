let carrito = [];
let idUsuario = null;

document.addEventListener("DOMContentLoaded", () => {
    verificarSesion();
    cargarProductos();

    document.getElementById("btnFinalizarVenta").addEventListener("click", finalizarVenta);
});

async function verificarSesion() {
    const response = await fetch('../auth/session.php');
    const data = await response.json();
    if (!data.usuario) {
        alert("Tu sesión ha expirado. Por favor, vuelve a iniciar sesión.");
        window.location.href = "iniciarSesion.php";
    }
    else {
        idUsuario = data.usuario.id
    }
}

async function cargarProductos() {
    try {
        const response = await fetch("../productos/read.php");
        const data = await response.json();
        const contenedor = document.getElementById("lista-productos");
        contenedor.innerHTML = "";

        if (data.length === 0) {
            contenedor.innerHTML = "<p>No hay productos registrados.</p>";
            return;
        }

        data.forEach(prod => {
            const div = document.createElement("div");
            div.classList.add("producto");

  div.innerHTML = `
    <h3>${prod.nombre}</h3>
    <p><strong>Precio Venta:</strong> $${prod.precio_venta}</p>
    <p><strong>Disponibles:</strong> ${prod.stock}</p>
    ${prod.imagen ? `<img src="imagenes/${prod.imagen}" alt="${prod.nombre}" width="100">` : ''}
    <br>
    ${prod.stock > 0 ? `
        <input type='number' min='1' max='${prod.stock}' placeholder='Cantidad' id='cantidad-${prod.id}' value='1' step='1'>
        <button onclick='agregarAlCarrito(${JSON.stringify(prod).replace(/'/g, "\\'")})'>Agregar al carrito</button>
    ` : `<p style="color:red;"><strong>Sin stock disponible</strong></p>`}
`;
            contenedor.appendChild(div);
        });
    } catch (error) {
        console.error("Error cargando productos:", error);
    }
}

function agregarAlCarrito(prod) {
    const cantidad = parseInt(document.getElementById(`cantidad-${prod.id}`).value);
    if (!cantidad || cantidad < 1) {
        alert("Ingresa una cantidad válida");
        return;
    }

    const existente = carrito.find(p => p.id === prod.id);
    if (existente) {
        existente.cantidad += cantidad;
    } else {
        carrito.push({
            id: prod.id,
            nombre: prod.nombre,
            precio_unitario: parseFloat(prod.precio_venta),
            cantidad: cantidad
        });
    }

    actualizarCarrito();
}

function actualizarCarrito() {
    const contenedor = document.getElementById("carrito-lista");
    contenedor.innerHTML = "";

    let total = 0;

    carrito.forEach((item, index) => {
        const subtotal = item.precio_unitario * item.cantidad;
        total += subtotal;

        const div = document.createElement("div");
        div.innerHTML = `
            ${item.nombre} - ${item.cantidad} x $${item.precio_unitario} = $${subtotal.toFixed(2)}
            <button onclick="eliminarDelCarrito(${index})">Eliminar</button>
        `;
        contenedor.appendChild(div);
    });

    document.getElementById("total").textContent = total.toFixed(2);
}

function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    actualizarCarrito();
}

async function finalizarVenta() {
    if (carrito.length === 0) {
        alert("El carrito está vacío.");
        return;
    }

    const metodo_pago = document.getElementById("metodo_pago").value;

    const detalles = carrito.map(item => ({
        producto_id: item.id,
        cantidad: item.cantidad,
        precio_unitario: item.precio_unitario,
        subtotal: item.cantidad * item.precio_unitario
    }));

    const total = detalles.reduce((sum, d) => sum + d.subtotal, 0);

    const formData = new FormData();

    formData.append("usuario_id", idUsuario);

    formData.append("total", total);
    formData.append("metodo_pago", metodo_pago);
    formData.append("detalle", JSON.stringify(detalles));

    try {
        const response = await fetch("../ventas/create.php", {
            method: "POST",
            body: formData
        });
        const data = await response.json();

        if (data.mensaje) {
            alert(data.mensaje);
            //Aqui se crea el ARCHIVO PDF USANDO LA LIBRERIA DomPDF    
            if (data.id) {
                window.open(`../ventas/generar_factura.php?id=${data.id}`, '_blank');
            }

            carrito = [];
            actualizarCarrito();
            cargarProductos();

        } else {
            alert("Error al registrar la venta: " + (data.error || "Desconocido"));
        }
    } catch (error) {
        console.error("Error al finalizar venta:", error);
    }
}

async function finalizarVenta() {
    if (carrito.length === 0) {
        alert("El carrito está vacío.");
        return;
    }

    const metodo_pago = document.getElementById("metodo_pago").value;

    const detalles = carrito.map(item => ({
        producto_id: item.id,
        cantidad: item.cantidad,
        precio_unitario: item.precio_unitario,
        subtotal: item.cantidad * item.precio_unitario
    }));

    const total = detalles.reduce((sum, d) => sum + d.subtotal, 0);

    const formData = new FormData();
    formData.append("usuario_id", idUsuario);
    formData.append("total", total);
    formData.append("metodo_pago", metodo_pago);
    formData.append("detalle", JSON.stringify(detalles));

    try {
        const response = await fetch("../ventas/create.php", {
            method: "POST",
            body: formData
        });
        const data = await response.json();

        if (data.mensaje) {
            alert(data.mensaje);
            if (data.id) {
                window.open(`../ventas/generar_factura.php?id=${data.id}`, '_blank');
            }

            carrito = [];
            actualizarCarrito();
            cargarProductos(); // Recarga los productos con el stock actualizado

            // Aquí agregamos la línea para refrescar la página
            location.reload(); // Esto recargará la página después de finalizar la venta
        } else if (data.error) {
            alert("Error: " + data.error);
        } else {
            alert("Error al registrar la venta. Intenta nuevamente.");
        }
    } catch (error) {
        console.error("Error al finalizar venta:", error);
        alert("Error inesperado al procesar la venta.");
    }
}

