<?php
include("conexion.php");

$sql = "SELECT 
d.id,
d.codigo,
d.tipo,
d.fecha_recepcion,
d.remitente,
dp.nombre AS despacho,
d.estado
FROM documento d
JOIN despacho dp ON d.id_despacho = dp.id
ORDER BY d.id DESC";

$result = mysqli_query($conn, $sql);

$datos = [];

while($row = mysqli_fetch_assoc($result)){
    $datos[] = $row;
}

// DEVOLVER JSON
header('Content-Type: application/json');
echo json_encode($datos);
?>