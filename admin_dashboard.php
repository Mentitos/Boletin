<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

include 'db.php';
include 'functions.php'; // Incluir el archivo de funciones comunes



// Verificar si se ha enviado una búsqueda
$search_dni = '';
$search_class = 'all';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search_dni'])) {
        $search_dni = $_POST['search_dni'];
    }
    if (isset($_POST['search_class'])) {
        $search_class = $_POST['search_class'];
    }
}

// Consulta para obtener la lista de estudiantes y sus datos
$sql = "SELECT students.id, students.name, students.dni, users.username, classes.class_code 
        FROM students 
        JOIN users ON students.user_id = users.id 
        JOIN classes ON students.class_id = classes.id";

// Filtrar por clase si no es "all"
if ($search_class !== 'all') {
    $sql .= " WHERE classes.id = '$search_class'";
    // Agregar la búsqueda por DNI si hay un término de búsqueda
    if ($search_dni !== '') {
        $sql .= " AND students.dni LIKE '$search_dni%' UNION 
                  SELECT students.id, students.name, students.dni, users.username, classes.class_code 
                  FROM students 
                  JOIN users ON students.user_id = users.id 
                  JOIN classes ON students.class_id = classes.id 
                  WHERE classes.id = '$search_class' AND students.dni LIKE '%$search_dni%' AND students.dni NOT LIKE '$search_dni%'";
    }
} else if ($search_dni !== '') {
    // Búsqueda por DNI sin filtrar por clase
    $sql .= " WHERE students.dni LIKE '$search_dni%' UNION 
              SELECT students.id, students.name, students.dni, users.username, classes.class_code 
              FROM students 
              JOIN users ON students.user_id = users.id 
              JOIN classes ON students.class_id = classes.id 
              WHERE students.dni LIKE '%$search_dni%' AND students.dni NOT LIKE '$search_dni%'";
}

$result = $conn->query($sql);

// Consulta para obtener la lista de clases disponibles
$sql_classes = "SELECT id, class_code FROM classes";
$result_classes = $conn->query($sql_classes);

// Función para obtener las notas de un estudiante específico agrupadas por materia

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
            text-align: center;
        }
        .actions {
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px; /* Add gap for spacing */
        }
        .actions a {
            text-decoration: none;
            color: #fff;
            padding: 8px 16px;
            border-radius: 4px;
            background-color: #333;
            transition: background-color 0.3s;
            flex: 1 1 200px; /* Flexible and responsive width */
            box-sizing: border-box; /* Ensure padding is included in the element's total width */
            text-align: center; /* Center the text */
        }
        .actions a:hover {
            background-color: #555;
        }
        .search-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px; /* Add gap for spacing */
        }
        .search-container input[type="text"], .search-container select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 14px;
            flex: 1 1 200px; /* Flexible and responsive width */
            box-sizing: border-box; /* Ensure padding is included in the element's total width */
        }
        .search-container .clear-button {
            cursor: pointer;
            color: #fff;
            font-weight: bold;
            background-color: #f44336;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s;
            flex: 1 1 100px; /* Flexible and responsive width */
            box-sizing: border-box; /* Ensure padding is included in the element's total width */
            text-align: center; /* Center the text */
        }
        .search-container .clear-button:hover {
            background-color: #d32f2f;
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
        .grades {
            max-width: 300px;
        }
        .grades ul {
            padding: 0;
            margin: 0;
        }
        .grades ul li {
            list-style-type: none;
            margin-bottom: 5px;
        }
        .table-actions {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .table-actions a {
            margin-bottom: 10px;
            text-decoration: none;
            color: #fff;
            padding: 8px 16px;
            border-radius: 4px;
            background-color: #333;
            transition: background-color 0.3s;
            width: 150px; /* Ensure the button takes up the full width */
            box-sizing: border-box; /* Ensure padding is included in the element's total width */
            text-align: center; /* Center the text */
        }
        .table-actions a:last-child {
            margin-bottom: 0; /* Remove margin from the last item */
        }
        .table-actions a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido al Dashboard de Administrador</h1>
        <div class="actions">
            <a href="add_student.php">Añadir Estudiante</a>
            <a href="subjects_dashboard.php">Gestionar Materias</a>
            <a href="manage_classes.php">Gestionar Cursos</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
        
        <h2>Buscar Estudiante</h2>
        <div class="search-container">
            <form id="search_form" method="POST" action="">
                <input type="text" id="search_dni" name="search_dni" placeholder="Buscar por DNI" value="<?php echo htmlspecialchars($search_dni); ?>">
                <select id="search_class" name="search_class">
                    <option value="all">Todas las clases</option>
                    <?php
                    if ($result_classes->num_rows > 0) {
                        while ($row_class = $result_classes->fetch_assoc()) {
                            $selected = ($row_class['id'] == $search_class) ? 'selected' : '';
                            echo "<option value='{$row_class['id']}' {$selected}>{$row_class['class_code']}</option>";
                        }
                    }
                    ?>
                </select>
                <span class="clear-button" onclick="clearSearch()">x Limpiar</span>
                <button type="submit">Buscar</button>
            </form>
        </div>
        
        <h2>Lista de Estudiantes</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Curso</th>
                <th>Nombre de Usuario</th>
                <th>DNI</th>
                <th>Notas por Materia</th>
                <th>Promedio por Materia</th>
                <th>Acciones</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Obtener las notas agrupadas del estudiante
                    $student_id = $row['id'];
                    $grades = getStudentGradesGrouped($student_id, $conn);
                    
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['class_code']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['dni']}</td>
                            <td>";
                    
                    // Mostrar las notas agrupadas por materia y promedio
                    if (!empty($grades)) {
                        echo "<div class='grades'>";
                        foreach ($grades as $subject => $data) {
                            echo "<ul><li>{$subject}: {$data['grades_list']}</li></ul>";
                        }
                        echo "</div>";
                    } else {
                        echo "N/A";
                    }
                    
                    echo "</td>
                            <td>";
                    
                    // Mostrar el promedio por materia
                    if (!empty($grades)) {
                        echo "<div class='grades'>";
                        foreach ($grades as $subject => $data) {
                            echo "<ul><li>{$subject}: {$data['average_grade']}</li></ul>";
                        }
                        echo "</div>";
                    } else {
                        echo "N/A";
                    }
                    
                    echo "</td>
                            <td><div class='table-actions'>
                                <a href='edit_student.php?id={$row['id']}'>Editar</a>
                                <a href='manage_grades.php?id={$row['id']}'>Gestionar Notas</a>
                                <a href='delete_student.php?id={$row['id']}' onclick='return confirm(\"¿Estás seguro de eliminar este estudiante?\")'>Eliminar</a>
                                <a href='generate_student_pdf.php?id={$row['id']}'>Generar PDF</a>
                                </div>
                            </td>
                            </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No hay estudiantes registrados</td></tr>";
            }
            ?>
        </table>
    </div>
    <script>
        function clearSearch() {
            document.getElementById('search_dni').value = '';
            document.getElementById('search_class').value = 'all';
            document.getElementById('search_form').submit();
        }
    </script>
</body>
</html>