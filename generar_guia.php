<?php
include("conexion.php");

$documentos = $_POST['documentos'] ?? [];

if(count($documentos) < 1){
    echo "Seleccione al menos un documento";
    exit();
}

$ids = implode(",", $documentos);

$sql = "SELECT id, id_despacho, estado FROM documento WHERE id IN ($ids)";
$res = mysqli_query($conn, $sql);

$despacho = null;

while($row = mysqli_fetch_assoc($res)){

    if($row['estado'] != "Pendiente de entrega"){
        echo "Solo documentos pendientes pueden enviarse";
        exit();
    }

    if($despacho == null){
        $despacho = $row['id_despacho'];
    } else {
        if($despacho != $row['id_despacho']){
            echo "Todos deben ser del mismo despacho";
            exit();
        }
    }
}

// GENERAR GUIA
$res2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM guia_remito");
$row2 = mysqli_fetch_assoc($res2);
$numero = $row2['total'] + 1;

$numero_guia = "GUIA-" . str_pad($numero, 4, "0", STR_PAD_LEFT);

mysqli_query($conn, "INSERT INTO guia_remito (numero_guia, fecha, id_despacho)
VALUES ('$numero_guia', CURDATE(), $despacho)");

$id_guia = mysqli_insert_id($conn);

foreach($documentos as $doc){

    mysqli_query($conn, "INSERT INTO detalle_guia (id_guia, id_documento)
    VALUES ($id_guia, $doc)");

    mysqli_query($conn, "UPDATE documento SET estado='Cargo de envio' WHERE id=$doc");

    mysqli_query($conn, "INSERT INTO seguimiento (id_documento, estado)
    VALUES ($doc, 'Cargo de envio')");
}

echo "Guía generada correctamente: " . $numero_guia;
?>