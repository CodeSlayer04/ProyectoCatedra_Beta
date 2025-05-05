let modoEdicion = false;
let usuarioEditandoId = null;


document.addEventListener("DOMContentLoaded", () => {

    cargarUsuarios();


    const formProductosCreate = document.getElementById("form-usuarios-create");
    formProductosCreate.addEventListener('submit', crearUsuario);

    const btnMostrar = document.getElementById('btnMostrarContra');
    btnMostrar.addEventListener('click', mostrarContra);

});

async function mostrarContra(e) {
    e.preventDefault(); // Evita que el formulario se envíe al hacer clic

    const campoContrasena = document.getElementById("contrasena");

    // Alternar entre tipo password y text
    if (campoContrasena.type === "password") {
        campoContrasena.type = "text";
        this.textContent = "Ocultar 🙈";
    } else {
        campoContrasena.type = "password";
        this.textContent = "Mostrar 👁️";
    }
}


async function cargarUsuarios() {
    try {
        const response = await fetch("../usuarios/read.php");
        const data = await response.json();

        const contenedor = document.getElementById("lista-usuarios");
        contenedor.innerHTML = "";

        if (data.length === 0) {
            contenedor.innerHTML = "<p>No hay usuarios registrados.</p>";
            return;
        }

        data.forEach(user => {
            const div = document.createElement("div");
            div.classList.add("user");

            div.innerHTML = `
                <h3>${user.nombre}</h3>
                <p><strong>Nombre de usuario:</strong> ${user.usuario}</p>
                <p><strong>Correo electrónico:</strong> ${user.correo}</p>
                <p><strong>Rol:</strong> ${user.rol}</p>
                <button onclick="editarUsuario(${user.id})">Editar</button>
                <button onclick="eliminarUsuario(${user.id})">Eliminar</button>
            `;
            contenedor.appendChild(div);
        });
    } catch (error) {
        console.error("Error cargando usuarios:", error);
    }
}



async function crearUsuario(e) {

    e.preventDefault();
    const formUsuariosCreate = document.getElementById("form-usuarios-create");
    const formData = new FormData(formUsuariosCreate);

    // Si estamos en modo edición, añadimos el ID del user
    if (modoEdicion && usuarioEditandoId) {
        formData.append("id", usuarioEditandoId);
    }

    try {
        const url = modoEdicion ? "../usuarios/update.php" : "../usuarios/create.php";
        const response = await fetch(url, {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (result.mensaje) {
            alert(modoEdicion ? "Usuario actualizado correctamente." : "Usuario creado correctamente.");
            modoEdicion = false;
            productoEditandoId = null;
            document.querySelector('#form-usuarios-create button[type="submit"]').textContent = "Crear";
            document.getElementById("contrasena").required = true;
            formUsuariosCreate.reset();
            cargarUsuarios();
        } else {
            alert("Error en crear usuario: " + (result.error || "desconocido"));
        }

    } catch (error) {
        console.error("Error al crear usuario:", error);
    }
}



async function editarUsuario(id) {
    try {
        const response = await fetch(`../usuarios/read.php?id=${id}`);
        const user = await response.json();

        // Llenar el formulario con los datos
        document.getElementById("nombre").value = user.nombre;
        document.getElementById("usuario").value = user.usuario;
        document.getElementById("correo").value = user.correo;
        document.getElementById("contrasena").value = '';
        document.getElementById("rol").value = user.rol;

        // Establecer modo edición
        modoEdicion = true;
        usuarioEditandoId = id;

        document.getElementById("contrasena").required = false;

        // Cambiar texto del botón
        document.querySelector('#form-usuarios-create button[type="submit"]').textContent = "Actualizar";
    } catch (error) {
        console.error("Error al cargar el usuario para edición:", error);
        alert("No se pudo cargar el usuario.");
    }
}


async function eliminarUsuario(id) {
    if (confirm("¿Está seguro de que desea eliminar este usuario?")) {

        if (confirm("Los cambios realizados no podrán revertirse ¿Desea continuar?")) {
            try {
                const response = await fetch(`../usuarios/delete.php?id=${id}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const result = await response.json();

                if (result.mensaje) {
                    alert("Usuario eliminado");
                    cargarUsuarios();
                } else {
                    alert("Error al eliminar: " + (result.error || "desconocido"));
                }
            } catch (error) {
                console.error("Error al eliminar usuario:", error);
            }
        }
    }
}

