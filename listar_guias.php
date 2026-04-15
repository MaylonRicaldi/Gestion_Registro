<?php
include("conexion.php");

$sql = "SELECT g.numero_guia, g.fecha, d.nombre as despacho
FROM guia_remito g
JOIN despacho d ON g.id_despacho = d.id
ORDER BY g.id DESC";

$res = mysqli_query($conn, $sql);

$datos = [];

while($row = mysqli_fetch_assoc($res)){
    $datos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($datos);
?>