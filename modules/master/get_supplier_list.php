<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";

	$table_supp="mst_sup_".$company_id;
	
	$showdel= isset($_POST['showdel']) ? $_POST['showdel'] : '1';
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'kd_sup';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
	$cari= isset($_POST['cari']) ? mysql_real_escape_string($_POST['cari']) : '';
	$offset = ($page-1)*$rows;
	
	$filtrec="";
	
	$filter1="";
	$filter2="";
	$filter3="";
	
	if ($showdel=="1") {
		$filter1="sup_status='".$showdel."' ";
	}
	if ($cari!="" ) {
		$filter3="concat(kd_sup,nm_sup) LIKE '%".$_POST[cari]."%'";
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
	
	
	// query
	$qry="select count(*) from ".$table_supp." ".$filtrec;
	$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	
	$qry="select * from ".$table_supp."".$filtrec." order by $sort $order limit $offset, $rows ";
	
	//echo "$qry";
	
	$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
	 
	$items = array();
	while($row = mysql_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;
	 
	echo json_encode($result);	
}
?>