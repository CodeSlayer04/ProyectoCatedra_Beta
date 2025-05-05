<?php
session_start();

// Limpiar todas las variables de sesión
$_SESSION = [];

// Destruir sesión
if (session_destroy()) {
    // Eliminar la cookie de sesión (si existe)
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

} else {
    // Manejar el error en caso de que la destrucción de la sesión falle
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Error al cerrar la sesión"]);
    exit;
}
?>