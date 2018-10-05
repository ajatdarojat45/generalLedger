<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";

	$table_akun="mst_akun_".$company_id;

	$acc=$_GET[acc];
	$nmp=$_POST[nmp];

	$qry="update ".$table_akun." set acc_status=0,pemakai='$user_id',tgl_input=now() where acc='".$acc."' ";
	$result=@mysql_query($qry,$conn);
	if ($result){
		echo json_encode(array('success'=>true));
	} else {
		echo json_encode(array('errorMsg'=>'Some errors occured cannot delete.'));
	}
}
?>