<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt']))
{
	include "../inc/inc_akses.php";

	$table_akun="mst_akun_".$company_id;
	
	$qry="select acc,nmp from ".$table_akun ;
	$res=mysql_query($qry,$conn) or die("Error $qry ".mysql_error());

}
?>
