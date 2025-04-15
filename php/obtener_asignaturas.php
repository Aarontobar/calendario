<?php
$conexion = new mysqli("localhost", "root", "", "reservas");
if ($conexion->connect_error) {
    die("Error: " . $conexion->connect_error);
}

$sql = "SELECT id, nombre FROM asignaturas";
$resultado = $conexion->query($sql);

$asignaturas = [];

while ($fila = $resultado->fetch_assoc()) {
    $asignaturas[] = $fila;
}

echo json_encode($asignaturas);
$conexion->close();
?>