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
  <link rel="stylesheet" href="css/EstiloUsuario.css">
</head>

<body>
  <header>
    <section class="menu navegacion">
      <h1>Gestión de usuarios</h1>
      <nav class="navegacion">
        <a class="boton" href="dashboard.php">Inicio</a>
        <a class="boton" href="vender.php">Registrar compra</a>
        <a class="boton" href="productos.php">Gestionar productos</a>
        <a class="boton" href="ventas.php">Informes de venta</a>
        <a class="boton activo" href="usuarios.php">Gestionar usuarios</a>
        <button class="boton" id="btnLogout" type="button">Cerrar Sesión</button>
      </nav>
    </section>
  </header>

  <main class="container">
    <!-- Columna izquierda: Tarjetas de usuarios -->
    <div class="left-column">
      <div class="card-productos">
        <h1 class="titu1">Usuarios registrados</h1>
        <div id="lista-usuarios">
          <!-- Aquí se insertarán las tarjetas con JavaScript -->
        </div>
      </div>
    </div>

    <!-- Columna derecha: Formulario de crear usuario -->
    <div class="right-column">
      <div class="card-form">
        <h2>Agregar nuevo usuario</h2>
        <form id="form-usuarios-create">
          <input type="text" name="usuario" id="usuario" placeholder="Nombre de usuario" required>
          <input type="text" id="nombre" name="nombre" placeholder="Nombres y Apellidos" required>
          <input type="email" id="correo" name="correo" placeholder="Correo electrónico" required>
          <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
          <button id="btnMostrarContra" type="button">Mostrar contraseña</button>

          <label for="rol">Rol:</label><br>
          <select id="rol" name="rol" required>
            <option value="admin">Administrador</option>
            <option value="empleado">Operador de caja</option>
          </select>

          <button class="crear" type="submit">Crear</button>
        </form>
      </div>
    </div>
  </main>
  <script src="js/verificarSesion.js"></script>
  <script src="js/logout.js"></script>
  <script src="js/usuarios.js"></script>
</body>

</html>