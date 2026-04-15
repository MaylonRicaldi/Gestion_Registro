<?php
$host = "localhost";
$usuario = "root";       
$password = "";     
$bd = "sistema_documentos";

// Crear conexión
$conn = mysqli_connect($host, $usuario, $password, $bd);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Opcional: establecer codificación UTF-8
mysqli_set_charset($conn, "utf8");
?>