<?php
session_start();
if ($_SESSION["role"] !== "student") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
</head>
<body>
    <h1>Bienvenido al Dashboard de Estudiante</h1>
    <p>Aquí puedes ver tus notas, descargar boletines y más.</p>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
