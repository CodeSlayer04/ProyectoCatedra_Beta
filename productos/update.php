<?php
// POST: Actualiza un producto desde un formulario
header("Content-Type: application/json");
require_once("../config/conexion.php");

// Usamos $_POST para acceder a los datos del formulario
if (
    isset($_POST['id']) &&
    !empty($_POST['sku']) && !empty($_POST['nombre']) &&
    isset($_POST['precio_compra']) && isset($_POST['precio_venta']) &&
    isset($_POST['stock'])
) {

    $id = $_POST['id'];
    $sku = $_POST['sku'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'] ?? '';
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $stock = $_POST['stock'];
    $categoria_id = $_POST['categoria_id'] ?? null;
    $fecha_caducidad = $_POST['fecha_caducidad'] ?? null;

    $imagen_nombre = null;

    // Manejo de la subida de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        $nombre_original = basename($_FILES['imagen']['name']);
        $ext = pathinfo($nombre_original, PATHINFO_EXTENSION);
        $imagen_nombre = uniqid("img_") . "." . $ext;
        // Ruta absoluta al directorio de imágenes
        $ruta_destino = realpath(__DIR__ . '/../public/imagenes') . '/' . $imagen_nombre;

        // Mover la imagen al destino
        if (!move_uploaded_file($imagen_tmp, $ruta_destino)) {
            echo json_encode(["error" => "No se pudo guardar la imagen."]);
            exit;
        }
    }

    $database = new Database();
    $db = $database->getConnection();

    $query = "UPDATE productos SET sku = :sku, nombre = :nombre, descripcion = :descripcion,
    precio_compra = :precio_compra, precio_venta = :precio_venta,
    stock = :stock, categoria_id = :categoria_id, fecha_caducidad = :fecha_caducidad";

    if ($imagen_nombre) {
        $query .= ", imagen = :imagen";
    }

    $query .= " WHERE id = :id";


    $stmt = $db->prepare($query);

    // Asociamos los parámetros usando $_POST
    $stmt = $db->prepare($query);
    $stmt->bindParam(':sku', $sku);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':precio_compra', $precio_compra);
    $stmt->bindParam(':precio_venta', $precio_venta);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':categoria_id', $categoria_id);
    $stmt->bindParam(':fecha_caducidad', $fecha_caducidad);
    $stmt->bindParam(':id', $id);

    if ($imagen_nombre) {
        $stmt->bindParam(':imagen', $imagen_nombre);
    }

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Producto actualizado correctamente."]);
    } else {
        echo json_encode(["error" => "No se pudo actualizar el producto."]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos."]);
}
?>