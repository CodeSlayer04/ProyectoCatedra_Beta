<?php
//POST: agregar un nuevo producto
header("Content-Type: application/json");
require_once("../config/conexion.php");

//$data = json_decode(file_get_contents("php://input"), true);

if (
    !empty($_POST['sku']) && !empty($_POST['nombre']) &&
    isset($_POST['precio_compra']) && isset($_POST['precio_venta']) &&
    isset($_POST['stock'])
) {
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

    $query = "INSERT INTO productos (sku, nombre, descripcion, precio_compra, precio_venta, stock, imagen, categoria_id, fecha_caducidad)
              VALUES (:sku, :nombre, :descripcion, :precio_compra, :precio_venta, :stock, :imagen, :categoria_id, :fecha_caducidad)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":sku", $sku);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":descripcion", $descripcion);
    $stmt->bindParam(":precio_compra", $precio_compra);
    $stmt->bindParam(":precio_venta", $precio_venta);
    $stmt->bindParam(":stock", $stock);
    $stmt->bindParam(":imagen", $imagen_nombre);
    $stmt->bindParam(":categoria_id", $categoria_id);
    $stmt->bindParam(":fecha_caducidad", $fecha_caducidad);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Producto creado correctamente."]);

    } else {
        echo json_encode(["error" => "No se pudo crear el producto."]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos."]);
}
?>