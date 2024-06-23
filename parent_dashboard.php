<?php
session_start();
if ($_SESSION["role"] !== "parent") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
</head>
<body>
    <h1>Bienvenido al Dashboard de Padre</h1>
    <p>Aquí puedes ver las notas de tus hijos, contactar con los profesores y más.</p>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
