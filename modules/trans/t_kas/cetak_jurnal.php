<?php
	session_start();
	
	if (!isset($_SESSION['app_glt'])) {	
		exit ;
	}
	
	include "../inc/inc_akses.php";
	include "../inc/func_modul.php";
	
	include "../../plugins/fpdf/fpdf.php";	

	$tbl_mst_akun	="mst_akun_".$company_id ;
	$tbl_mst_sup	="mst_sup_".$company_id ;	
	$tbl_trx		="trx_caseglt_".$company_id ;	 
	$tgl_trans		=substr($aktif_tgl,0,7);
	
	$nbk=$_GET['nbk'];
	
	date_default_timezone_set("Asia/Jakarta");
	
	
	class PDF extends FPDF {
		// Page header
		function Header()
		{	
			//$this->SetFont('Arial','B',10);
			// Title
			//$this->Cell(150);
			//$this->Cell(50,6,'BUKTI JURNAL','TLBR',0,'C');
			
		}
		
		// Page footer
		function Footer()
		{	// draw line at bottom record page
			//$this->Cell(200,4,'','T',0,'C'); 

			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',6);
			// Page number
			$this->Cell(strlen('Page '.$this->PageNo().'/{nb} Date: '.date('d-m-Y h:m:s').
						" GL TEMPO")+4,4,'Page '.$this->PageNo().'/{nb} Date: '.date('d-m-Y h:m:s')." GL TEMPO",'LRTB',0,'C');
			//$this->Line(05,198,280,198);
		}
	}
	
	$pdf = new PDF('P','mm','A4','Tempo Media Group','Bukti Jurnal: '.$nbk,'Jurnal_'.$nbk); // ( POTRAIT, 'mm', 'A4', 'perusahaan', 'judul laporan')
	
	$qry1="select * from ".$tbl_trx." where nbk='$nbk' and trx_status='1' order by nbk,seq ";
	$rs1 = mysql_query($qry1,$conn) or die ("Error $qry ".mysql_error());
	$rec1=mysql_fetch_array($rs1);
	
	$qry2="select * from ".$tbl_trx." where nbk='$nbk' and trx_status='1' order by nbk,seq ";
	$rs2 = mysql_query($qry2,$conn) or die ("Error $qry ".mysql_error());
	
	
	$pdf->SetTitle('Bukti Jurnal : '.$nbk);
	$pdf->AliasNbPages();
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(5);
	$pdf->AddPage();
	$pdf->SetFont('Arial','',14);
	// Title
	$pdf->Cell(50,5,$company_name,'',0,'L');
	$pdf->Cell(100);
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(40,5,'BUKTI JURNAL','LTBR',0,'C');
	$pdf->Ln(10);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(50,6, 'Nomor Bukti   : '.$nbk,'',0,'L');
	$tgp=set_tglblntahun($rec1['tgp']);
	$pdf->Ln(5);
	$pdf->Cell(50,6, 'Tanggal Bukti : '.$tgp,'',0,'L');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','I',8);

	$pdf->Cell(190,2,'','B',0,'L');
	$pdf->Ln(2);	
	$pdf->Cell(8,6,'Seq','B',0,'L');
	$pdf->Cell(22,6,'Kode Perkiraan','B',0,'L');
	$pdf->Cell(140,6,'Nama Perkiraan / Keterangan','B',0,'L');
	$pdf->Cell(20,6,'Jumlah    ','B',0,'R');
	
	$pdf->SetFont('Arial','',7);
	$pdf->Ln(7);
	$total=0;
	while ($rec2=mysql_fetch_array($rs2)){
		
		$acc=$rec2['acc'];
		
		$qry="select * from ".$tbl_mst_akun." where acc='$acc' ";
		$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
		
		$dtakun=mysql_fetch_array($rs);
		
		$nmp=empty($dtakun['nmp']) ? '--------------------- Kode Akun Tidak Ditemukan -------------------- ' : $dtakun['nmp'] ;
		
		$pdf->Cell(8,6,$rec2['seq'],'',0,'L');
		$pdf->Cell(22,6,$rec2['acc'],'',0,'L');
		$pdf->Cell(140,6,$nmp,'',0,'L');
		
		if ($rec2['deb']==0){
			$pdf->Cell(20,6,tampil_uang($rec2['deb']+$rec2['krd']).' (K)','',0,'R');	
		} else {
			$pdf->Cell(20,6,tampil_uang($rec2['deb']+$rec2['krd']).' (D)','',0,'R');	
		}
		
		$pdf->Ln(4);
		$pdf->Cell(30,6,'- - - - - - - - - - - - - - - - - - - -','',0,'C');		
		$pdf->Cell(150,6,$rec2['ket'],'',0,'L');		
		$pdf->Ln(5);
		$total += $rec2['deb'];
		
	}
	$pdf->Cell(190,2,'','B',0,'L');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(160,2,'TOTAL :','',0,'R');
	$pdf->Cell(26,2,tampil_uang($total),'',0,'R');
	$pdf->Ln(2);
	$pdf->Cell(140,2,'','B',0,'L');
	$pdf->Cell(50,2,'','B',0,'L');

	$pdf->Output($pdf->JudulShow().'.pdf','I');

?>
