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
        <h1>Historial de ventas</h1>
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

        <h2>Filtrar ventas</h2>
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

            <button type="submit">Aplicar filtros</button>
        </form>

        <hr>
<div class="card">
  <h2 class="titulo-ventas">Listado de ventas realizadas</h2>

  <div class="carrusel-wrapper">
    <button class="carrusel-btn prev-btn">‹</button>
    
    <div class="venta-carrusel">
      <div id="venta-slides" class="venta-slides">
        <!-- Aquí se insertan las tarjetas desde JS -->
      </div>
    </div>
    
    <button class="carrusel-btn next-btn">›</button>
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