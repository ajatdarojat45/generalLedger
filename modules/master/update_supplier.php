<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){ 
	
	include "../inc/inc_akses.php";

	$table_supp="mst_sup_".$company_id;
	
	$kdsup=$_GET[kd_sup];
	$nmsup=$_POST[nm_sup];
	
	$qry="update ".$table_supp." set nm_sup='$nmsup', pemakai='$user_id',tgl_input=now() where kd_sup='".$kdsup."' ";
	$result=@mysql_query($qry,$conn);
	if ($result){
		echo json_encode(array('success'=>true));
	} else {
		echo json_encode(array('errorMsg'=>'Some errors occured cannot update'));
	}
}
?>