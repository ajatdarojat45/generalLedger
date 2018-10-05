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

<table class="table table-hover table-bordered" width="100%">
	<th width="2%" class="info">No.Id</th>
	<th class="info">Keterangan</th>
	<th width="2%" class="info">D/H</th>
	<th width="5%" class="info">Pilih</th>
	<?
	if ($_GET[kd_pil_kode]) {
		$que = mysql_query("INSERT INTO $tbl_setup_d (mcom_id,noset_id,nourut_id,pt,acc,nmp,nil,trx_status,kode,nama,flg,pemakai,tgl_input,user_id) VALUES ('$company_id','$_GET[noset]','$_GET[nourut]','0','','','+','1','$_GET[kd_pil_kode]','$_GET[kd_pil_nama]','$_GET[kd_pil_flg]','$user_id',now(),'$user_id') ", $conn) or die("Error Insert Akun");
		$que = mysql_query("INSERT INTO $tbl_setup (mcom_id,noset_id,nourut_id,pt,acc,nmp,nil,trx_status,kode,nama,flg,pemakai,tgl_input,user_id) VALUES ('$company_id','$_GET[noset]','$_GET[nourut]','0','','','+','1','$_GET[kd_pil_kode]','$_GET[kd_pil_nama]','$_GET[kd_pil_flg]','$user_id',now(),'$user_id') ", $conn) or die("Error Insert Akun");
	}
	
	$que = mysql_query("SELECT * FROM $tbl_head WHERE nourut_id<>'$_GET[nourut]' AND keterangan<>'' AND user_id='$user_id' AND nourut_id NOT IN (SELECT kode FROM $tbl_setup_d WHERE user_id='$user_id' AND trx_status='1') ORDER BY nourut", $conn) or die("Error Create Select Temp Neraca Kons Detail Setup".mysql_error());
	$no=1;
	while ($data = mysql_fetch_array($que)){
		if ($data[flg]=="H") { 
			$isi="<b><input id='set_$no_urut' name='set_ket' class='form-control' type='text' value='$data[keterangan]' disabled='disabled' /></b>" ;
		} else { 
			$isi="<input id='set_$no_urut' name='set_ket' class='form-control' type='text' value='$data[keterangan]' disabled='disabled'/>" ;
		}		
		
		echo "<tr>
		<td>$data[nourut_id]</td>
		<td>$isi</td>
		<td>$data[flg]</td>
		<td><a href='?noset=$_GET[noset]&nourut=$_GET[nourut]&kd_pil_kode=$data[nourut_id]&kd_pil_nama=$data[keterangan]&kd_pil_flg=$data[flg]' title='Pilih $data[nourut_id]' data-placement='bottom'><span class='glyphicon glyphicon-arrow-down'></span></a></td></tr>";
		$no ++;
		//	<a href='?noset=$_GET[noset]&nourut=$_GET[nourut]&kd_pil_kode=$data[nourut_id]&kd_pil_nama=$data[keterangan]&kd_pil_flg=$data[flg]' onclick=	"klik_pilih_head('$data[nourut_id]','$_GET[nourut]')\" title='Pilih $data[nourut_id]' data-placement='bottom'><span class='glyphicon glyphicon-arrow-down'></span></a>
	}
	?>
	
</table>
<?

if ($_GET[kd_pil_kode]) {	

	echo "<script> parent.document.getElementById(\"frame_hasil\").src='tampil_setup_head.php?noset=$_GET[noset]&nourut=$_GET[nourut]';</script>";
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
