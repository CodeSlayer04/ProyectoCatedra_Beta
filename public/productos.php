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
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <header>
        <h1>Gestión de Productos</h1>
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
            <h2>Crear nueva categoría</h2>
            <form id="form-categorias-create">
                <input type="text" name="nombre" id="nombreCat" placeholder="Nombre de categoría" required>
                <textarea name="descripcion" id="descripcionCat" placeholder="Descripción"></textarea>
                <button type="submit">Guardar</button>
            </form>
        </div>

        <!-- <div class="card">
            <h2>Categorías registradas</h2>
            <div id="lista-categorias">Cargando...</div>
        </div> -->


        <div class="card">
            <h2>Agregar nuevo producto</h2>
            <form id="form-productos-create">
                <input type="text" id="nombre" name="nombre" placeholder="Nombre del producto" required>
                <input type="text" name="descripcion" id="descripcion" placeholder="Detalles">
                <input type="text" id="sku" name="sku" placeholder="SKU" required>
                <input type="number" id="precio_compra" name="precio_compra" placeholder="Precio de compra" required
                    step="0.01">
                <input type="number" id="precio_venta" name="precio_venta" placeholder="Precio de venta" required
                    step="0.01">
                <input type="number" id="stock" name="stock" placeholder="Stock" required>
                <br>
                <input type="checkbox" name="activarFecha" id="activarFecha">
                <label for="activarFecha">Tiene caducidad</label>
                <input type="date" id="fecha_caducidad" name="fecha_caducidad" style="visibility:hidden;">
                <select id="categoria_id" name="categoria_id" required>
                    <!-- Opciones se cargarán dinámicamente -->
                </select>
                <input type="file" id="imagen" name="imagen" accept="image/*">
                <button type="submit">Agregar</button>
            </form>
        </div>

        <div class="card">
            <h2>Productos registrados</h2>
            <div id="lista-productos">Cargando...</div>
        </div>
    </main>

    <script src="js/productos.js"></script>
    <script src="js/verificarSesion.js"></script>
    <script src="js/logout.js"></script>
</body>

</html>