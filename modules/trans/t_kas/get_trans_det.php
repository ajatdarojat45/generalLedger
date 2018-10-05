<?php
	session_start();
	
	if (!isset($_SESSION['app_glt'])) {	
		exit ;
	}
	
	include "../inc/inc_akses.php";
	
	$tbl_mst_akun	="mst_akun_".$company_id ;
	$tbl_mst_sup	="mst_sup_".$company_id ;	
	$tbl_trx		="trx_caseglt_".$company_id ;	 
	$tgl_trans		=substr($aktif_tgl,0,7);
	
	$userid=$_SESSION["app_glt"];
	
	if (!isset($_POST['filtertrans'])) {
		
		$result["total"] = 0;		 
		$result["rows"] = array();		 
		echo json_encode($result);	
		exit;
	}
	
	$tipe= isset($_GET['tipe']) ? $_GET['tipe'] : '';
	$filtertrans= isset($_POST['filtertrans']) ? $_POST['filtertrans'] : 'XXX';
	$showdel= isset($_POST['showdel']) ? $_POST['showdel'] : '1';
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'seq';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
	$cari= isset($_POST['cari']) ? mysql_real_escape_string($_POST['cari']) : '';
	$offset = ($page-1)*$rows;	
	
	$filtrec="";
	
	$filter1="";
	$filter2="";
	$filter3="";
	
	//if ($showdel=="1") {
		$filter1="trx_status='".$showdel."' ";
	//} 
	
	$filter2="left(tgp,7)='".$tgl_trans."' and nbk='".$filtertrans."' and typ='".$tipe."' ";	
	
	if ($cari!="" ) {
		$filter3="CONCAT(nbk) LIKE '%".$_POST['cari']."%' ";
	}
	
	if ($filter1!="" && $filter2!="" && $filter3!="" ) {
		$filtrec=" WHERE ".$filter1." AND ".$filter2." AND ".$filter3;
	} elseif ($filter1!="" && $filter2!="" ) {
		$filtrec=" WHERE ".$filter1." AND ".$filter2;
	} elseif ($filter2!="" && $filter3!="" ) {
		$filtrec=" WHERE ".$filter2." AND ".$filter3;
	} elseif ($filter1!="" && $filter3!="" ) {
		$filtrec=" WHERE ".$filter1." AND ".$filter3;
	} elseif ($filter1!="") {
		$filtrec=" WHERE ".$filter1;
	} elseif ($filter2!="" ) {
		$filtrec=" WHERE ".$filter2;
	} elseif ($filter3!="" ) {
		$filtrec=" WHERE ".$filter3;
	}
	
	// ...
	/*
	$qry="select count(*) from ".$tbl_trx." ".$filtrec;
	$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	*/
	$result["total"] = 0;
	$qry="select mcom_id,trx_status,tgp,nbk,seq,acc,deb,krd,kd_sup,ket from ".$tbl_trx." ".$filtrec." order by $sort $order limit $offset, $rows ";
	
	//echo "$qry";
	
	$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
	 
	$items = array();
	while($row = mysql_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;
	 
	echo json_encode($result);	
		
?>