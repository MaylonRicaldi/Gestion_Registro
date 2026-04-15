<?php
require 'vendor/autoload.php';
include("conexion.php");

use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_FILES['archivo']['tmp_name'])){

    $archivo = $_FILES['archivo']['tmp_name'];

    $excel = IOFactory::load($archivo);
    $hoja = $excel->getActiveSheet();
    $filas = $hoja->toArray();

    $insertados = 0;
    $duplicados = 0;

    for($i = 1; $i < count($filas); $i++){

        $numero = trim($filas[$i][0]);
        $tipo = trim($filas[$i][1]);
        $fechaExcel = $filas[$i][2];
        $remitente = trim($filas[$i][3]);
        $despacho = trim($filas[$i][4]);

        // =========================
        // FECHA
        // =========================
        if (is_numeric($fechaExcel)) {
            $fecha = date("Y-m-d", strtotime("1899-12-30 +$fechaExcel days"));
        } else {
            $fecha = date("Y-m-d", strtotime($fechaExcel));
        }

        if($numero=="" || $tipo=="" || $fecha=="" || $remitente=="" || $despacho==""){
            continue;
        }

        $codigo = "DOC00" . $numero;

        // =========================
        // 🔥 VALIDAR DUPLICADO BD
        // =========================
        $verificar = mysqli_query($conn, "SELECT id FROM documento WHERE codigo='$codigo'");

        if(mysqli_num_rows($verificar) > 0){
            $duplicados++;
            continue;
        }

        // =========================
        // INSERTAR DOCUMENTO
        // =========================
        $sql = "INSERT INTO documento 
        (codigo, tipo, fecha_recepcion, remitente, id_despacho, estado)
        VALUES 
        ('$codigo', '$tipo', '$fecha', '$remitente', '$despacho', 'Pendiente de entrega')";

        if(mysqli_query($conn, $sql)){

            $id = mysqli_insert_id($conn);

            mysqli_query($conn, "INSERT INTO seguimiento (id_documento, estado)
            VALUES ($id, 'Pendiente de entrega')");

            $insertados++;
        }
    }

    // =========================
    // MENSAJE FINAL
    // =========================
    echo "<script>
        alert('✅ Importación terminada\\nInsertados: $insertados\\nDuplicados: $duplicados');
        window.location='index.html';
    </script>";

}else{
    echo "Error al subir archivo";
}
?>