<?php
session_start();
if ($_SESSION["role"] !== "admin" && $_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}

include 'db.php';

$student_id = $_GET['id'];

// Obtener todas las materias
$sql_subjects = "SELECT id, name FROM subjects";
$result_subjects = $conn->query($sql_subjects);

$sql_grades = "SELECT students.name AS student_name, subjects.name AS subject_name, grades.id AS grade_id, grades.grade 
              FROM grades 
              JOIN students ON grades.student_id = students.id 
              JOIN subjects ON grades.subject_id = subjects.id 
              WHERE students.id='$student_id'";
$result_grades = $conn->query($sql_grades);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id = $_POST["subject_id"];
    $grade = $_POST["grade"];

    $sql_insert = "INSERT INTO grades (student_id, subject_id, grade) VALUES ('$student_id', '$subject_id', '$grade')";
    if ($conn->query($sql_insert) === TRUE) {
        echo "Nota añadida exitosamente";
        // Redirigir para evitar el reenvío del formulario
        header("Location: manage_grades.php?id=$student_id");
        exit();
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Notas</title>
</head>
<body>
    <h1>Gestionar Notas para <?php echo $student_id; ?></h1>
    <h2>Añadir Nota</h2>
    <form method="POST" action="">
        <label for="subject_id">Materia:</label>
        <select id="subject_id" name="subject_id" required>
            <option value="">Selecciona una materia</option>
            <?php
            if ($result_subjects->num_rows > 0) {
                while($row = $result_subjects->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                }
            } else {
                echo "<option value=''>No hay materias disponibles</option>";
            }
            ?>
        </select><br><br>
        <label for="grade">Nota:</label>
        <input type="text" id="grade" name="grade" required><br><br>
        <button type="submit">Añadir Nota</button>
    </form>
    <h2>Notas Existentes</h2>
    <table border="1">
        <tr>
            <th>Materia</th>
            <th>Nota</th>
            <th>Acciones</th>
        </tr>
        <?php
        if ($result_grades->num_rows > 0) {
            while($row = $result_grades->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['subject_name']}</td>
                        <td>{$row['grade']}</td>
                        <td><a href='delete_grade.php?id={$row['grade_id']}&student_id={$student_id}'>Eliminar</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay notas registradas</td></tr>";
        }
        ?>
    </table>
    <a href="admin_dashboard.php">Volver al Dashboard</a>
</body>
</html>
