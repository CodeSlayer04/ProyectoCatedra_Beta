<?php



header("Content-Type: application/json");
require_once("../config/conexion.php");

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Eliminar la contraseña del resultado
    unset($usuario['contrasena']);

    echo json_encode($usuario);
    exit;
}

$query = "SELECT id, usuario, nombre, correo, rol FROM usuarios";
$stmt = $db->prepare($query);
$stmt->execute();

$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($resultado);
?>