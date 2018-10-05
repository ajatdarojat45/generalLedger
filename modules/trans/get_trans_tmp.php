<?php
	session_start();
	
	if (!isset($_SESSION['app_glt'])) {	
		exit ;
	}
	
	include "../inc/inc_akses.php";
	
	$tbl_mst_akun	="mst_akun_".$company_id ;
	$tbl_mst_sup	="mst_sup_".$company_id ;	
	$tbl_trx		="trx_caseglt_".$company_id ;	 
	$tbl_trx_tmp	="trx_tmp_caseglt";	 
	$tgl_trans		=substr($aktif_tgl,0,7);
	
	$userid=$_SESSION["app_glt"];
	
	/*if (!isset($_POST['nbk'])) {		
		$result["total"] = 0;		 
		$result["rows"] = array();		 
		echo json_encode($result);	
		exit;
	}*/
	
	$nbk= isset($_POST['nbk']) ? $_POST['nbk'] : 'XXX';
	
	$result["total"] = 0;
	
	$qry="delete from $tbl_trx_tmp where user_id='$userid'";
	$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
	
	$qry="insert into $tbl_trx_tmp (mcom_id,trx_status,tgp,nbk,seq,acc,deb,krd,kd_sup,ket,flg,user_id) (select mcom_id,trx_status,tgp,nbk,seq,acc,deb,krd,kd_sup,ket,flg,'$userid' from $tbl_trx  where trx_status='1' and nbk='$nbk' )";
	$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());


	$qry="select * from $tbl_trx_tmp where nbk='$nbk' and trx_status='1' and user_id='$userid' order by seq";
	$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
	
	//$qry="select mcom_id,trx_status,tgp,nbk,seq,acc,deb,krd,kd_sup,ket from ".$tbl_trx_tmp." ".$filtrec." order by $sort $order limit $offset, $rows ";
	
	//echo "$qry";
	
	//$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
	 
	$items = array();
	while($row = mysql_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;
	 
	echo json_encode($result);	
		
?>