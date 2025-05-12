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
        <section class="menu navegacion">
            <h1>Registrar venta</h1>
            <nav class="navegacion">
                <a class="boton" href="dashboard.php">Inicio</a>
                <a class="boton activo" href="vender.php">Registrar compra</a>
                <a class="boton" href="productos.php">Gestionar productos</a>
                <a class="boton" href="ventas.php">Informes de venta</a>
                <a class="boton" href="usuarios.php">Gestionar usuarios</a>
                <button class="boton" id="btnLogout" type="button">Cerrar Sesión</button>
            </nav>
        </section>
        <section class="filtros">
            <form id="filtroFormProductos">
                <label for="nombre_nombre">Nombre:</label>
                <input id="filtro_nombre" name="filtro_nombre" type="text" class="texto Nombre-producto"
                    placeholder="Buscar nombre...">

                <label for="filtro_sku">SKU:</label>
                <input id="filtro_sku" name="filtro_sku" type="text" class="texto Nombre-producto"
                    placeholder="Buscar sku...">

                <label for="precio_precio_min">Precio:</label>
                <input id="filtro_precio_min" name="filtro_precio_min" type="number" class="texto preciomin"
                    placeholder="Mín">

                <input id="filtro_precio_max" name="filtro_precio_max" type="number" class="texto preciomax"
                    placeholder="Máx">

                <label for="filtro_categoria">Categoría:</label>
                <select id="filtro_categoria_id" name="filtro_categoria">

                    <!-- Opciones se cargarán dinámicamente -->
                </select>

                <button type="submit">Aplicar filtros</button>

            </form>
            <button type="button" id="limpiarFiltros">Limpiar filtros</button>
        </section>
    </header>

    <section class="cuerpo">
        <section class="card productos">
            <section id="lista-productos">Cargando...</section>
        </section>
        <section class="card carrito">
            <h2>Carrito de compras</h2>
            <div id="carrito-lista"></div>
            <p><strong>Total: $<span id="total">0.00</span></strong></p>
            <label for="metodo_pago">Método de pago:</label>
            <select id="metodo_pago" style="margin-bottom: 0.5rem;">
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
                <option value="otro">Otro</option>
            </select>
            <br>
            <button class="boton" id="btnFinalizarVenta">Finalizar Venta</button>
        </section>
    </section>

    <!--<script src="js/verificarSesion.js"></script>-->
    <script src="js/logout.js"></script>
    <script src="js/vender.js"></script>
</body>

</html>