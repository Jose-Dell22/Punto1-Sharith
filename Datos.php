<?php

$host = "localhost";
$user = "root";
$password = "Su contraseña de la DB"; 
$db = "Aqui pones el nombre de la base de datos";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

require_once("Empleado.php");

$empleado = new Empleado($conn, "Carlos", 2000000, 5, 3, 2, 2);
$empleado->guardar();
$empleado->imprimirResumenPago();

$conn->close();

?>
