async function verificarSesion() {
    const response = await fetch('../auth/session.php');
    const data = await response.json();
    if (!data.usuario) {
        alert("Tu sesión ha expirado. Por favor, vuelve a iniciar sesión.");
        window.location.href = "iniciarSesion.php";
    }
}

verificarSesion();