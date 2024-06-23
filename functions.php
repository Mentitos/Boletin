<?php
// functions.php

function getStudentGradesGrouped($student_id, $conn) {
    $sql_grades = "SELECT subjects.id AS subject_id, subjects.name AS subject_name, 
                   GROUP_CONCAT(grades.grade ORDER BY grades.id SEPARATOR ', ') AS grades_list,
                   AVG(grades.grade) AS average_grade
                   FROM grades 
                   JOIN subjects ON grades.subject_id = subjects.id 
                   WHERE grades.student_id = ?
                   GROUP BY subjects.id, subjects.name";
    
    // Preparar la consulta
    $stmt = $conn->prepare($sql_grades);
    $stmt->bind_param("i", $student_id); // i indica que $student_id es un entero
    $stmt->execute();
    $result_grades = $stmt->get_result();
    
    $grades = array();
    while ($row = $result_grades->fetch_assoc()) {
        $grades[$row['subject_name']] = array(
            'grades_list' => $row['grades_list'],
            'average_grade' => round($row['average_grade'], 2) // Redondear promedio a dos decimales
        );
    }
    
    return $grades;
}
?>
