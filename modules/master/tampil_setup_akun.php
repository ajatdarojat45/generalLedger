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
<table class="table table-hover table-bordered table-condensed" width="100%">
	<th width="2%" class="info">+/-</th>
	<th width="15%" class="info">Kode Perkiraan</th>
	<th class="info">Nama Perkiraan</th>
	<th width="5%" class="info">Pilih</th>
	<?
	
	if ($_GET[kd_del_acc]) {		
		$que = mysql_query("DELETE FROM $tbl_setup_d  WHERE acc='$_GET[kd_del_acc]' AND user_id='$user_id' ", $conn) or die("Error Delete setup  Akun");
		
		$que = mysql_query("DELETE FROM $tbl_setup WHERE acc='$_GET[kd_del_acc]' AND user_id='$user_id' ", $conn) or die("Error Delete setup  Akun");
		
	}
	
	if ($_GET[kd_pm]) {
		if ($_GET[kd_pm]=="plus") { $nil="-" ;} else { $nil="+" ;}
			
		$que = mysql_query("UPDATE $tbl_setup_d SET nil='$nil' WHERE pt='$_GET[pt]' AND nourut_id='$_GET[nourut]' AND trx_status='1' AND mcom_id='$company_id' AND user_id='$user_id' AND acc='$_GET[kd_pil_acc]' ORDER BY acc", $conn) or die("Error Update Temp Neraca Kons Detail Setup".mysql_error());
	
	}

	$que = mysql_query("SELECT * FROM $tbl_setup_d WHERE pt='$_GET[pt]' AND nourut_id='$_GET[nourut]' AND trx_status='1' AND mcom_id='$company_id' AND user_id='$user_id' ORDER BY acc", $conn) or die("Error Create Select Temp Neraca Kons Detail Setup".mysql_error());
	
	//echo "SELECT * FROM tmp_mst_set_nrkt WHERE nourut_id='$_GET[nourut]' AND trx_status='1' AND mcom_id='$company_id' AND user_id='$user_id' "		;
	$no=1;
	while ($data = mysql_fetch_array($que)){
		if ($data[nil]=="-") { $ikon="minus";} else { $ikon="plus";} 
		echo "<tr>
			<td><a href='?noset=$_GET[noset]&nourut=$_GET[nourut]&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&kd_pm=$ikon&kd_pil_acc=$data[acc]&level=$_GET[level]&nm_lvl=$_GET[nm_lvl]' onClick=\"klik_plus_minus('$data[acc]','$data[nil]')\"><span class='glyphicon glyphicon-$ikon'></a></td>
			<td>$data[acc]</td>
			<td>$data[nmp]</td>
			<td><a href='?noset=$_GET[noset]&nourut=$_GET[nourut]&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&kd_del_acc=$data[acc]&level=$_GET[level]&nm_lvl=$_GET[nm_lvl]' title='Hapus $data[acc]' data-placement='bottom'><span class='glyphicon glyphicon-arrow-up'></span></a>	
</td>
			</tr>";
		$no ++;
	}
	//<a href='' onClick=\"klik_hapus_akun('$data[acc]')\"  title='Hapus $data[acc]' data-placement='bottom'><span class='glyphicon glyphicon-arrow-up'></span></a>	
	?>
	
</table>


</body>
</html>
<?

if ($_GET[kd_del_acc]) {

	echo "<script> parent.document.getElementById(\"frame_pilih\").src='tampil_akun.php?noset=$_GET[noset]&level=$_GET[level]&nm_lvl=$_GET[nm_lvl]&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&nourut=$_GET[nourut]';</script>";
}


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
