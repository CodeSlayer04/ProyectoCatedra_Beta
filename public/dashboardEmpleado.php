<?php
//session_start(); Ya esta incluido en el archivo de abajo

require_once('../auth/verificar_acceso.php');

if (!isset($_SESSION['usuario'])) {
    header("Location: iniciarSesion.php");
    exit;
}

verificarRol(['admin', 'empleado']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Variedades Escobar</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <header>
        <section class="menu navegacion">
                <h1>Variedades Escobar</h1>
            <nav class="navegacion">
                <a class="boton activo" href="dashboardEmpleado.php">Inicio</a>
                <a class="boton" href="venderEmpleado.php">Registrar compra</a>
                <button id="btnLogout" type="button">Cerrar Sesión</button>
            </nav>
        </section>
    </header>
    <main>

        <div class="card">
            <h2 id="bienvenida">Cargando...</h2>
            <p id="rol"></p>

        </div>

        <div class="card">
            <h2>Panel de administración</h2>
            <button>Gestionar productos</button>
        </div>

    </main>
    <footer>
        <p>&copy; 2025 Variedades Escobar</p>
    </footer>

    <script src="js/dashboard.js"></script>
    <script src="js/verificarSesion.js"></script>
    <script src="js/logout.js"></script>
</body>

</html>