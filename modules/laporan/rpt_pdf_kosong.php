<?
	require("fpdf.php");		
	
	$pdf = new FPDF( );
	
	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',16);
	
	$pdf->Cell(0,10,'BLANK REPORT');
	
	$pdf->Output();
?>
