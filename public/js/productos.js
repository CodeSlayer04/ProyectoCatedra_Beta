let modoEdicion = false;
let productoEditandoId = null;


document.addEventListener("DOMContentLoaded", () => {
    cargarCategorias();
    cargarProductos();

    //const btnCrearCategoria = document.getElementById("btnCrearCategoria");
    //const btnCrearProducto = document.getElementById("btnCrearProducto");

    //btnCrearCategoria.addEventListener("click", crearCategoria);
    //btnCrearProducto.addEventListener("click", crearProducto);

    const formCategoriasCreate = document.getElementById("form-categorias-create");
    const formProductosCreate = document.getElementById("form-productos-create");
    formCategoriasCreate.addEventListener('submit', crearCategoria);
    formProductosCreate.addEventListener('submit', crearProducto);

    const activarFecha = document.getElementById("activarFecha");
    activarFecha.addEventListener("change", toggleFecha);

    document.getElementById('filtroFormProductos').addEventListener('submit', function (e) {
        e.preventDefault(); // Evita la recarga de la página
        cargarProductos(); // Llama a la función para cargar las ventas con los filtros aplicados
    });
    document.getElementById('limpiarFiltros').addEventListener('click', limpiarFiltros);
});

async function cargarCategorias() {
    try {
        const response = await fetch("../categorias/read.php");
        const data = await response.json();

        const select = document.getElementById("categoria_id");
        select.innerHTML = "";

        const select2 = document.getElementById("filtro_categoria_id");
        select2.innerHTML = '<option value="" selected>Todos</option>';

        data.forEach(cat => {
            const option = document.createElement("option");
            option.value = cat.id;
            option.textContent = cat.nombre;
            select.appendChild(option);
        });

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

// async function cargarProductos() {
//     try {
//         const response = await fetch("../productos/read.php");
//         const data = await response.json();

//         const contenedor = document.getElementById("lista-productos");
//         contenedor.innerHTML = "";

//         if (data.length === 0) {
//             contenedor.innerHTML = "<p>No hay productos registrados.</p>";
//             return;
//         }

//         data.forEach(prod => {
//             const card = document.createElement("div");
//             card.classList.add("card-producto");

//             const imagen = prod.imagen ? `imagenes/${prod.imagen}` : "https://via.placeholder.com/100";

//             card.innerHTML = `
//                 <img src="${imagen}" alt="${prod.nombre}">
//                 <div class="contenido">
//                     <h3>${prod.nombre}</h3>
//                     <p><strong>SKU:</strong> ${prod.sku}</p>
//                     <p><strong>Stock:</strong> ${prod.stock}</p>
//                     <p><strong>Precio:</strong> $${prod.precio_venta}</p>
//                     <div class="botones">
//                         <button onclick="editarProducto(${prod.id})">Modificar</button>
//                         <button onclick="alert('Stock actual: ${prod.stock}')">Cantidad</button>
//                     </div>
//                 </div>
//             `;
//             contenedor.appendChild(card);
//         });
//     } catch (error) {
//         console.error("Error cargando productos:", error);
//     }
// }

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
                <p><strong>Descripción:</strong> ${prod.descripcion || "-"}</p>
                <p><strong>SKU:</strong> ${prod.sku}</p>
                <p><strong>Precio Compra:</strong> $${prod.precio_compra}</p>
                <p><strong>Precio Venta:</strong> $${prod.precio_venta}</p>
                <p><strong>Stock:</strong> ${prod.stock}</p>
                <p><strong>Categoría:</strong> ${prod.categoria_nombre || 'Sin categoría'}</p>
                <p><strong>Caduca:</strong> ${prod.fecha_caducidad === '0000-00-00' ? 'N/A' : prod.fecha_caducidad}</p>
                
                <div style="margin-top: 10px;">
                    <button onclick="editarProducto(${prod.id})">Editar</button>
                    <button onclick="eliminarProducto(${prod.id})">Eliminar</button>
                </div>
            </center>
        `;
        contenedor.appendChild(tarjeta);
    });
}



async function crearCategoria(e) {


    e.preventDefault(); //evita la recarga de la página
    const formCategoriasCreate = document.getElementById("form-categorias-create");
    const formData = new FormData(formCategoriasCreate);


    /* const nombre = document.getElementById("nombreCat").value.trim();
    const descripcion = document.getElementById("descripcionCat").value.trim();

    if (!nombre) {
        alert("El nombre de la categoría es obligatorio.");
        return;
    } */

    try {

        const response = await fetch("../categorias/create.php", {
            method: "POST",
            //headers: { 'Content-Type': 'application/json' },
            //body: JSON.stringify({ nombre, descripcion })
            body: formData
        });

        const result = await response.json();

        if (result.mensaje) {
            alert("Categoría creada exitosamente.");
            document.getElementById("nombreCat").value = "";
            document.getElementById("descripcionCat").value = "";
            cargarCategorias();
        } else {
            alert("Error al crear categoría: " + (result.error || "desconocido"));
        }
    } catch (error) {
        console.error("Error al crear categoría:", error);
    }
}






async function crearProducto(e) {

    e.preventDefault();
    const formProductosCreate = document.getElementById("form-productos-create");
    const formData = new FormData(formProductosCreate);

    // Si estamos en modo edición, añadimos el ID del producto
    if (modoEdicion && productoEditandoId) {
        formData.append("id", productoEditandoId);
    }

    const imagenInput = document.getElementById("imagen");
    const archivo = imagenInput.files[0];

    // Validar tamaño máximo: 2MB = 2 * 1024 * 1024 bytes
    if (archivo && archivo.size > 2 * 1024 * 1024) {
        alert("La imagen es demasiado grande. Debe ser menor a 2MB.");
        return;
    }

    /* const nombre = document.getElementById("nombre").value.trim();
    const sku = document.getElementById("sku").value.trim();
    const precioCompra = document.getElementById("precio_compra").value;
    const precioVenta = document.getElementById("precio_venta").value;
    const stock = document.getElementById("stock").value;
    const fechaCaducidad = document.getElementById("fecha_caducidad").value;
    const categoriaId = document.getElementById("categoria_id").value;
    const imagen = document.getElementById("imagen").files[0];

    if (!nombre || !sku || !precioCompra || !precioVenta || !stock || !categoriaId) {
        alert("Por favor, completa todos los campos obligatorios.");
        return;
    } */

    try {
        const url = modoEdicion ? "../productos/update.php" : "../productos/create.php";
        const response = await fetch(url, {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (result.mensaje) {
            alert(modoEdicion ? "Producto actualizado correctamente." : "Producto creado correctamente.");
            modoEdicion = false;
            productoEditandoId = null;
            document.querySelector('#form-productos-create button[type="submit"]').textContent = "Crear";
            formProductosCreate.reset();
            cargarProductos();
        } else {
            alert("Error en crear producto: " + (result.error || "desconocido"));
        }

    } catch (error) {
        console.error("Error al crear producto:", error);
    }
}



async function editarProducto(id) {
    try {
        const response = await fetch(`../productos/read.php?id=${id}`);
        const producto = await response.json();

        // Llenar el formulario con los datos
        document.getElementById("nombre").value = producto.nombre;
        document.getElementById("descripcion").value = producto.descripcion;
        document.getElementById("sku").value = producto.sku;
        document.getElementById("precio_compra").value = producto.precio_compra;
        document.getElementById("precio_venta").value = producto.precio_venta;
        document.getElementById("stock").value = producto.stock;
        document.getElementById("categoria_id").value = producto.categoria_id;

        const campoFecha = document.getElementById("fecha_caducidad");
        if (producto.fecha_caducidad && producto.fecha_caducidad !== '0000-00-00') {
            document.getElementById("activarFecha").checked = true;
            campoFecha.style.visibility = 'visible';
            campoFecha.value = producto.fecha_caducidad;
        } else {
            document.getElementById("activarFecha").checked = false;
            campoFecha.style.visibility = 'hidden';
            campoFecha.value = '';
        }

        // Establecer modo edición
        modoEdicion = true;
        productoEditandoId = id;

        // Cambiar texto del botón
        document.querySelector('#form-productos-create button[type="submit"]').textContent = "Actualizar";
    } catch (error) {
        console.error("Error al cargar el producto para edición:", error);
        alert("No se pudo cargar el producto.");
    }
}


async function eliminarProducto(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
        try {
            const response = await fetch(`../productos/delete.php?id=${id}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            const result = await response.json();

            if (result.mensaje) {
                alert("Producto eliminado");
                cargarProductos();
            } else {
                alert("Error al eliminar: " + (result.error || "desconocido"));
            }
        } catch (error) {
            console.error("Error al eliminar producto:", error);
        }
    }
}

async function toggleFecha() {

    const campoFecha = document.getElementById("fecha_caducidad");

    if (campoFecha.style.visibility === 'hidden') {
        campoFecha.style.visibility = 'visible';
    }
    else {
        campoFecha.style.visibility = 'hidden';
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