<?php
//session_start();

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
    <title>Atender compra</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <header>
        <h1>Registrar venta</h1>
        <nav>
            <a href="dashboardEmpleado.php">Inicio</a>
            <a href="venderEmpleado.php">Registrar compra</a>
            <button id="btnLogout" type="button">Cerrar Sesión</button>
        </nav>
    </header>

    <main>

        <div class="card">
            <h2>Productos registrados</h2>
            <div id="lista-productos">Cargando...</div>
        </div>
    </main>

    <script src="js/verificarSesion.js"></script>
    <script src="js/logout.js"></script>
</body>

</html>