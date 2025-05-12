

let rol = null;


document.addEventListener("DOMContentLoaded", () => {

    cargarUsuario();
    cargarNotificaciones();

    if (document.getElementById('venderEmpleado')) {
        document.getElementById('venderEmpleado').addEventListener('click', venderEmpleado);
    }

    if (document.getElementById('vender')) {
        document.getElementById('vender').addEventListener('click', vender);
    }

    if (document.getElementById('productos')) {
        document.getElementById('productos').addEventListener('click', productos);
    }
});


async function cargarUsuario() {
    const response = await fetch('../auth/session.php');
    const data = await response.json();

    if (data.usuario) {

        if (data.usuario.rol === 'empleado') {
            rol = 'Operador de caja';
        }
        else if (data.usuario.rol) {
            rol = 'Administrador';
        }

        document.getElementById('bienvenida').innerText = 'Bienvenido/a, ' + data.usuario.nombre;
        document.getElementById('rol').innerText = 'Rol: ' + rol;
    } else {
        window.location.href = 'iniciarSesion.php';
    }
}


function cargarNotificaciones() {
    fetch("../productos/notificaciones.php")
        .then(res => res.json())
        .then(data => {
            const contenedor = document.getElementById("notificaciones");

            if (data.length === 0) {
                contenedor.innerHTML = "<p class='notificacion info'>No hay notificaciones por el momento.</p>";
            } else {
                contenedor.innerHTML = ""; // limpiar
                data.forEach(msg => {
                    const div = document.createElement("div");
                    div.classList.add("notificacion");

                    // Detectar tipo
                    if (msg.includes("stock bajo")) {
                        div.classList.add("bajo-stock");
                    } else if (msg.includes("caduca pronto")) {
                        div.classList.add("caducidad");
                    } else {
                        div.classList.add("info");
                    }

                    div.innerHTML = msg;
                    contenedor.appendChild(div);
                });
            }
        });

}




function venderEmpleado() {
    window.location.href = "venderEmpleado.php";
}

function vender() {
    window.location.href = "vender.php";
}

function productos() {
    window.location.href = "productos.php";
}

