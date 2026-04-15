<?php
include("conexion.php");

$id = $_POST['id'] ?? '';
$estado = $_POST['estado'] ?? '';

// VALIDACIÓN
if (empty($id) || empty($estado)) {
    echo "Error: datos vacíos";
    exit();
}

// PREPARAR CONSULTA (más seguro)
$stmt = $conn->prepare("UPDATE documento SET estado=? WHERE id=?");
$stmt->bind_param("si", $estado, $id);

if ($stmt->execute()) {

    // INSERTAR EN SEGUIMIENTO
    $stmt2 = $conn->prepare("INSERT INTO seguimiento (id_documento, estado) VALUES (?, ?)");
    $stmt2->bind_param("is", $id, $estado);
    $stmt2->execute();

    echo "ok";

} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>