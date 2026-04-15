<?php
include("conexion.php");

$id = $_POST['id'] ?? '';
$estado = $_POST['estado'] ?? '';

// VALIDACIÓN
if (empty($id) || empty($estado)) {
    echo "Error: datos vacíos";
    exit();
}

// 🔥 OBTENER ESTADO ACTUAL
$stmt = $conn->prepare("SELECT estado FROM documento WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if(!$row){
    echo "Documento no encontrado";
    exit();
}

$estado_actual = $row['estado'];

// 🔥 REGLAS DE FLUJO
$valido = false;

if($estado_actual == "Pendiente de entrega"){
    if($estado == "Cargo de envio" || $estado == "No recepcionado"){
        $valido = true;
    }
}

if($estado_actual == "Cargo de envio"){
    if($estado == "Cargo devuelto entregado"){
        $valido = true;
    }
}

// 🔥 BLOQUEO
if(!$valido){
    echo "No permitido: de '$estado_actual' a '$estado'";
    exit();
}

// =============================
// ACTUALIZAR
// =============================
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