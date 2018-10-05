<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";

	$table_supp="mst_sup_".$company_id;
	
	$kdsup=$_POST[kd_sup];
	$nmsup=$_POST[nm_sup];
	
	$qry="SELECT kd_sup FROM $table_supp WHERE kd_sup='$kdsup'";
	$rec=@mysql_query($qry,$conn);
	
	$result=mysql_fetch_array($rec);
	if ($result['kd_sup']==$kdsup){ // Jika kode sudah ada
		echo json_encode(array('errorMsg'=>'Kode Supplier '.$kdsup.' sudah ada !'));
	} else {
		$qry="INSERT INTO $table_supp (mcom_id,kd_sup,nm_sup,sup_status,pemakai,tgl_input) VALUES ('$company_id','$kdsup','$nmsup','1','$user_id',now())";
		//$result=@mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
		$result=@mysql_query($qry,$conn);
		if ($result){
			echo json_encode(array('success'=>true));
		} else {
			echo json_encode(array('errorMsg'=>'Some errors occured, cannot save. '));
		}
	}
}
?>