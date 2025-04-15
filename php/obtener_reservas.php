<?php
$conexion = new mysqli("localhost", "root", "", "reservas");
if ($conexion->connect_error) {
    die("Error: " . $conexion->connect_error);
}

$sql = "SELECT r.*, a.nombre AS asignatura, a.color 
        FROM reservas r
        JOIN asignaturas a ON r.asignatura_id = a.id";

$resultado = $conexion->query($sql);

$eventos = [];

while ($fila = $resultado->fetch_assoc()) {
    $eventos[] = [
        'title' => $fila['asignatura'] . ' - ' . $fila['profesor'],
        'start' => $fila['fecha'] . 'T' . $fila['hora_inicio'],
        'end' => $fila['fecha'] . 'T' . $fila['hora_fin'],
        'color' => $fila['color']
    ];
}

echo json_encode($eventos);
$conexion->close();
?>