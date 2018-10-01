<?php
ob_start();
ini_set('display_errors', 'on');
error_reporting(E_ALL | E_STRICT);
require('fpdf.php');
echo "a to wyjdzie na ekran";
$pdf = new FPDF();
//$pdf->Open();
$pdf->AddPage('P', 'A5');
$pdf->AddFont('arial_ce','','arial_ce.php');
$pdf->AddFont('arial_ce','I','arial_ce_i.php');
$pdf->AddFont('arial_ce','B','arial_ce_b.php');
$pdf->AddFont('arial_ce','BI','arial_ce_bi.php');
$pdf-> SetFont('arial_ce', 'B',  20);
$pdf-> SetXY(10, 30);
$pdf->MultiCell(100,8, 'Moj plik', 0, 'L', 0);
$pdf-> SetFont('arial_ce', 'BI',  20);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->SetXY($x, $y+6);
$pdf->MultiCell(100,5, 'Serdecznie zaprasza na', 0, 'L', 0);
$pdf->Output();
//$pdf->Output(F,'filename.pdf');
ob_end_flush();
?>