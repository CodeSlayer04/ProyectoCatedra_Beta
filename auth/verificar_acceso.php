<?php
session_start();

function verificarRol($rolesPermitidos)
{
    if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], $rolesPermitidos)) {
        // Redirige solo si NO estás ya en la página de error
        if (basename($_SERVER['PHP_SELF']) !== 'no-autorizado.php') {
            header("Location: no-autorizado.php");
            exit();
        }
    }
}
?>