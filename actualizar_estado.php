<?php
include("conexion.php");

$id = $_POST['id'] ?? '';
$estado = $_POST['estado'] ?? '';

if($id == "" || $estado == ""){
    echo "Error: datos vacíos";
    exit();
}

// ACTUALIZAR
$sql = "UPDATE documento SET estado='$estado' WHERE id=$id";

if(mysqli_query($conn, $sql)){

    mysqli_query($conn, "INSERT INTO seguimiento (id_documento, estado)
    VALUES ($id, '$estado')");

    echo "ok";

} else {
    echo "Error: " . mysqli_error($conn);
}
?>