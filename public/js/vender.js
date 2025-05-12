let carrito = [];
let idUsuario = null;

document.addEventListener("DOMContentLoaded", () => {
    verificarSesion();
    cargarProductos();
    cargarCategorias();

    document.getElementById("btnFinalizarVenta").addEventListener("click", finalizarVenta);
    document.getElementById('filtroFormProductos').addEventListener('submit', function (e) {
        e.preventDefault(); // Evita la recarga de la página
        cargarProductos(); // Llama a la función para cargar las ventas con los filtros aplicados
    });
    document.getElementById('limpiarFiltros').addEventListener('click', limpiarFiltros);
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

/* async function cargarProductos() {
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

    <section class="producto item">
        <section class="producto imagen">
        ${prod.imagen ? `<img class="producto imagen" src="imagenes/${prod.imagen}" alt="${prod.nombre}" width="100">` : ''}
        </section>
        <section class="producto detalle">
          <p class="producto detalle nombre"><strong>${prod.nombre}</strong></p>
          <p class="producto detalle precio"><strong>Precio:</strong> $${prod.precio_venta}</p>
            <p><strong>Disponibles:</strong> ${prod.stock}</p>
          </section>
        ${prod.stock > 0 ? `
        <input type='number' min='1' max='${prod.stock}' placeholder='Cantidad' id='cantidad-${prod.id}' value='1' step='1'>
        <button class="boton producto agregar" onclick='agregarAlCarrito(${JSON.stringify(prod).replace(/'/g, "\\'")})'>Agregar al carrito</button>
    ` : `<p style="color:red;"><strong>Sin stock disponible</strong></p>`}
      </section>
`;
            contenedor.appendChild(div);
        });
    } catch (error) {
        console.error("Error cargando productos:", error);
    }
} */

async function cargarProductos() {

    const nombrePro = document.getElementById('filtro_nombre').value;
    const skuPro = document.getElementById('filtro_sku').value;
    const precioMin = document.getElementById('filtro_precio_min').value;
    const precioMax = document.getElementById('filtro_precio_max').value;
    const categoriaProd = document.getElementById('filtro_categoria_id').value;

    const params = new URLSearchParams();
    if (nombrePro) params.append('filtro_nombre', nombrePro);
    if (skuPro) params.append('filtro_sku', skuPro);
    if (precioMin) params.append('filtro_precio_min', precioMin);
    if (precioMax) params.append('filtro_precio_max', precioMax);
    if (categoriaProd) params.append('filtro_categoria', categoriaProd);

    console.log('URL enviada:', '../productos/read.php?' + params.toString()); // Depuración: Verifica la URL generada

    fetch('../productos/read.php?' + params.toString())
        .then(res => res.json())
        .then(data => mostrarProductos(data)) // Llama a la función para mostrar las ventas filtradas
        .catch(err => {
            document.getElementById('lista-productos').innerHTML = `<p>Error al cargar productos.</p>`;
            console.error(err);
        });
}

function mostrarProductos(productos) {


    const contenedor = document.getElementById("lista-productos");
    if (!contenedor) return;



    if (productos.length === 0) {
        contenedor.innerHTML = '<p>No se encontraron productos con los filtros seleccionados.</p>';
        return;
    }

    contenedor.innerHTML = ''; // Limpiar antes de agregar nuevas tarjetas

    productos.forEach((prod, index) => {
        const tarjeta = document.createElement("div");
        tarjeta.classList.add("producto");

        tarjeta.innerHTML = `


        <center>
                ${prod.imagen ? `<img src="imagenes/${prod.imagen}" alt="${prod.nombre}" width="100">` : ''}
                <h3>${prod.nombre}</h2>
                <p><strong>Precio Venta:</strong> $${prod.precio_venta}</p>
                <p><strong>Categoría:</strong> ${prod.categoria_nombre || 'Sin categoría'}</p>
                <p><strong>Disponible:</strong> ${prod.stock}</p>
        ${prod.stock > 0 ? `
        <input class='cantidadAgregar' type='number' min='1' max='${prod.stock}' placeholder='Cantidad' id='cantidad-${prod.id}' value='1' step='1'>
        <br>
        <br>
        <button class="boton producto agregar" onclick='agregarAlCarrito(${JSON.stringify(prod).replace(/'/g, "\\'")})'>Agregar al carrito</button>
    ` : `<p style="color:red;"><strong>Sin stock disponible</strong></p>`}
      </center>
        `;
        contenedor.appendChild(tarjeta);
    });
}




function agregarAlCarrito(prod) {
    const input = document.getElementById(`cantidad-${prod.id}`);
    const cantidad = parseInt(input.value);
    const max = parseInt(input.max); // Valor máximo permitido desde el atributo max (igual a prod.stock)

    if (!cantidad || cantidad < 1) {
        alert("Ingresa una cantidad válida (mínimo 1)");
        input.value = 1;
        return;
    }

    if (cantidad > max) {
        alert(`No puedes agregar más de ${max} unidades. Stock insuficiente.`);
        input.value = max;
        return;
    }

    const existente = carrito.find(p => p.id === prod.id);
    if (existente) {
        if (existente.cantidad + cantidad > max) {
            alert(`Solo puedes agregar ${max - existente.cantidad} unidades más de este producto.`);
            return;
        }
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
            <section class="items">
            ${item.nombre} - ${item.cantidad} x $${item.precio_unitario} = $${subtotal.toFixed(2)}
            <button class="boton btncarrito" onclick="eliminarDelCarrito(${index})">
    <img src="imagenes/delete.png" alt="Eliminar" class="icono-eliminar">
</button>
            </section>
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

async function cargarCategorias() {
    try {
        const response = await fetch("../categorias/read.php");
        const data = await response.json();


        const select2 = document.getElementById("filtro_categoria_id");
        select2.innerHTML = '<option value="" selected>Todos</option>';

        data.forEach(cat => {
            const option = document.createElement("option");
            option.value = cat.id;
            option.textContent = cat.nombre;
            select2.appendChild(option);
        });
    } catch (error) {
        console.error("Error cargando categorías:", error);
    }
}

function limpiarFiltros() {

    // Limpiar todos los inputs
    document.getElementById('filtro_nombre').value = '';
    document.getElementById('filtro_sku').value = '';
    document.getElementById('filtro_precio_min').value = '';
    document.getElementById('filtro_precio_max').value = '';
    document.getElementById('filtro_categoria_id').value = '';

    // Cargar todos los productos sin filtros
    cargarProductos();
}