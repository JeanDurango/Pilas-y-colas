<?php
require_once('config.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreCliente = $_POST['nombre_cliente'];
    $fechaCita = $_POST['fecha_cita'];
    
    // Inserta la cita en la base de datos con estado "pendiente"
    $conn->query("INSERT INTO citas (nombre_cliente, fecha_cita, estado) VALUES ('$nombreCliente', '$fechaCita', 'pendiente')");
    
    echo "Cita solicitada correctamente.";
}
?>
