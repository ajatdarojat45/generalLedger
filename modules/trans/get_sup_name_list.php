<?php
session_start();

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";

	$tbl_mst_sup="mst_sup_".$company_id ;

	$qry="select kd_sup,nm_sup from ".$tbl_mst_sup." where sup_status=1 order by nm_sup";
	
	$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
	 
	$items = array();
	while($row = mysql_fetch_object($rs)){
		array_push($items, $row);
	}
	
	$result= $items;
	
	echo json_encode($result);	
}
?>