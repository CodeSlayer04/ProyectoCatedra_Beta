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
    <title>Historial de ventas</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/ventas.css">
</head>

<body>
    <header>

        <section class="menu navegacion">
            <h1>Historial de ventas</h1>
            <nav class="navegacion">
                <a class="boton" href="dashboard.php">Inicio</a>
                <a class="boton" href="vender.php">Registrar compra</a>
                <a class="boton" href="productos.php">Gestionar productos</a>
                <a class="boton activo" href="ventas.php">Informes de venta</a>
                <a class="boton" href="usuarios.php">Gestionar usuarios</a>
                <button class="boton" id="btnLogout" type="button">Cerrar Sesión</button>
            </nav>
        </section>
    </header>

    <main>

        <h2 id="titulo">Filtrar ventas</h2>
        <form id="filtroForm">
            <label for="fecha_inicio">Desde:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio">

            <label for="fecha_fin">Hasta:</label>
            <input type="date" id="fecha_fin" name="fecha_fin">

            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" placeholder="Nombre del usuario">

            <label for="metodo_pago">Método de pago:</label>
            <select id="metodo_pago" name="metodo_pago">
                <option value="">Todos</option>
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
            </select>
            <label for="venta_id">ID:</label>
            <input type="number" id="venta_id" name="venta_id" placeholder="ID de venta">

            <button type="submit">Aplicar filtros</button>
            <button type="button" id="limpiarFiltros">Limpiar filtros</button>
        </form>


        <div style="display: flex; justify-content: center; margin-top: 2rem;">
            <button id="btnGenerarInforme">Generar informe de ventas</button>
        </div>

        <hr>
        <div class="detalle-card">
            <h2 id="titulo-ventas">Listado de ventas realizadas</h2>
            <div id="venta-slides">
            </div>
            <div id="historial-ventas" style="margin-top: 1rem; color: red;">
            </div>
        </div>


        </div>

        <!-- Sidebar fuera de la tarjeta -->
        <div id="sidebar-detalles" class="sidebar"></div>
        </div>



    </main>


    <script src="js/verificarSesion.js"></script>
    <script src="js/logout.js"></script>
    <script src="js/ventas.js"></script>
</body>

</html>