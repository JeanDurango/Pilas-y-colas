<?php
require_once('config.php'); // Incluye el archivo de configuración de la base de datos

// Obtiene todas las citas pendientes de la base de datos
$result = $conn->query("SELECT * FROM citas WHERE estado = 'pendiente'");

// Crea una cola para almacenar citas pendientes
$colaCitasPendientes = new SplQueue();

// Agrega las citas pendientes a la cola
while ($row = $result->fetch_assoc()) {
    $colaCitasPendientes->enqueue($row);
}

// Crea una pila para almacenar citas aprobadas, en proceso o canceladas
$pilaCitasAprobadas = new SplStack();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCita = $_POST['id'];
    $estado = $_POST['estado'];

    // Actualiza el estado de la cita en la base de datos
    $conn->query("UPDATE citas SET estado = '$estado' WHERE id = $idCita");

    // Si el estado es "aprobada", "en proceso" o "cancelada", agrega la cita a la pila
    if ($estado != 'pendiente') {
        $result = $conn->query("SELECT * FROM citas WHERE id = $idCita");
        $row = $result->fetch_assoc();
        $pilaCitasAprobadas->push($row);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Administrador</title>
</head>
<body>
    <h2>Citas Pendientes</h2>
    <ul>
        <?php
        // Muestra las citas pendientes de la cola
        foreach ($colaCitasPendientes as $cita) {
            echo "<li>{$cita['nombre_cliente']} - {$cita['fecha_cita']} 
            <form action='admin.php' method='post'>
                <input type='hidden' name='id' value='{$cita['id']}'>
                <select name='estado'>
                    <option value='aprobada'>Aprobada</option>
                    <option value='en proceso'>En Proceso</option>
                    <option value='cancelada'>Cancelada</option>
                </select>
                <input type='submit' value='Actualizar'>
            </form>
            </li>";
        }
        ?>
    </ul>

    <h2>Citas Aprobadas, En Proceso o Canceladas</h2>
    <ul>
        <?php
        // Muestra las citas aprobadas, en proceso o canceladas de la pila
        foreach ($pilaCitasAprobadas as $cita) {
            echo "<li>{$cita['nombre_cliente']} - {$cita['fecha_cita']} - {$cita['estado']}</li>";
        }
        ?>
    </ul>
</body>
</html>
