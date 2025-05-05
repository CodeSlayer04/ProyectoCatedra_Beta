<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Iniciar Sesión - Variedades Escobar</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="card" id="login-container">
        <h2>Iniciar Sesión</h2>
        <div id="alerta"></div>


        <form id="formulario-login">
            <input type="text" name="usuario" placeholder="Usuario" required />
            <input type="password" name="contrasena" placeholder="Contraseña" required />
            <button type="submit">Entrar</button>
        </form>
        <!--Mensaje de error al iniciar sesión-->
        <div class="message" id="mensaje">

        </div>
    </div>

    <script src="js/login.js"></script>
</body>

</html>