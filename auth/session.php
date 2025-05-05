<?php
session_start();
header("Content-Type: application/json");

$session_duration = 600; // 10 minutos

if (isset($_SESSION['usuario'])) {
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_duration)) {
        // Sesión expirada
        session_unset();
        session_destroy();
        echo json_encode(['error' => 'Sesión expirada']);
    } else {
        // Actualizamos la última actividad
        $_SESSION['LAST_ACTIVITY'] = time();
        echo json_encode(['usuario' => $_SESSION['usuario']]);
    }
} else {
    echo json_encode(["error" => "No autenticado"]);
}
?>