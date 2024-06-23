<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

include 'db.php';

$sql = "SELECT id, name FROM subjects";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_subject"])) {
    $name = $_POST["name"];
    $sql = "INSERT INTO subjects (name) VALUES ('$name')";
    if ($conn->query($sql) === TRUE) {
        header("Location: subjects_dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save_edit"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $sql = "UPDATE subjects SET name='$name' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: subjects_dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_subject"])) {
    $id = $_POST["id"];
    $sql = "DELETE FROM subjects WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: subjects_dashboard.php");
        exit();
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
    <title>Gestionar Materias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        h2 {
            color: #555;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 22px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #66afe9;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        .edit-form {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestionar Materias</h1>

        <!-- Formulario para añadir materia -->
        <h2>Añadir Materia</h2>
        <form method="POST" action="">
            <input type="hidden" name="add_subject" value="1">
            <label for="name">Nombre de la Materia:</label>
            <input type="text" id="name" name="name" required>
            <button type="submit">Añadir Materia</button>
        </form>

        <!-- Lista de materias -->
        <h2>Lista de Materias</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='button' onclick='editSubject({$row['id']}, \"{$row['name']}\")'>Editar</button>
                                    <button type='submit' name='delete_subject' value='1' onclick='return confirm(\"¿Estás seguro de eliminar esta materia?\")'>Eliminar</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No hay materias registradas</td></tr>";
            }
            ?>
        </table>

        <!-- Formulario de edición oculto -->
        <div id="editForm" class="edit-form">
            <h2>Editar Materia</h2>
            <form method="POST" action="">
                <input type="hidden" name="save_edit" value="1">
                <input type="hidden" id="editId" name="id">
                <label for="editName">Nombre de la Materia:</label>
                <input type="text" id="editName" name="name" required>
                <button type="submit">Guardar Cambios</button>
                <button type="button" onclick="cancelEdit()">Cancelar</button>
            </form>
        </div>

        <a href="admin_dashboard.php">Volver al Dashboard</a>
    </div>

    <script>
        function editSubject(id, name) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editForm').style.display = 'block';
            window.scrollTo(0, document.body.scrollHeight);
        }

        function cancelEdit() {
            document.getElementById('editForm').style.display = 'none';
        }
    </script>
</body>
</html>
