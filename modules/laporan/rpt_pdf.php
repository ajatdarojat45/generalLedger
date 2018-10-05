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
			$this->Cell(15,8,'ACC','LT',0,'C');
			$this->Cell(50,8,'URAIAN','LT',0,'C');
			$this->Cell(50,4,'NERACA SALDO','LT',0,'C');			
			$this->Cell(50,4,'ADJUSTMENT','LT',0,'C');
			$this->Cell(50,4,'NERACA','LT',0,'C');
			$this->Cell(50,4,'LABA-RUGI','LTR',1,'C');
			
			$this->Cell(15,4,'','B',0,'C');
			$this->Cell(50,4,'','B',0,'C');			
			$this->Cell(25,4,'DEBET','LTB',0,'C');			
			$this->Cell(25,4,'KREDIT','LTB',0,'C');			
			$this->Cell(25,4,'DEBET','LTB',0,'C');			
			$this->Cell(25,4,'KREDIT','LTB',0,'C');			
			$this->Cell(25,4,'DEBET','LTB',0,'C');			
			$this->Cell(25,4,'KREDIT','LTB',0,'C');			
			$this->Cell(25,4,'DEBET','LTB',0,'C');			
			$this->Cell(25,4,'KREDIT','LTBR',0,'C');			
			
			//$this->Cell(80,0,'Nama Perkiraan',0,0);
			
			// Line break
			$this->Ln(4);
		}
		
		// Page footer
		function Footer()
		{	
			$this->Cell(265,4,'','T',0,'C'); 

			// Position at 1.5 cm from bottom
			$this->SetY(-8);
			// Arial italic 8
			$this->SetFont('Arial','I',7);
			// Page number
			$this->Cell(265,4,'Page '.$this->PageNo().'/{nb}','T',0,'C');
			//$this->Line(05,198,280,198);
		}
	}
	
	$pdf = new PDF('L','mm','A4',$company_name,$_GET[rpt]); // ( POTRAIT, 'mm', 'A4', 'perusahaan', 'judul laporan')
	$pdf->AliasNbPages();
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(5);
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','',6);
	$qry_tmp = mysql_query("SELECT a.acc,a.nmp,a.tnd,a.jnp,a.hpp,b.* FROM ".$tbl_mst_akun." as a right join ".$tbl_mst_saldo." as b on a.acc=b.acc WHERE a.level='5' AND left(b.per,4)='$tahun' ORDER BY a.acc", $conn) or die("Error Select Akun ".mysql_error()) ;

	$no=1;			

	$NSD=0;
	$NSK=0;
	$ADJD=0;
	$ADJK=0;
	$NRD=0;
	$NRK=0;
	$LRD=0;
	$LRK=0;
		
	while ($data = mysql_fetch_array($qry_tmp)) {			
		$berwarna="";
		/*
		IF nDK='D'
			nNrcSalD=nSaw
			nTrsD=nSak  &&+nAdjD-nAdjK
		ELSE
			nNrcSalK=nSaw
			nTrsK=nSak  &&-nAdjD+nAdjK
		ENDIF 
		
		IF nNR='N'
			nNrcD=nTrsD
			nNrcK=nTrsK
		ELSE
			nRlD=nTrsD
			nRlK=nTrsK
		ENDIF 
		*/		
		
		$nNrcSalD=0;
		$nNrcSalK=0;
		$NRD=0;
		$NRK=0;
		$LRD=0;
		$LRK=0;
		$nTrsD=0;
		$nTrsK=0;
		
		
		$NSD=0;
		$NSK=0;
		$ADJD=0;
		$ADJK=0;
		$NRD=0;
		$NRK=0;
		$LRD=0;
		$LRK=0;
		
		$nDK=$data[tnd];
		$nNR=$data[jnp];
		
		$nSaw="saw".$bulan;
		$nSak="sak".$bulan;
		
		$Kasd="kasd".$bulan;
		$Bnkd="bnkd".$bulan;
		$Memd="memd".$bulan;

		$Kasc="kasc".$bulan;
		$Bnkc="bnkc".$bulan;
		$Memc="memc".$bulan;
		
		$ADJD = $data[$Kasd] + $data[$Bnkd] + $data[$Memd] ;
		$ADJK = $data[$Kasc] + $data[$Bnkc] + $data[$Memc] ;
		
		if ($data[tnd]=="D") {
			$NSD=$data[$nSaw];
			$nTrsD=$data[$nSak];
		} else {
			$NSK=$data[$nSaw];
			$nTrsK=$data[$nSak];
		}
		
		if ($nNR=='N') {
			$NRD=$nTrsD;
			$NRK=$nTrsK;
			
		} else {
			$LRD=$nTrsD;
			$LRK=$nTrsK;
		}
		
		
		$NS_D=tampil_uang($NSD,true);
		$NS_K=tampil_uang($NSK,true);

		$ADJ_D=tampil_uang($ADJD,true);
		$ADJ_K=tampil_uang($ADJK,true);

		$NR_D=tampil_uang($NRD,true);
		$NR_K=tampil_uang($NRK,true);

		$LR_D=tampil_uang($LRD,true);
		$LR_K=tampil_uang($LRK,true);
		if ( $NSD==0 && $NSK==0 && $ADJD==0 && $ADJK==0 && $NRD==0 && $NRK==0 && $ADJD==0 && $ADJK==0) {
		} else {
			$pdf->Cell(15,3,$data[acc],'L',0,'');
			$pdf->Cell(50,3,$data[nmp],'L',0,'');
			$pdf->Cell(25,3,$NS_D,'L',0,'R');			
			$pdf->Cell(25,3,$NS_K,'L',0,'R');			
			$pdf->Cell(25,3,$ADJ_D,'L',0,'R');			
			$pdf->Cell(25,3,$ADJ_K,'L',0,'R');			
			$pdf->Cell(25,3,$NR_D,'L',0,'R');			
			$pdf->Cell(25,3,$NR_K,'L',0,'R');			
			$pdf->Cell(25,3,$LR_D,'L',0,'R');			
			$pdf->Cell(25,3,$LR_K,'LR',1,'R');
		}
		
		$no ++;
	}
	
	$NSD=tampil_uang($NSD,true);
	$NSK=tampil_uang($NSK,true);
	$ADJD=tampil_uang($ADJD,true);
	$ADJK=tampil_uang($ADJK,true);
	$NRD=tampil_uang($NRD,true);
	$NRK=tampil_uang($NRK,true);
	$LRD=tampil_uang($LRD,true);
	$LRK=tampil_uang($LRK,true);
	
	$pdf->Cell(65,8,'GRAND TOTAL','LT',0,'C');
	$pdf->Cell(25,4,$NSD,'LBT',0,'R');			
	$pdf->Cell(25,4,$NSK,'LBT',0,'R');			
	$pdf->Cell(25,4,$ADJD,'LBT',0,'R');			
	$pdf->Cell(25,4,$ADJK,'LBT',0,'R');			
	$pdf->Cell(25,4,$NRD,'LBT',0,'R');			
	$pdf->Cell(25,4,$NRK,'LBT',0,'R');			
	$pdf->Cell(25,4,$LRD,'LBT',0,'R');			
	$pdf->Cell(25,4,$LRK,'LBRT',1,'R');

	$pdf->Cell(65,0,'','L',0,'C');
	$pdf->Cell(25,4,'','LB',0,'C');			
	$pdf->Cell(25,4,'','LB',0,'C');			
	$pdf->Cell(50,4,'LABA/RUGI','LBT',0,'C');			
	$pdf->Cell(25,4,'','LB',0,'C');			
	$pdf->Cell(25,4,'','LB',0,'C');			
	$pdf->Cell(25,4,'','LB',0,'C');			
	$pdf->Cell(25,4,'','LBR',1,'C');
	
	$pdf->Output();

}


