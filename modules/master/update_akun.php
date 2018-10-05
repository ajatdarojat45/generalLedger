<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){ 
	
	include "../inc/inc_akses.php";

	$table_akun="mst_akun_".$company_id;
	
	$acc=$_GET[acc];
	
	//echo "<script> alert('$acc');</script>";
	
	$th_sld=$_POST['th_sld']; 
	$bl_sld=$_POST['bl_sld']; 
	$jml_sld=$_POST['jml_sld'] ;
	$jml_sld=isset($_POST['jml_sld']) ? $_POST['jml_sld'] : '0';
	
	//echo "<script>alert('$jml_sld');</script>";
	
	if ($jml_sld=="") { $jml_sld="0";}
	
	$jml=str_replace(",","",$jml_sld);
	$qry="UPDATE $table_akun SET nmp='$_POST[nmp]', level='$_POST[level]', tnd='$_POST[tnd]', jnp='$_POST[jnp]', hpp='$_POST[hpp]', pusat='$_POST[pusat]', ket='$_POST[kd_ket]', pemakai='$user_id',th_sld='$th_sld',bl_sld='$bl_sld',jml_sld='$jml', tgl_input=now() WHERE acc='$acc' AND acc_status='1' AND mcom_id='$company_id' ";
	
	$result=@mysql_query($qry,$conn);
	if ($result){
		echo json_encode(array('success'=>true));
	} else {
		echo json_encode(array('errorMsg'=>'Some errors occured cannot update'));
	}
	
	// Update table saldo ( caseglm )
	if ($th_sld!="" && $bl_sld!="" && $jml_sld!="") {
	$qry="UPDATE $table_akun SET nmp='$_POST[nmp]', level='$_POST[level]', tnd='$_POST[tnd]', jnp='$_POST[jnp]', hpp='$_POST[hpp]', pusat='$_POST[pusat]', ket='$_POST[kd_ket]', pemakai='$user_id',th_sld='$th_sld',bl_sld='$bl_sld',jml_sld='$jml', tgl_input=now() WHERE acc='$acc' AND acc_status='1' AND mcom_id='$company_id' ";
	}
}
?>