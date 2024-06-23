<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Consulta para obtener las clases disponibles
$sql_classes = "SELECT id, class_code FROM classes";
$result_classes = $conn->query($sql_classes);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $class_id = $_POST['class_id'];
    $dni = $_POST['dni'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insertar usuario en la tabla users
    $sql_user = "INSERT INTO users (username, password, role) VALUES (?, ?, 'student')";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("ss", $username, $password);
    $stmt_user->execute();
    $user_id = $stmt_user->insert_id;

    // Insertar estudiante en la tabla students
    $sql_student = "INSERT INTO students (name, class_id, dni, user_id) VALUES (?, ?, ?, ?)";
    $stmt_student = $conn->prepare($sql_student);
    $stmt_student->bind_param("sisi", $name, $class_id, $dni, $user_id);

    if ($stmt_student->execute()) {
        echo "Estudiante añadido correctamente.";
    } else {
        echo "Error: " . $stmt_student->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Estudiante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"],
        select {
            width: calc(100% - 12px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Añadir Estudiante</h1>
        <form method="POST" action="">
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="class_id">Curso:</label>
            <select id="class_id" name="class_id" required>
                <?php
                if ($result_classes->num_rows > 0) {
                    while ($row_class = $result_classes->fetch_assoc()) {
                        echo "<option value='{$row_class['id']}'>{$row_class['class_code']}</option>";
                    }
                }
                ?>
            </select><br>

            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" pattern="\d{8}" required><br>

            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required><br>

            <button type="submit">Añadir Estudiante</button>
        </form>
    </div>
</body>
</html>
