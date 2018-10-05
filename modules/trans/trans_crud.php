<?php
	session_start();
	
	if (!isset($_SESSION['app_glt'])) {	
		exit ;
	} 
	
	include "../inc/inc_akses.php";
	
	$tbl_trx	="trx_caseglt_".$company_id ;	 
	$tgl_trans	=substr($aktif_tgl,0,7);	
	$userid		=$_SESSION["app_glt"];

	$jenis		=isset($_GET['trx']) ? $_GET['trx'] : 'XXX';
	$crud		=isset($_GET['crud']) ? $_GET['crud'] : 'XXX';
	
	$tipetrx="X";
	$qry="";
	
	if ($jenis=="KAS"){
		$tipetrx="K";
	}
	if ($jenis=="BANK"){
		$tipetrx="B";
	}
	if ($jenis=="MEMORIAL"){
		$tipetrx="M";
	}
	if ($jenis=="BS"){
		$tipetrx="S";
	}

	if ($crud=="DELETE"){
		$nbk=isset($_POST['nbk']) ? $_POST['nbk'] : 'XXX';

		//$qry="update $tbl_trx set trx_status='0',tgl_input=now(),user_id='$userid' where nbk='$nbk' and typ='$tipetrx' order by nbk";
		$qry="delete from $tbl_trx where nbk='$nbk' and typ='$tipetrx' order by nbk";
	}
	
	if ($crud=="EDIT"){
		$trcf=isset($_POST['trcf']) ? $_POST['trcf'] : 'X';
		$nbk=isset($_POST['nbk']) ? $_POST['nbk'] : 'XXX';
		$datamentah=json_decode(json_encode($_POST['data']));
		
		if (!isset($_POST['nbk'])) {
			echo json_encode(array('errorMsg'=>'Nomor Bukti Tidak Terkirim !'));
			exit;
		}
		if ($datamentah=="" || $datamentah==null ) {
			echo json_encode(array('errorMsg'=>'Tidak Ada Object Data !'));
			exit;
		}
		if (!isset($_POST['trcf'])) {
			echo json_encode(array('errorMsg'=>'Tanda Cashflow Tidak Terkirim !'));
			exit;
		}
		//$seq1=$datamentah[0]->{'seq'};	
		//$seq2=$datamentah[1]->{'seq'};	
		$pjg=sizeof($datamentah);
	
		//$qry="update $tbl_trx set trx_status='0' where nbk='$nbk' and typ='$tipetrx' order by nbk";
		
		$qry="delete from $tbl_trx where nbk='$nbk' and typ='$tipetrx' order by nbk";
		$sukses = mysql_query($qry,$conn) or die (json_encode(array('errorMsg'=>mysql_error())) );

		$teks="insert into $tbl_trx (mcom_id,trx_status,tgp,nbk,seq,acc,deb,krd,kd_sup,ket,tgl_input,user_id,typ,flg) values ";
		$isi="";		
		for ($m=($pjg-1); $m >= 0; $m--) {
			$isi=$isi." ('$company_id','".$datamentah[$m]->{'trx_status'}."','".
							$datamentah[$m]->{'tgp'}."','".$datamentah[$m]->{'nbk'}."','".
							$datamentah[$m]->{'seq'}."','".$datamentah[$m]->{'acc'}."','".
							$datamentah[$m]->{'deb'}."','".$datamentah[$m]->{'krd'}."','".
							$datamentah[$m]->{'kd_sup'}."','".mysql_real_escape_string($datamentah[$m]->{'ket'})."',now(),'$userid','$tipetrx','$trcf'),";
		}
		/*for ($m=0; $m<$pjg; $m ++) {
			$isi=$isi." ('$company_id','".$datamentah[$m]->{'trx_status'}."','".
							$datamentah[$m]->{'tgp'}."','".$datamentah[$m]->{'nbk'}."','".
							$datamentah[$m]->{'seq'}."','".$datamentah[$m]->{'acc'}."','".
							$datamentah[$m]->{'deb'}."','".$datamentah[$m]->{'krd'}."','".
							$datamentah[$m]->{'kd_sup'}."','".mysql_real_escape_string($datamentah[$m]->{'ket'})."',now(),'$userid','$tipetrx','$trcf'),";
		}*/
		
		$isinya=substr($isi,0,-1);
		$qry=$teks.$isinya;			
	}
	
	//You have an error in your SQL syntax; check the manual that corresponds 
	//to your MySQL server version for the right syntax to use near '' at line 1
	
	if ($crud=="NEW"){	
		$datamentah=json_decode(json_encode($_POST['data']));
		// Cek Auto Number atau Manual
		$nobuk=isset($_POST['nbk']) ? $_POST['nbk'] : 'X';
		// KK  atau KM
		$tipetrans=isset($_POST['jenistrans']) ? $_POST['jenistrans'] : 'X';		
		$nbk="";

		if (!isset($_POST['nbk'])) {
			echo json_encode(array('errorMsg'=>'Nomor Bukti Tidak Terkirim !'));
			exit;
		}
		if (!isset($_POST['jenistrans'])) {
			echo json_encode(array('errorMsg'=>'Tipe Transaksi Tidak Terkirim !'));
			exit;
		}
		if ($datamentah=="" || $datamentah==null ) {
			echo json_encode(array('errorMsg'=>'Tidak Ada Object Data !'));
			exit;
		}
		
		if ($nobuk=="Auto Number"){ 
			// Buat nomor otomatis dari data yang ada 
			$qrycari="select max(substr(nbk,4,3)) as noakhir from $tbl_trx where typ='$tipetrx' and left(tgp,7)='".$tgl_trans."' and trx_status='1'";
			
			$hasil = mysql_query($qrycari,$conn) or die (json_encode(array('errorMsg'=>mysql_error())));			
			$ada=mysql_fetch_array($hasil);
			
			$nomor=$ada['noakhir']+1;
			
			$nourut=substr('000'.$nomor,-3);
			
			$nbk=$tipetrans."-".$nourut."/".substr($tgl_trans,-2)."/".substr($tgl_trans,2,2);
			
		} else {
			// Cek apakah nomor bukti sudah ada
			$qrycari="select count(*) as jml from $tbl_trx where nbk='$nobuk' and trx_status='1' and typ='$tipetrx'";
			$hasil = mysql_query($qrycari,$conn) or die (json_encode(array('errorMsg'=>mysql_error())));			
			$ada=mysql_fetch_array($hasil);
			
			$jml=$ada['jml'];
			
			if ($jml>0){
				echo json_encode(array('errorMsg'=>'Nomor Bukti '.$nobuk.' sudah ada !'));
				exit;
			}
			
			$nbk=$nobuk;
		}		
		
		$pjg=sizeof($datamentah);
		$teks="insert into $tbl_trx (mcom_id,trx_status,tgp,nbk,seq,acc,deb,krd,kd_sup,ket,tgl_input,user_id,typ) values ";
		$isi="";		
		for ($m=($pjg-1); $m >= 0; $m--) {
		//for ($m=0; $m<$pjg; $m ++) {
			$isi=$isi." ('$company_id','".$datamentah[$m]->{'trx_status'}."','".
							$datamentah[$m]->{'tgp'}."','$nbk','".
							$datamentah[$m]->{'seq'}."','".$datamentah[$m]->{'acc'}."','".
							$datamentah[$m]->{'deb'}."','".$datamentah[$m]->{'krd'}."','".
							$datamentah[$m]->{'kd_sup'}."','".mysql_real_escape_string($datamentah[$m]->{'ket'})."',now(),'$userid','$tipetrx'),";
		}		
		$isinya=substr($isi,0,-1);
		$qry=$teks.$isinya;	
		//echo json_encode(array('errorMsg'=>$qry));				
		//exit;
	}
	
	$sukses = mysql_query($qry,$conn) or die ( json_encode(array('errorMsg'=>mysql_error())));
	
	if ($sukses) {
		echo json_encode(array('success'=>true));
	}else {
		echo json_encode(array('errorMsg'=>mysql_error()));				
	}
?>