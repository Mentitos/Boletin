<?php
session_start();
if ($_SESSION["role"] !== "admin" && $_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (isset($_GET['id']) && isset($_GET['student_id'])) {
    $grade_id = $_GET['id'];
    $student_id = $_GET['student_id'];

    // Eliminar nota
    $sql = "DELETE FROM grades WHERE id='$grade_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Nota eliminada exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Redirigir a la página de gestión de notas
    header("Location: manage_grades.php?id=$student_id");
    exit();
} else {
    echo "ID de nota o estudiante no proporcionado";
}
?>
