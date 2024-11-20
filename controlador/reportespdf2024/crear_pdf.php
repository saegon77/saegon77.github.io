<?php
//Incluimos el fichero de conexion
include_once("dbconect.php");
//Incluimos la libreria PDF
include_once('libs/fpdf.php');
 
class PDF extends FPDF
{
// Funcion encargado de realizar el encabezado
function Header()
{
    // Logo
    $this->Image('logo1.png',9,-1,28);
    $this->SetFont('Arial','B',13);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(95,10,'Usuarios',1,0,'C');
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
$db = new dbConexion();
$connString =  $db->getConexion();
$display_heading = array('usua_codigo'=>'Codigo de usuario', 'usua_nombre'=> 'Nombre de usuario', 'usua_clave'=> 'clave','id_rol'=> 'ID',);
 
$result = mysqli_query($connString, "SELECT usua_codigo, usua_nombre, usua_clave, id_rol FROM usuario") or die("database error:". mysqli_error($connString));
$header = mysqli_query($connString, "SHOW columns FROM usuario");
 
$pdf = new PDF();
//header
$pdf->AddPage();
//foter page
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',12);
// Declaramos el ancho de las columnas
$w = array(30, 80, 30, 30,30);
//Declaramos el encabezado de la tabla
$pdf->Cell(30,12,'CODIGO',1);
$pdf->Cell(80,12,'NOMBRE',1);
$pdf->Cell(30,12,'CLAVE',1);
$pdf->Cell(30,12,'ID',1);
$pdf->Ln();
$pdf->SetFont('Arial','',12);
//Mostramos el contenido de la tabla
 foreach($result as $row)
    {
        $pdf->Cell($w[0],6,$row['id_rol'],1);
        $pdf->Cell($w[1],6,$row['usua_nombre'],1);
        $pdf->Cell($w[2],6,$row['usua_clave'],1);
        $pdf->Cell($w[3],6,$row['usua_codigo'],1);
        $pdf->Ln();
    }
$pdf->Output();
?>
