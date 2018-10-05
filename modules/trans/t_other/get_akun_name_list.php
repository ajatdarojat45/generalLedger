<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";

	$table_akun="mst_akun_".$company_id;
	
	// ...
	//$qry="select count(*) from ".$table_akun;
	//$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
	//$row = mysql_fetch_row($rs);
	//$result["total"] = $row[0];
	
	$qry="select acc,nmp from ".$table_akun." where acc_status=1 and level=5 order by nmp ";
	
	//$result= array();
	
	//echo "$qry";
	
	$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
	 
	$items = array();
	while($row = mysql_fetch_object($rs)){
		array_push($items, $row);
	}
	
	
	$result= $items;
	
	echo json_encode($result);	
}
?>