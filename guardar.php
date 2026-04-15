<?php
include("conexion.php");

// RECIBIR DATOS
$numero = $_POST['numero'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$remitente = $_POST['remitente'] ?? '';
$despacho = $_POST['despacho'] ?? '';

// ================= VALIDACIONES =================

// CAMPOS VACÍOS
if($numero=="" || $tipo=="" || $fecha=="" || $remitente=="" || $despacho==""){
    echo "<script>alert('⚠️ Todos los campos son obligatorios'); window.location='index.html';</script>";
    exit();
}

// VALIDAR NÚMERO
if(!is_numeric($numero) || $numero < 1 || $numero > 9){
    echo "<script>alert('⚠️ Número inválido (1-9)'); window.location='index.html';</script>";
    exit();
}

// GENERAR CÓDIGO → DOC00X
$codigo = "DOC00" . $numero;

// VALIDAR DUPLICADO
$verificar = mysqli_query($conn, "SELECT id FROM documento WHERE codigo='$codigo'");
if(mysqli_num_rows($verificar) > 0){
    echo "<script>alert('⚠️ El código ya existe'); window.location='index.html';</script>";
    exit();
}

// VALIDAR TIPO
$tipos_validos = ["Oficio","Carta","Memorando","Informe"];
if(!in_array($tipo, $tipos_validos)){
    echo "<script>alert('⚠️ Tipo inválido'); window.location='index.html';</script>";
    exit();
}

// VALIDAR FECHA
if(!strtotime($fecha)){
    echo "<script>alert('⚠️ Fecha inválida'); window.location='index.html';</script>";
    exit();
}

// VALIDAR REMITENTE
if(!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/", $remitente)){
    echo "<script>alert('⚠️ Remitente inválido'); window.location='index.html';</script>";
    exit();
}

// VALIDAR DESPACHO
$despachos_validos = [1,2,3,4];
if(!in_array($despacho, $despachos_validos)){
    echo "<script>alert('⚠️ Despacho inválido'); window.location='index.html';</script>";
    exit();
}

// ================= INSERT =================

$sql = "INSERT INTO documento 
(codigo, tipo, fecha_recepcion, remitente, id_despacho, estado)
VALUES 
('$codigo', '$tipo', '$fecha', '$remitente', '$despacho', 'Pendiente de entrega')";

if(mysqli_query($conn, $sql)){

    $id = mysqli_insert_id($conn);

    mysqli_query($conn, "INSERT INTO seguimiento (id_documento, estado)
    VALUES ($id, 'Pendiente de entrega')");

    header("Location: index.html");

} else {
    echo "Error: " . mysqli_error($conn);
}
?>