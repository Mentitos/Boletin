<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $subject_id = $_POST["subject_id"];
    $grade = $_POST["grade"];

    $sql = "INSERT INTO grades (student_id, subject_id, grade) VALUES ('$student_id', '$subject_id', '$grade')";

    if ($conn->query($sql) === TRUE) {
        echo "Nota agregada exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nota</title>
</head>
<body>
    <form method="POST" action="">
        <label for="student_id">ID del Estudiante:</label>
        <input type="text" id="student_id" name="student_id" required><br><br>
        <label for="subject_id">ID de la Materia:</label>
        <input type="text" id="subject_id" name="subject_id" required><br><br>
        <label for="grade">Nota:</label>
        <input type="text" id="grade" name="grade" required><br><br>
        <button type="submit">Agregar Nota</button>
    </form>
</body>
</html>
