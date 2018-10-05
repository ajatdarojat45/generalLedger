<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {
	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php";
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	include "../inc/func_modul.php";
	include "../inc/inc_aed.php";	
	include "../../plugins/fpdf/fpdf.php";	

	$tbl_mst_akun="mst_akun_".$company_id ;
	$tbl_mst_saldo="trx_caseglm_".$company_id ;
	
	//echo $company_name;
	$tgl_trans=substr($aktif_tgl,0,7);
	$tahun=substr($aktif_tgl,0,4);
	$bulan=substr($aktif_tgl,5,2);
	
	$tahun=$_GET[per_thn];
	$bulan=$_GET[per_bln];
	$bulan_nm=ambil_bulan($bulan);
	
	$nama_sistem=$nama_sistem;
	
	class PDF extends FPDF {
		// Page header
		function Header()
		{	
			$this->SetFont('Arial','',9);
			// Title
			$this->Cell(280,0,$this->CompanyShow(),0,0,'C');
			$this->Ln(6);
			$this->SetFont('Arial','B',12);
			$this->Cell(280,0,$this->JudulShow(),0,0,'C');
			
			//$this->Line(05,15,280,15);
			//$this->Line(05,20,280,20);
			
			$this->Ln(6);
			$this->SetFont('Times','',6);
			$this->Cell(15,4,'ACC','LTB',0,'C');
			$this->Cell(45,4,'URAIAN','LTB',0,'C');
			$this->Cell(18,4,'Januari','LTB',0,'C');
			$this->Cell(18,4,'Februari','LTB',0,'C');
			$this->Cell(18,4,'Maret','LTB',0,'C');
			$this->Cell(18,4,'April','LTB',0,'C');
			$this->Cell(18,4,'Mei','LTB',0,'C');
			$this->Cell(18,4,'Juni','LTB',0,'C');
			$this->Cell(18,4,'Juli','LTB',0,'C');
			$this->Cell(18,4,'Agustus','LTB',0,'C');
			$this->Cell(18,4,'September','LTB',0,'C');
			$this->Cell(18,4,'Oktober','LTB',0,'C');
			$this->Cell(18,4,'Nopember','LTB',0,'C');
			$this->Cell(18,4,'Desember','LTBR',1,'C');
						
			//$this->Cell(80,0,'Nama Perkiraan',0,0);
			
			// Line break
			//$this->Ln(1);
		}
		
		// Page footer
		function Footer()
		{	// draw line at bottom record page
			$this->Cell(276,4,'','T',0,'C'); 

			// Position at 1.5 cm from bottom
			$this->SetY(-8);
			// Arial italic 8
			$this->SetFont('Arial','I',6);
			// Page number
			$this->Cell(strlen('Page '.$this->PageNo().'/{nb} Date: '.$_POST[cdatetime]."General Ledger System")+4,4,'Page '.$this->PageNo().'/{nb} Date: '.$_POST[cdatetime]." General Ledger System",'LRTB',0,'C');
			//$this->Line(05,198,280,198);
		}
	}
	
	$pdf = new PDF('L','mm','A4',$company_name,$_GET[judul]." - Tahun ".$tahun); // ( POTRAIT, 'mm', 'A4', 'perusahaan', 'judul laporan')
	$pdf->SetTitle($_GET[judul]." - Tahun ".$tahun);
	$pdf->AliasNbPages();
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(5);
	$pdf->AddPage();
	$pdf->SetFont('Times','',5);
	$qry_tmp = mysql_query("SELECT a.acc,a.nmp,a.tnd,a.jnp,a.hpp,b.* FROM ".$tbl_mst_akun." as a right join ".$tbl_mst_saldo." as b on a.acc=b.acc WHERE a.level='5' AND left(b.per,4)='$tahun' ORDER BY a.acc", $conn) or die("Error Select Akun ".mysql_error()) ;

	$no=1;			

	$B1=0;
	$B2=0;
	$B3=0;
	$B4=0;
	$B5=0;
	$B6=0;
	$B7=0;
	$B8=0;
	$B9=0;
	$B10=0;
	$B11=0;
	$B12=0;

	while ($data = mysql_fetch_array($qry_tmp)) {			
		$berwarna="";

		$B1=$B1+($data["sak01"] - $data["saw01"]);
		$B2=$B2+($data["sak02"] - $data["saw02"]);
		$B3=$B3+($data["sak03"] - $data["saw03"]);
		$B4=$B4+($data["sak04"] - $data["saw04"]);
		$B5=$B5+($data["sak05"] - $data["saw05"]);
		$B6=$B6+($data["sak06"] - $data["saw06"]);
		$B7=$B7+($data["sak07"] - $data["saw07"]);
		$B8=$B8+($data["sak08"] - $data["saw08"]);
		$B9=$B9+($data["sak09"] - $data["saw09"]);
		$B10=$B10+($data["sak10"] - $data["saw10"]);
		$B11=$B11+($data["sak11"] - $data["saw11"]);
		$B12=$B12+($data["sak12"] - $data["saw12"]);
		
		$TB1=tampil_uang(($data["sak01"] - $data["saw01"]),true);
		$TB2=tampil_uang(($data["sak02"] - $data["saw02"]),true);
		$TB3=tampil_uang(($data["sak03"] - $data["saw03"]),true);
		$TB4=tampil_uang(($data["sak04"] - $data["saw04"]),true);
		$TB5=tampil_uang(($data["sak05"] - $data["saw05"]),true);
		$TB6=tampil_uang(($data["sak06"] - $data["saw06"]),true);
		$TB7=tampil_uang(($data["sak07"] - $data["saw07"]),true);
		$TB8=tampil_uang(($data["sak08"] - $data["saw08"]),true);
		$TB9=tampil_uang(($data["sak09"] - $data["saw09"]),true);
		$TB10=tampil_uang(($data["sak10"] - $data["saw10"]),true);
		$TB11=tampil_uang(($data["sak11"] - $data["saw11"]),true);
		$TB12=tampil_uang(($data["sak12"] - $data["saw12"]),true);
		
		if ( $TB1=="0.00" && $TB2=="0.00" && $TB3=="0.00" && $TB4=="0.00" && $TB5=="0.00" && $TB6=="0.00" && $TB7=="0.00" && $TB8=="0.00" && $TB9=="0.00" && $TB10=="0.00" && $TB11=="0.00" && $TB12=="0.00" ) {
		} else {
			$pdf->Cell(15,3,$data[acc],'L',0,'');
			$pdf->Cell(45,3,$data[nmp],'L',0,'');
			$pdf->Cell(18,3,$TB1,'L',0,'R');			
			$pdf->Cell(18,3,$TB2,'L',0,'R');
			$pdf->Cell(18,3,$TB3,'L',0,'R');
			$pdf->Cell(18,3,$TB4,'L',0,'R');
			$pdf->Cell(18,3,$TB5,'L',0,'R');
			$pdf->Cell(18,3,$TB6,'L',0,'R');
			$pdf->Cell(18,3,$TB7,'L',0,'R');
			$pdf->Cell(18,3,$TB8,'L',0,'R');
			$pdf->Cell(18,3,$TB9,'L',0,'R');
			$pdf->Cell(18,3,$TB10,'L',0,'R');
			$pdf->Cell(18,3,$TB11,'L',0,'R');
			$pdf->Cell(18,3,$TB12,'LR',1,'R');
		}
		
		$no ++;
	}
	$TB1=tampil_uang($B1,true);
	$TB2=tampil_uang($B2,true);
	$TB3=tampil_uang($B3,true);
	$TB4=tampil_uang($B4,true);
	$TB5=tampil_uang($B5,true);
	$TB6=tampil_uang($B6,true);
	$TB7=tampil_uang($B7,true);
	$TB8=tampil_uang($B8,true);
	$TB9=tampil_uang($B9,true);
	$TB10=tampil_uang($B10,true);
	$TB11=tampil_uang($B11,true);
	$TB12=tampil_uang($B12,true);
	
	$pdf->SetFont('Times','B',5);
	
	$pdf->Cell(60,3,'GRAND TOTAL','LT',0,'C');
	$pdf->Cell(18,3,$TB1,'LT',0,'R');			
	$pdf->Cell(18,3,$TB2,'LT',0,'R');
	$pdf->Cell(18,3,$TB3,'LT',0,'R');
	$pdf->Cell(18,3,$TB4,'LT',0,'R');
	$pdf->Cell(18,3,$TB5,'LT',0,'R');
	$pdf->Cell(18,3,$TB6,'LT',0,'R');
	$pdf->Cell(18,3,$TB7,'LT',0,'R');
	$pdf->Cell(18,3,$TB8,'LT',0,'R');
	$pdf->Cell(18,3,$TB9,'LT',0,'R');
	$pdf->Cell(18,3,$TB10,'LT',0,'R');
	$pdf->Cell(18,3,$TB11,'LT',0,'R');
	$pdf->Cell(18,3,$TB12,'LTR',1,'R');
	
	$pdf->Output();

}

?>
