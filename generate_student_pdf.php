<?php
require('fpdf/fpdf.php');
include 'db.php';
include 'functions.php';

if (!isset($_GET['id'])) {
    die("ID del estudiante no especificado.");
}

$student_id = $_GET['id'];

$sql = "SELECT students.name, students.dni, classes.class_code 
        FROM students 
        JOIN classes ON students.class_id = classes.id 
        WHERE students.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Estudiante no encontrado.");
}

$student = $result->fetch_assoc();

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, 'Reporte de Notas del Estudiante', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    function StudentInfo($student)
    {
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, "Nombre del Estudiante: " . $student['name'], 0, 1);
        $this->Ln(10);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->StudentInfo($student);
$pdf->Output('I', 'reporte_estudiante_' . $student_id . '.pdf');

$conn->close();
?>
