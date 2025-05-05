<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: iniciarSesion.php");
    exit;
}

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
        <h1>Variedades Escobar</h1>
        <nav>
            <a href="dashboardEmpleado.php">Volver</a>
            <button id="btnLogout" type="button">Cerrar Sesión</button>
        </nav>
    </header>
    <main>

        <div class="card">
            <h2 id="bienvenida">No está autorizado a ingresar a esta página</h2>
            <p id="rol">Acceso no autorizado</p>
        </div>

    </main>
    <footer>
        <p>&copy; 2025 Variedades Escobar</p>
    </footer>

    <script src="js/verificarSesion.js"></script>
    <script src="js/logout.js"></script>
</body>

</html>