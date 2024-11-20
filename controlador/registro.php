<?php
//Incluimos la libreria PDF
require('./fpdf.php');

class PDF extends FPDF
{
    // Funcion encargada de realizar el encabezado
    function Header()
    {
        // Logo
        $this->Image('./vista/img/logo.png',10,-1,50);
        $this->SetFont('Arial','B',13);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(95,10,'Lista de Usuarios',1,0,'C');
        // Line break
        $this->Ln(20);
    }
    // Funcion pie de pagina
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Parámetros de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dakara";

// Crear conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Realizar la consulta
$result = $conn->query("SELECT u.usua_codigo, u.usua_nombre, u.id_rol 
                        FROM usuario u");

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

$pdf = new PDF();
//header
$pdf->AddPage();
//footer page
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',12);
// Declaramos el ancho de las columnas
$w = array(20, 100, 60);
//Declaramos el encabezado de la tabla
$pdf->Cell(20,12,'ID',1);
$pdf->Cell(100,12,'NOMBRE',1);
$pdf->Cell(60,12,'ROL',1);
$pdf->Ln();
$pdf->SetFont('Arial','',12);
//Mostramos el contenido de la tabla
while($row = $result->fetch_assoc())
{
    $pdf->Cell($w[0],6,$row['usua_codigo'],1);
    $pdf->Cell($w[1],6,$row['usua_nombre'],1);
    $pdf->Cell($w[2],6,$row['id_rol'],1); // Cambiado a id_rol
    $pdf->Ln();
}
$pdf->Output();

// Cerrar la conexión
$conn->close();
?>