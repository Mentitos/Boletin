<?php
include 'db.php';

$sql = "SELECT students.name AS student_name, subjects.name AS subject_name, grades.grade 
        FROM grades 
        JOIN students ON grades.student_id = students.id 
        JOIN subjects ON grades.subject_id = subjects.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Notas</title>
</head>
<body>
    <h1>Notas</h1>
    <table border="1">
        <tr>
            <th>Estudiante</th>
            <th>Materia</th>
            <th>Nota</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>".$row["student_name"]."</td><td>".$row["subject_name"]."</td><td>".$row["grade"]."</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay notas registradas</td></tr>";
        }
        ?>
    </table>
</body>
</html>
