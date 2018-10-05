<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";

	$table_akun="mst_akun_".$company_id;
	
	$qry="select acc,nmp from ".$table_akun." where acc_status=1 and level=5 order by acc ";

	if (isset($_GET['pusat'])){
		if ($_GET['pusat']=='KB') {
			$qry="select acc,nmp from ".$table_akun." where acc_status=1 and level=5 and (pusat='K' or pusat='B') order by acc ";
		} else {		
			$qry="select acc,nmp from ".$table_akun." where acc_status=1 and level=5 and pusat='".$_GET['pusat']."' order by acc ";
		}
	}
	
	$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
	 
	$items = array();
	while($row = mysql_fetch_object($rs)){
		array_push($items, $row);
	}
	
	$result= $items;
	
	echo json_encode($result);	
}
?>