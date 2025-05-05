



async function cargarUsuario() {
    const response = await fetch('../auth/session.php');
    const data = await response.json();

    if (data.usuario) {
        document.getElementById('bienvenida').innerText = 'Bienvenido, ' + data.usuario.nombre;
        document.getElementById('rol').innerText = 'Rol: ' + data.usuario.rol;
    } else {
        window.location.href = 'iniciarSesion.php';
    }
}

cargarUsuario();