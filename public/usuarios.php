<?php
//session_start();

require_once('../auth/verificar_acceso.php');

if (!isset($_SESSION['usuario'])) {
    header("Location: iniciarSesion.php");
    exit;
}

verificarRol(['admin']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <header>
        <h1>Gestión de usuarios</h1>
        <nav>
            <a href="dashboard.php">Inicio</a>
            <a href="vender.php">Registrar compra</a>
            <a href="productos.php">Gestionar productos</a>
            <a href="ventas.php">Informes de venta</a>
            <a href="usuarios.php">Gestionar usuarios</a>
            <button id="btnLogout" type="button">Cerrar Sesión</button>
        </nav>
    </header>

    <main>


        <div class="card">
            <h2>Agregar nuevo usuario</h2>
            <form id="form-usuarios-create">

            </form>
        </div>

        <div class="card">
            <h2>Usuarios registrados</h2>
            <div id="lista-productos">Cargando...</div>
        </div>
    </main>


    <script src="js/verificarSesion.js"></script>
</body>

</html>