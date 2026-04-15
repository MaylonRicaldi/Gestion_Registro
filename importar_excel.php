<?php
require 'vendor/autoload.php';
include("conexion.php");

use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_FILES['archivo']['tmp_name'])){

    $archivo = $_FILES['archivo']['tmp_name'];

    $excel = IOFactory::load($archivo);
    $hoja = $excel->getActiveSheet();
    $filas = $hoja->toArray();

    for($i = 1; $i < count($filas); $i++){

        $numero = $filas[$i][0];
        $tipo = $filas[$i][1];
        $fecha = $filas[$i][2];
        $remitente = $filas[$i][3];
        $despacho = $filas[$i][4];

        if($numero=="" || $tipo=="" || $fecha=="" || $remitente=="" || $despacho==""){
            continue;
        }

        $codigo = "DOC00" . $numero;

        $verificar = mysqli_query($conn, "SELECT id FROM documento WHERE codigo='$codigo'");
        if(mysqli_num_rows($verificar) > 0){
            continue;
        }

        $sql = "INSERT INTO documento 
        (codigo, tipo, fecha_recepcion, remitente, id_despacho, estado)
        VALUES 
        ('$codigo', '$tipo', '$fecha', '$remitente', '$despacho', 'Pendiente de entrega')";

        if(mysqli_query($conn, $sql)){
            $id = mysqli_insert_id($conn);

            mysqli_query($conn, "INSERT INTO seguimiento (id_documento, estado)
            VALUES ($id, 'Pendiente de entrega')");
        }
    }

    echo "<script>alert('✅ Excel importado correctamente'); window.location='index.html';</script>";

}else{
    echo "Error al subir archivo";
}
?>