/*
	nAcc=acc
	nSaw=saw&nBl
	nSak=sak&nBl
	
*!*		nAdjD=adjd&nBl
*!*		nAdjK=adjc&nBl
*!*		nAdjD=0 &&kasc&nBl
*!*		nAdjK=0

	nAdjD=Kasd&nBl+Bnkd&nBl+Memd&nBl
	nAdjK=Kasc&nBl+BnkC&nBl+Memc&nBl		
	
	SELECT casecoa
	SET ORDER TO casecoa1
	SEEK nAcc
	IF FOUND()
		nNmp=nmp
		nDK=tnd
		nNR=jnp
		nLevel=level
	ELSE
		nNmp=''
		nDK=''
		nNR=''
		nLevel=0
	ENDIF 
	
	IF nLevel<>5
		SELECT csrGlm
		SKIP 
		LOOP 
	ENDIF 
	
	STORE 0 TO nNrcSalD,nNrcSalK,nNrcD,nNrcK,nRlD,nRlK,nTrsD,nTrsK
	
	IF nDK='D'
		nNrcSalD=nSaw
		nTrsD=nSak  &&+nAdjD-nAdjK
	ELSE
		nNrcSalK=nSaw
		nTrsK=nSak  &&-nAdjD+nAdjK
	ENDIF 
	
	IF nNR='N'
		nNrcD=nTrsD
		nNrcK=nTrsK
	ELSE
		nRlD=nTrsD
		nRlK=nTrsK
	ENDIF 
	
	*/

?>
