<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Obtener el ID del estudiante desde la URL
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
} else {
    header("Location: admin_dashboard.php");
    exit();
}

// Obtener los datos actuales del estudiante
$sql = "SELECT students.id, students.name, students.dni, users.username, students.class_id 
        FROM students 
        JOIN users ON students.user_id = users.id 
        WHERE students.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Obtener la lista de clases disponibles
$sql_classes = "SELECT id, class_code FROM classes";
$result_classes = $conn->query($sql_classes);

// Manejar la actualización del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $dni = $_POST['dni'];
    $username = $_POST['username'];
    $class_id = $_POST['class_id'];

    // Actualizar los datos del usuario en la tabla users
    $sql_update_user = "UPDATE users 
                        JOIN students ON users.id = students.user_id 
                        SET users.username = ? 
                        WHERE students.id = ?";
    $stmt_update_user = $conn->prepare($sql_update_user);
    $stmt_update_user->bind_param("si", $username, $student_id);
    $stmt_update_user->execute();

    // Actualizar los datos del estudiante en la tabla students
    $sql_update_student = "UPDATE students 
                           SET name = ?, dni = ?, class_id = ? 
                           WHERE id = ?";
    $stmt_update_student = $conn->prepare($sql_update_student);
    $stmt_update_student->bind_param("ssii", $name, $dni, $class_id, $student_id);
    $stmt_update_student->execute();

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estudiante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 10px;
            color: #333;
        }
        input[type="text"], select {
            padding: 8px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        button {
            padding: 10px;
            border-radius: 4px;
            border: none;
            background-color: #333;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Estudiante</h1>
        <form method="POST" action="">
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>

            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" value="<?php echo htmlspecialchars($student['dni']); ?>" required>

            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($student['username']); ?>" required>

            <label for="class_id">Clase:</label>
            <select id="class_id" name="class_id" required>
                <?php
                if ($result_classes->num_rows > 0) {
                    while ($row_class = $result_classes->fetch_assoc()) {
                        $selected = ($row_class['id'] == $student['class_id']) ? 'selected' : '';
                        echo "<option value='{$row_class['id']}' {$selected}>{$row_class['class_code']}</option>";
                    }
                }
                ?>
            </select>

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
