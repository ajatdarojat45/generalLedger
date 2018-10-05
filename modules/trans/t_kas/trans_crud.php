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
		$qry="update $tbl_trx set trx_status='0' where nbk='$nbk' and typ='$tipetrx' ";
	}
	
	if ($crud=="EDIT"){
		$nbk=isset($_POST['nbk']) ? $_POST['nbk'] : 'XXX';
		$tgl=isset($_POST['tgp']) ? $_POST['tgp'] : 'XXX';
		$tgp=substr($tgl,-4).'-'.substr($tgl,3,2).'-'.substr($tgl,0,2);		
		
		if ($tgl_trans<>substr($tgp,0,7)){
			echo json_encode(array('errorMsg'=>'Tanggal transaksi salah !'.substr($tgp,0,7))) ;
			exit;
		}
		
		$ketpus=isset($_POST['ketpus']) ? mysql_real_escape_string($_POST['ketpus']) : 'XXX';
		$seq='000';
		
		$qry="update $tbl_trx set tgp='$tgp',ket='$ketpus' where nbk='$nbk' and typ='$tipetrx' and trx_status='1' and seq='000' order by nbk,seq ";
		
		$sukses = mysql_query($qry,$conn) or die (json_encode(array('errorMsg'=>mysql_error())) );
		
		$qry="update $tbl_trx set tgp='$tgp' where nbk='$nbk' and typ='$tipetrx' and trx_status='1' order by nbk,seq";		
	}
	
	if ($crud=="NEW"){		
		$tipetrans=isset($_POST['jenistrans']) ? $_POST['jenistrans'] : 'XXX';		
		$tgl=isset($_POST['tgp']) ? $_POST['tgp'] : 'XXX';
		$tgp=substr($tgl,-4).'-'.substr($tgl,3,2).'-'.substr($tgl,0,2);		

		if ($tgl_trans<>substr($tgp,0,7)){
			echo json_encode(array('errorMsg'=>'Tanggal transaksi salah ! '.substr($tgp,0,7))) ;
			exit;
		}

		$nbk=isset($_POST['nbk']) ? $_POST['nbk'] : 'XXX';
		$accpus=isset($_POST['accpus']) ? $_POST['accpus'] : 'XXX';
		$ketpus=isset($_POST['ketpus']) ? mysql_real_escape_string($_POST['ketpus']) : 'XXX';
		$seq=isset($_POST['seq']) ? $_POST['seq'] : 'XXX';
		$accdet=isset($_POST['accdet']) ? $_POST['accdet'] : 'XXX';
		$supdet=isset($_POST['supdet']) ? $_POST['supdet'] : 'XXX';
		$tipejur=isset($_POST['tipejur']) ? $_POST['tipejur'] : 'XXX';
		$jumlah=isset($_POST['jumlah']) ? $_POST['jumlah'] : '0';
		$ketdet=isset($_POST['ketdet']) ? mysql_real_escape_string($_POST['ketdet']) : 'XXX';
		
		if ($nbk=="Auto Number"){ 
			// Buat nomor otomatis dari data yang ada 
			$nbk="";
			$qrycari="select max(substr(nbk,4,3)) as noakhir from $tbl_trx where typ='$tipetrx' and left(tgp,7)='".substr($tgp,0,7)."' ";
			
			$hasil = mysql_query($qrycari,$conn) or die (json_encode(array('errorMsg'=>mysql_error())));			
			$ada=mysql_fetch_array($hasil);
			
			$nomor=$ada['noakhir']+1;
			$nbk=$tipetrans."-".$nomor."/".substr($tgl_trans,-2)."/".substr($tgl_trans,2,2);
			
		} else {
			// Cek apakah nomor bukti sudah ada
			$qrycari="select count(*) as jml from $tbl_trx where nbk='$nbk' and trx_status='1' and typ='$tipetrx'";
			$hasil = mysql_query($qrycari,$conn) or die (json_encode(array('errorMsg'=>mysql_error())));			
			$ada=mysql_fetch_array($hasil);
			
			$jml=$ada['jml'];
			
			if ($jml>0){
				echo json_encode(array('errorMsg'=>'Nomor Bukti '.$nbk.' sudah ada !'));
				exit;
			}
		}
		
		$jmlD='';
		$jmlK='';
		
		if ($tipetrans=='KK'){ 
			// Jurnal Debet
			$jmlD='0';
			$jmlK=$jumlah;
		} else { 
			// Jurnal Kredit
			$jmlD=$jumlah;
			$jmlK='0';
		}
		
		// Jurnal Pusat 
		$qry1="insert into $tbl_trx (mcom_id,trx_status,nbk,tgp,seq,typ,acc,deb,krd,ket,tgl_input,user_id) 
			  values ('$company_id',1,'$nbk','$tgp','000','$tipetrx','$accpus','$jmlD','$jmlK','$ketpus',now(),'$userid')";
		
		$sukses = mysql_query($qry1,$conn) or die (json_encode(array('errorMsg'=>mysql_error())) );

		if ($sukses) {			
			if ($tipejur=='Debet'){ 
				// Jurnal Debet
				$jmlD=$jumlah;
				$jmlK='0';
			} else { 
				// Jurnal Kredit
				$jmlD='0';
				$jmlK=$jumlah;
			}
			// Jurnal Detail 
			$qry="insert into $tbl_trx (mcom_id,trx_status,nbk,tgp,seq,typ,acc,deb,krd,ket,kd_sup,tgl_input,user_id) 
				values ('$company_id',1,'$nbk','$tgp','001','$tipetrx','$accdet','$jmlD','$jmlK','$ketdet','$supdet',now(),'$userid')";				
		}	
	}
	
	$sukses = mysql_query($qry,$conn) or die ( json_encode(array('errorMsg'=>mysql_error())));
	
	if ($sukses) {
		echo json_encode(array('success'=>true));
	}else {
		echo json_encode(array('errorMsg'=>mysql_error()));				
	}
?>