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
    <title>Atender compra</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <header>
        <h1>Registrar venta</h1>
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
            <h2>Carrito de compras</h2>
            <div id="carrito-lista"></div>
            <p><strong>Total: $<span id="total">0.00</span></strong></p>
            <label for="metodo_pago">Método de pago:</label>
            <select id="metodo_pago">
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
                <option value="otro">Otro</option>
            </select>
            <br><br>
            <button id="btnFinalizarVenta">Finalizar Venta</button>
        </div>

        <div class="card">
            <h2>Productos registrados</h2>
            <div id="lista-productos">Cargando...</div>
        </div>
    </main>

    <!--<script src="js/verificarSesion.js"></script>-->
    <script src="js/logout.js"></script>
    <script src="js/vender.js"></script>
</body>

</html>