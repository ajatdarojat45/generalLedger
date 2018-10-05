<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";
	
	//  Cek Hak Akses Tombol Add, Edit dan Delete
	
	include "../inc/inc_aed.php";

	$tbl_setup_d="tmp_mst_set_nrkt_setup";
	$tbl_setup="tmp_mst_set_nrkt";
	$tbl_head="tmp_mst_set_nrk";

	$table_mst_akun="mst_akun_".$_GET[pt];

?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet">
    <link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet">
    <link href="../../style/style_utama.css" rel="stylesheet">

   <![if lt IE 9]>
      <script src="bootstrap/html5shiv.js"></script>
      <script src="bootstrap/respond.min.js"></script>
    <![endif]>
	
</HEAD>
<body>
<?
echo "Perusahaan : <b>".$_GET[nm_pt]."</b> Level : <b>".$_GET[nm_lvl]."</b>";
?>
<form name="frm_tampil" id="frm_tampil">
<table class="table table-hover table-bordered table-condensed" width="100%" height="50px">
	<th width="15%" class="info">Kode Perkiraan</th>
	<th class="info">Nama Perkiraan</th>
	<th width="5%" class="info">Pilih</th>
	<?
	if ($_GET[kd_pil_acc]) {		
		//echo "INSERT INTO tmp_mst_set_nrkt_setup (mcom_id,noset_id,nourut_id,pt,acc,nmp,nil,trx_status,kode,nama,flg,pemakai,tgl_input,user_id) VALUES ('$company_id','$_GET[noset]','$_GET[nourut]','$_GET[pt]','$_GET[kd_pil_acc]','$_GET[kd_pil_nmp]','+','1','0','','','$user_id',now(),'$user_id') ";
				
		$que = mysql_query("INSERT INTO $tbl_setup_d (mcom_id,noset_id,nourut_id,pt,acc,nmp,nil,trx_status,kode,nama,flg,pemakai,tgl_input,user_id) VALUES ('$company_id','$_GET[noset]','$_GET[nourut]','$_GET[pt]','$_GET[kd_pil_acc]','$_GET[kd_pil_nmp]','+','1','0','','','$user_id',now(),'$user_id') ", $conn) or die("Error Insert Akun");
		$que = mysql_query("INSERT INTO $tbl_setup (mcom_id,noset_id,nourut_id,pt,acc,nmp,nil,trx_status,kode,nama,flg,pemakai,tgl_input,user_id) VALUES ('$company_id','$_GET[noset]','$_GET[nourut]','$_GET[pt]','$_GET[kd_pil_acc]','$_GET[kd_pil_nmp]','+','1','0','','','$user_id',now(),'$user_id') ", $conn) or die("Error Insert Akun");
	}	
	
	if (trim($_GET[level])=="") {
		$que = mysql_query("SELECT acc,nmp FROM $table_mst_akun WHERE 
		acc_status='1' AND acc NOT IN (SELECT acc FROM $tbl_setup_d WHERE user_id='$user_id' AND pt='$_GET[pt]' ) ORDER BY acc ASC ", $conn) or die("Error Select Akun");
	} else {
		$que = mysql_query("SELECT acc,nmp FROM $table_mst_akun WHERE 
		acc_status='1' AND level='$_GET[level]' AND acc NOT IN (SELECT acc FROM $tbl_setup_d WHERE user_id='$user_id'  AND pt='$_GET[pt]' ) ORDER BY acc ASC ", $conn) or die("Error Select Akun");		
	}
	$no=1;
	while ($data = mysql_fetch_array($que)){
		echo "<tr name='row_$no'>
			<td>$data[acc]</td>
			<td>$data[nmp]</td>
			<td><a href='?noset=$_GET[noset]&level=$_GET[level]&nm_lvl=$_GET[nm_lvl]&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&nourut=$_GET[nourut]&kd_pil_acc=$data[acc]&kd_pil_nmp=$data[nmp]#row_$no' title='Pilih $data[acc]' data-placement='bottom'><span class='glyphicon glyphicon-arrow-down'></span></a></td></tr>";
			
			//<td><a href='?noset=$_GET[noset]&level=$_GET[level]&nm_lvl=$_GET[nm_lvl]&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&nourut=$_GET[nourut]&kd_pil_acc=$data[acc]&kd_pil_nmp=$data[nmp]#row_$no' onclick=\"klik_pilih_akun('$data[acc]','$_GET[nourut]','$_GET[pt]')\" title='Pilih $data[acc]' data-placement='bottom'><span class='glyphicon glyphicon-arrow-down'></span></a></td>
			
		$no ++;
	}

	?>
	
</table>
</form>

<?

if ($_GET[kd_pil_acc]) {	

	echo "<script> parent.document.getElementById(\"frame_hasil\").src='tampil_setup_akun.php?noset=$_GET[noset]&nourut=$_GET[nourut]&level=$_GET[level]&nm_lvl=$_GET[nm_lvl]&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&akun_pilih=$_GET[kd_pil_acc]&kd_aksi=pilih';</script>";
}

?>


</body>
</html>
<?
}
else
{
	echo"<title>Manage Care</title>
				<link href=\"../../style\style.css\" rel=stylesheet>";
	echo "<center>";
	echo "<h3>Acess Denied</h3>";
	echo "Please <a href=../../home_login.php target=$_self>[Login]</a> First<br>";
	echo "</center>";

}
?>
