<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Función para limpiar y validar datos ingresados
function cleanInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Agregar nueva clase si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_class'])) {
        $class_code = cleanInput($_POST['class_code']);

        // Validar y agregar la nueva clase a la base de datos
        if (!empty($class_code)) {
            $sql_add_class = "INSERT INTO classes (class_code) VALUES (?)";
            $stmt = $conn->prepare($sql_add_class);
            $stmt->bind_param("s", $class_code);
            $stmt->execute();
            $stmt->close();

            header("Location: manage_classes.php");
            exit();
        }
    }

    // Procesar la solicitud de actualización de clase si se ha enviado el formulario de edición
    if (isset($_POST['edit_class'])) {
        $class_id = $_POST['class_id'];
        $new_class_code = cleanInput($_POST['new_class_code']);

        // Validar y actualizar la clase en la base de datos
        if (!empty($class_id) && !empty($new_class_code)) {
            $sql_update_class = "UPDATE classes SET class_code = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update_class);
            $stmt->bind_param("si", $new_class_code, $class_id);
            $stmt->execute();
            $stmt->close();

            header("Location: manage_classes.php");
            exit();
        }
    }

    // Procesar la solicitud de eliminación de clase si se ha enviado el formulario de eliminación
    if (isset($_POST['delete_class'])) {
        $class_id = $_POST['id'];

        // Eliminar la clase de la base de datos
        if (!empty($class_id)) {
            $sql_delete_class = "DELETE FROM classes WHERE id = ?";
            $stmt = $conn->prepare($sql_delete_class);
            $stmt->bind_param("i", $class_id);
            $stmt->execute();
            $stmt->close();

            header("Location: manage_classes.php");
            exit();
        }
    }
}

// Consulta para obtener la lista de clases
$sql = "SELECT id, class_code FROM classes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gestionar Clases</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
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
        h1, h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-container {
            margin-top: 20px;
        }
        .form-container input[type="text"] {
            width: 200px;
            padding: 5px;
            margin-right: 10px;
        }
        .form-container button {
            padding: 8px 16px;
            cursor: pointer;
        }
        .form-edit {
            display: none; /* Formulario de edición inicialmente oculto */
        }
    </style>
    <script>
        // Función para mostrar el formulario de edición y ocultar el de adición
        function showEditForm(classId, currentClassCode) {
            // Llenar el formulario de edición con los datos actuales de la clase
            document.getElementById('edit_class_id').value = classId;
            document.getElementById('edit_class_code').value = currentClassCode;

            // Ocultar el formulario de adición y mostrar el de edición
            document.getElementById('add_class_form').style.display = 'none';
            document.getElementById('edit_class_form').style.display = 'block';

            // Desplazarse hacia arriba de la página
            window.scrollTo(0, 0);
        }

        // Función para mostrar el formulario de adición y ocultar el de edición
        function showAddForm() {
            // Limpiar los campos del formulario de adición
            document.getElementById('class_code').value = '';

            // Mostrar el formulario de adición y ocultar el de edición
            document.getElementById('edit_class_form').style.display = 'none';
            document.getElementById('add_class_form').style.display = 'block';
        }

        // Función para mostrar una alerta de confirmación antes de eliminar una clase
        function confirmDelete() {
            return confirm('¿Estás seguro de que deseas eliminar esta clase?');
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Gestionar Clases</h1>
        <a href="admin_dashboard.php">Volver al Dashboard</a>
        
        <!-- Formulario de Añadir Nueva Clase -->
        <div class="form-container" id="add_class_form">
            <h2>Agregar Nueva Clase</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="text" id="class_code" name="class_code" placeholder="Código de Clase" required>
                <button type="submit" name="add_class">Agregar Clase</button>
            </form>
        </div>
        
        <!-- Formulario de Editar Clase -->
        <div class="form-container form-edit" id="edit_class_form">
            <h2>Editar Clase</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" id="edit_class_id" name="class_id" value="">
                <input type="text" id="edit_class_code" name="new_class_code" placeholder="Nuevo Código de Clase" required>
                <button type="submit" name="edit_class">Guardar Cambios</button>
            </form>
        </div>
        
        <h2>Lista de Clases</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Código de Clase</th>
                <th>Acciones</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['class_code']}</td>
                            <td>
                                <button onclick=\"showEditForm('{$row['id']}', '{$row['class_code']}')\">Editar</button>
                                <form method='POST' action='' style='display:inline;' onsubmit='return confirmDelete();'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' name='delete_class'>Eliminar</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No hay clases registradas</td></tr>";
            }
            ?>
        </table>
        
    </div>
</body>
</html>
