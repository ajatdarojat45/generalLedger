<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";

	$table_akun="mst_akun_".$company_id;
	$table_saldo="trx_caseglm_".$company_id;

	$acc=$_POST[acc];
	
	if ($acc=="") {
		echo json_encode(array('errorMsg'=>'Kode Perkiraan Tidak Boleh Kosong !'));
	} else {
	$th_sld=$_POST['th_sld']; 
	$bl_sld=$_POST['bl_sld']; 
	$jml_sld=$_POST['jml_sld'] ;
	$jml_sld=isset($_POST['jml_sld']) ? $_POST['jml_sld'] : '0';
	
	//echo "<script>alert('$jml_sld');</script>";
	
	if ($jml_sld=="") { $jml_sld="0";}
	
	$jml=str_replace(",","",$jml_sld);
	
	$periode=substr($sys_tgl,0,4);
	
	//echo "<script>alert('$periode');</script>";
	
	//return 
	
	$qry="SELECT acc FROM $table_akun WHERE acc='$acc'";
	$rec=@mysql_query($qry,$conn);
	
	$result=mysql_fetch_array($rec);
	if ($result['acc']==$acc){ // Jika kode sudah ada
		echo json_encode(array('errorMsg'=>'Kode Perkiraan '.$acc.' sudah ada !'));
	} else {
		$qry="INSERT INTO $table_saldo (mcom_id,acc,per) VALUES ('$company_id','$acc','$periode')";
		$result=@mysql_query($qry,$conn);
		if ($result){
			$qry="INSERT INTO $table_akun (mcom_id,acc,nmp,level,tnd,jnp,hpp,pusat,acc_status,th_sld,bl_sld,jml_sld,pemakai,tgl_input) VALUES ('$company_id','$acc','$_POST[nmp]','$_POST[level]','$_POST[tnd]','$_POST[jnp]','$_POST[hpp]','$_POST[pusat]','1','$th_sld','$bl_sld','$jml','$user_id',now())";
			$result=@mysql_query($qry,$conn);
			if ($result){
				echo json_encode(array('success'=>true));
			} else {
				echo json_encode(array('errorMsg'=>'Some errors occured, cannot save new account. '));
			}
		} else {
			echo json_encode(array('errorMsg'=>'Some errors occured, cannot save saldo. '));
		}
	}
	}
}
?>