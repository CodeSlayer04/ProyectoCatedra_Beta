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
                <input type="text" name="usuario" id="usuario" placeholder="Nombre de usuario" required>
                <input type="text" id="nombre" name="nombre" placeholder="Nombres y Apellidos" required>
                <input type="email" id="correo" name="correo" placeholder="Correo electrónico" required>
                <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
                <button id="btnMostrarContra" type="button">Mostrar 👁️</button>
                <label for="rol">Rol: </label>
                <select id="rol" name="rol" required>
                    <option value="admin">Administrador</option>
                    <option value="empleado">Operador de caja</option>
                </select>
                <button type="submit">Crear</button>
            </form>
        </div>

        <div class="card">
            <h2>Usuarios registrados</h2>
            <div id="lista-usuarios">Cargando...</div>
        </div>
    </main>


    <script src="js/verificarSesion.js"></script>
    <script src="js/logout.js"></script>
    <script src="js/usuarios.js"></script>
</body>

</html>