<?php
$conexion = new mysqli("localhost", "root", "", "reservas");
if ($conexion->connect_error) {
    die("Error: " . $conexion->connect_error);
}

$profesor = $_POST['profesor'];
$fecha = $_POST['fecha'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$asignatura_id = $_POST['asignatura_id'];

$sql = "INSERT INTO reservas (fecha, hora_inicio, hora_fin, profesor, asignatura_id)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssi", $fecha, $hora_inicio, $hora_fin, $profesor, $asignatura_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Reserva guardada correctamente";
} else {
    echo "Error al guardar la reserva";
}

$stmt->close();
$conexion->close();
?>