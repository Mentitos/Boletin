<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar estudiante
    $sql = "DELETE FROM students WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Estudiante eliminado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Redirigir al dashboard
    header("Location: admin_dashboard.php");
    exit();
} else {
    echo "ID de estudiante no proporcionado";
}
?>
