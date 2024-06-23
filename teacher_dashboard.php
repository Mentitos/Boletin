<?php
session_start();
if ($_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
</head>
<body>
    <h1>Bienvenido al Dashboard de Profesor</h1>
    <p>Aquí puedes agregar notas, ver el rendimiento de los estudiantes y más.</p>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
