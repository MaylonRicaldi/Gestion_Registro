<?php
include("conexion.php");

$sql = "SELECT 
s.id,
d.codigo,
s.estado,
s.fecha
FROM seguimiento s
JOIN documento d ON s.id_documento = d.id
ORDER BY s.id DESC";

$result = mysqli_query($conn, $sql);

$data = [];

while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>