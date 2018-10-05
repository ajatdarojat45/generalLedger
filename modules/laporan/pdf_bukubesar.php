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
	$tbl_trx_saldo	="trx_caseglm_".$company_id ;	 
	$tgl_trans		=substr($aktif_tgl,0,7);
	
	$perthn=$_GET['per_thn'];
	$perbln=$_GET['per_bln'];
	$kodeacc=$_GET['acc'];
	$tglper=$perthn.'-'.$perbln;
	$namabulan=get_bulan($tglper);
	
	//echo $tglper;
	
	date_default_timezone_set("Asia/Jakarta");
	
	
	class PDF extends FPDF {
		var $Kodeakun="";
		var $Namaakun="";
		var $Periode="";
		var $Saldo="";
		var $NamaSaldo="";
		
		// Page header
		function Header()
		{	
			$this->SetFont('Arial','',12);
			$this->Cell(160,5,$this->CompanyName,'',0,'L');// Nama Perusahaan
			$this->Cell(40,5,'BUKU BESAR','LTBR',0,'C');
			$this->Ln(10);
			$this->CetakAkun($this->Kodeakun,$this->Namaakun,$this->Periode,$this->Saldo,$this->NamaSaldo);
		}
		
		function CetakAkun($Kode="kode akun",$Nama="nama akun",$Per="periode transaksi",$saldo=0,$KetSaldo='Saldo Awal')
		{
			$this->SetFont('Arial','',10);
			$this->Cell(40,5,'Periode       : '.$Per,'',0,'L');
			$this->Ln(5);			
			$this->Cell(40,5,'Kode Akun  : '.$Kode.' - '.$Nama,0,'L');
			$this->Ln(5);			
			$this->SetFont('Arial','I',8);
			$this->Cell(17,6,'Tanggal','TB',0,'L');
			$this->Cell(28,6,'Nomor Bukti','TB',0,'L');
			$this->Cell(90,6,'Keterangan','TB',0,'L');
			$this->Cell(20,6,'Debet','TB',0,'R');
			$this->Cell(20,6,'Kredit','TB',0,'R');
			$this->Cell(25,6,'Saldo','TB',0,'R');
			$this->Ln(7);	
			$this->SetFont('Arial','',8);
			$this->Cell(140,5,$KetSaldo,'',0,'R');
			$this->Cell(60,5,$this->tampiluang($saldo),'',0,'R');	
			$this->Ln(1);	
			$this->Cell(200,5,'','B',0,'');
			$this->Ln(5);	
		}

		function tampiluang($number, $fractional=false) { 
			if ($fractional) { 
				$number = sprintf('%.2f', $number); 
			} 
			while (true) { 
				$replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number); 
				if ($replaced != $number) { 
					$number = $replaced; 
				} else { 
					break; 
				} 
			} 
			return $number; 
		} 
		
		function SetCetakAkun($Kode="kode akun",$Nama="nama akun",$Per="periode transaksi",$Nilai=0,$KetSaldo="Saldo Awal")
		{
			$this->Kodeakun=$Kode;
			$this->Namaakun=$Nama;
			$this->Periode=$Per;
			$this->Saldo=$Nilai;
			$this->NamaSaldo=$KetSaldo;
			
		}
				
		// Page footer
		function Footer()
		{	// draw line at bottom record page
			//$this->Cell(200,4,'','T',0,'C'); 

			// Position at 1.5 cm from bottom
			$this->SetY(-25);
			// Arial italic 8
			$this->SetFont('Arial','I',6);
			// Page number
			$this->Ln(10);			
			$this->Cell(strlen('Page '.$this->PageNo().'/{nb} Date: '.date('d-m-Y h:m:s').
						" GL TEMPO")+4,4,'Page '.$this->PageNo().'/{nb} Date: '.date('d-m-Y h:m:s')." GL TEMPO",'LRTB',0,'C');
			//$this->Line(05,198,280,198);
		}
	}
	
	// ( POTRAIT, 'mm', 'A4', 'perusahaan', 'judul laporan')
	$pdf = new PDF('P','mm','A4',$company_name,'Buku Besar '.$company_name); 
		
	$qry2="select * from ".$tbl_trx." where trx_status='1' and left(tgp,7)='$tglper' and acc='".$kodeacc."' order by acc,tgp";
	$rs2 = mysql_query($qry2,$conn) or die ("Error $qry2 ".mysql_error());
	
	$qry3="select acc,nmp,tnd from ".$tbl_mst_akun." where acc_status='1' and acc='".$kodeacc."' ";
	$rsa = mysql_query($qry3,$conn) or die ("Error $qry3 ".mysql_error());
	$recakun=mysql_fetch_array($rsa);
	
	$qry4="select * from ".$tbl_trx_saldo." where acc='".$kodeacc."' and per='$perthn' ";
	$rsm = mysql_query($qry4,$conn) or die ("Error $qry4 ".mysql_error());
	$recsal=mysql_fetch_array($rsm);
	$salbln=$recsal['saw'.$perbln];
	
	$saldo=$salbln;
	
	$pdf->SetCompanyName($company_name);		
	$pdf->SetTitle('LAPORAN BUKU BESAR');
	$pdf->AliasNbPages();
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(5);
	$pdf->SetCetakAkun($kodeacc,$recakun['nmp'],$namabulan.' '.$perthn,$salbln,'Saldo Awal');
	$pdf->AddPage();
		
	$pdf->SetFont('Arial','',8);
	$total=0;
	$totdeb=0;
	$totkrd=0;	
	
	while ($rec2=mysql_fetch_array($rs2)){
		$pdf->SetCetakAkun($kodeacc,$recakun['nmp'],$namabulan.' '.$perthn,$saldo,'Saldo transaksi');

		$acc=$rec2['acc'];
		$tgp=set_tglblntahun($rec2['tgp']);
		
		$pdf->Cell(17,5,$tgp,'',0,'L');
		$pdf->Cell(28,5,$rec2['nbk'],'',0,'L');
		$pdf->Cell(90,5,$rec2['ket'],'',0,'L');
		
		$pdf->Cell(20,5,tampil_uang($rec2['deb']),'',0,'R');	
		$pdf->Cell(20,5,tampil_uang($rec2['krd']),'',0,'R');
		
		if ($recakun['tnd']=='D'){
			if ($rec2['deb']!=0){
				$saldo += $rec2['deb'];			
			} else {
				$saldo += - $rec2['krd'];			
			}
		} else {
			if ($rec2['krd']!=0){
				$saldo += $rec2['krd'];			
			} else {
				$saldo += - $rec2['deb'];			
			}
		}
		
		$pdf->Cell(25,5,tampil_uang($saldo),'',0,'R');	
		$pdf->Ln(3);
		$pdf->Cell(200,2,'','B',0,'C');
		$pdf->Ln(2);
		
		$total += $rec2['deb'];
		$totdeb += $rec2['deb'];
		$totkrd += $rec2['krd'];		
	}
	
	//$pdf->Cell(200,2,'','B',0,'L');
	$pdf->Ln(2);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(135,5,'TOTAL : ','',0,'R');	
	$pdf->Cell(20,5,tampil_uang($totdeb),'',0,'R');	
	$pdf->Cell(20,5,tampil_uang($totkrd),'',0,'R');	
	$pdf->Cell(25,5,tampil_uang($saldo),'',0,'R');	
	$pdf->Ln(3);
	$pdf->Cell(200,2,'','B',0,'L');
	
	$pdf->Output($pdf->JudulShow().'.pdf','I');

?>
