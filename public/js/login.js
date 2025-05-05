window.addEventListener('pageshow', (event) => {
    // Solo si es una navegación de tipo back/forward
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
        verificarSesion();
    }
});


document.addEventListener('DOMContentLoaded', () => {

    verificarSesion();

    //const btn = document.getElementById('btnLogin');
    //btn.addEventListener("click", login);

    const form = document.getElementById('formulario-login');
    const mensaje = document.getElementById('mensaje');
    form.addEventListener('submit', async (e) => {
        e.preventDefault(); // Evita la recarga
        const formData = new FormData(form);

        try {
            const response = await fetch('../auth/login.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.usuario) {
                // Redirigir al dashboard o página principal
                mensaje.style.color = 'green';
                mensaje.innerText = 'Bienvenido, ' + result.usuario.nombre;
                if (result.usuario.rol === 'admin') {
                    window.location.href = "dashboard.php";
                }
                else {
                    window.location.href = "dashboardEmpleado.php";
                }

            } else {
                mensaje.style.color = 'red';
                mensaje.innerText = result.error || 'Ops, parece que ha ocurrido un error';
            }
        } catch (error) {
            mensaje.style.color = 'red';
            mensaje.innerText = 'Ocurrió un error de conexión';
        }
    });
});


async function verificarSesion() {

    const response = await fetch('../auth/session.php');
    const data = await response.json();

    if (data.usuario) {
        document.getElementById('formulario-login').style.display = 'none';
        document.getElementById('mensaje').style.display = 'none';
        document.getElementById('alerta').innerHTML = `
  <div class="alert">
    Actualmente ha iniciado sesión como <strong>${data.usuario.nombre}</strong>.<br>
    Necesita cerrar sesión antes de iniciar con un usuario diferente.
    <br><br>
    <button onclick="logout()" class="btnAzul">Cerrar sesión</button>
    <button onclick="cancel()" class="btnAzul">Cancelar</button>
  </div>
`;
    }
}

/* async function login() {
    const usuario = document.getElementById("usuario").value;
    const contrasena = document.getElementById("contrasena").value;
    const mensaje = document.getElementById('mensaje');

    const response = await fetch('../auth/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ usuario, contrasena })
    });

    const data = await response.json();

    if (data.usuario) {
        mensaje.style.color = 'green';
        mensaje.innerText = 'Bienvenido, ' + data.usuario.nombre;
        window.location.href = "dashboard.php";
    } else {
        mensaje.style.color = 'red';
        mensaje.innerText = data.error || 'Ops, parece que ha ocurrido un error';
    }
} */

async function logout() {
    await fetch('../auth/logout.php');
    window.location.reload();
}

async function cancel() {
    window.location.href = "dashboard.php";
}

