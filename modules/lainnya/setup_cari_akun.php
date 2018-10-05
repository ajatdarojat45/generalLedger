<?php 
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {
	
include "../inc/inc_akses.php"; 

include "../inc/inc_aed.php";

$tbl_mst_akun="mst_akun_".$company_id;

?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>GL TEMPO</title>

<!-- Bootstrap core CSS -->
<link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet">
<link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet">
<link href="../../style/style_utama.css" rel="stylesheet">

</HEAD>
<BODY>
<table class="table" border=0  width="90%" bgcolor="#FFFFFF">
	<tr height="30">
		<th width="7%">#</th>
		<th width="17%">Kode Perkiraan </th>
		<th width="59%">Nama Perkiraan</th>
		<th width="17%">Action</th>
	</tr>
<?php
	if ($_GET[btn_go_find]=='true') {
		if ($_GET[kode]=='1') {
			if (empty($_POST[f_kd_acc]) && empty($_POST[f_kd_nmp])) {
				$query_data_akun = mysql_query("SELECT acc,nmp FROM ".$tbl_mst_akun." WHERE acc_status='1' AND mcom_id='$company_id' AND pusat='K' AND level='5' ORDER BY acc ASC", $conn) or die("Error Select Find Akun ".mysql_error());							
			} else {
				if (!empty($_POST[f_kd_acc])) {
					$query_data_akun = mysql_query("SELECT acc,nmp FROM ".$tbl_mst_akun." WHERE acc_status='1' AND mcom_id='$company_id' AND acc LIKE '$_POST[f_kd_acc]%' AND pusat='K'  AND level='5' ORDER BY acc ASC", $conn) or die("Error Select Find Akun ".mysql_error());							
					//echo "SELECT acc,nmp FROM mst_akun WHERE mcom_id=$_GET[company_id] AND acc LIKE '$_POST[f_kd_acc]%'  ORDER BY acc ASC";
				} else {
					$query_data_akun = mysql_query("SELECT acc,nmp FROM ".$tbl_mst_akun." WHERE acc_status='1' AND mcom_id='$company_id' AND nmp LIKE '%$_POST[f_kd_nmp]%' AND pusat='K'  AND level='5' ORDER BY nmp ASC", $conn) or die("Error Select Find Akun ".mysql_error());	
				} 		
			}
			
		} else if ($_GET[kode]=='2') {
			if (empty($_POST[f_kd_acc]) && empty($_POST[f_kd_nmp])) {
				$query_data_akun = mysql_query("SELECT acc,nmp FROM ".$tbl_mst_akun." WHERE acc_status='1' AND mcom_id='$company_id' AND pusat='B' AND level='5' ORDER BY acc ASC", $conn) or die("Error Select Find Akun ".mysql_error());							
			} else {
				if (!empty($_POST[f_kd_acc])) {
					$query_data_akun = mysql_query("SELECT acc,nmp FROM ".$tbl_mst_akun." WHERE acc_status='1' AND mcom_id='$company_id' AND acc LIKE '$_POST[f_kd_acc]%' AND pusat='B'  AND level='5' ORDER BY acc ASC", $conn) or die("Error Select Find Akun ".mysql_error());							
					//echo "SELECT acc,nmp FROM mst_akun WHERE mcom_id=$_GET[company_id] AND acc LIKE '$_POST[f_kd_acc]%'  ORDER BY acc ASC";
				} else {
					$query_data_akun = mysql_query("SELECT acc,nmp FROM ".$tbl_mst_akun." WHERE acc_status='1' AND mcom_id='$company_id' AND nmp LIKE '%$_POST[f_kd_nmp]%' AND pusat='B'  AND level='5' ORDER BY nmp ASC", $conn) or die("Error Select Find Akun ".mysql_error());	
				} 		
			}
		} if ($_GET[kode]=='3') {
			if (empty($_POST[f_kd_acc]) && empty($_POST[f_kd_nmp])) {
				$query_data_akun = mysql_query("SELECT acc,nmp FROM ".$tbl_mst_akun." WHERE acc_status='1' AND mcom_id='$company_id' AND pusat='K' AND level='5' ORDER BY acc ASC", $conn) or die("Error Select Find Akun ".mysql_error());							
			} else {
				if (!empty($_POST[f_kd_acc])) {
					$query_data_akun = mysql_query("SELECT acc,nmp FROM ".$tbl_mst_akun." WHERE acc_status='1' AND mcom_id='$company_id' AND acc LIKE '$_POST[f_kd_acc]%' AND pusat='K'  AND level='5' ORDER BY acc ASC", $conn) or die("Error Select Find Akun ".mysql_error());							
					//echo "SELECT acc,nmp FROM mst_akun WHERE mcom_id=$_GET[company_id] AND acc LIKE '$_POST[f_kd_acc]%'  ORDER BY acc ASC";
				} else {
					$query_data_akun = mysql_query("SELECT acc,nmp FROM ".$tbl_mst_akun." WHERE acc_status='1' AND mcom_id='$company_id' AND nmp LIKE '%$_POST[f_kd_nmp]%' AND pusat='K'  AND level='5' ORDER BY nmp ASC", $conn) or die("Error Select Find Akun ".mysql_error());	
				} 		
			}
		}
		
		$jml_rec_data=mysql_num_rows($query_data_akun);		
		if ($jml_rec_data) {			
			$no = 1;
			while ($query_hasil = mysql_fetch_array($query_data_akun)){
				if ($tmbl_edit==2) {
					if ($_GET[kode]=='1') {
						$view_tmbl_edit="<button id='btn$no' type='button' class='btn btn-primary btn-xs' name='cari_akun' value='$query_hasil[0]' onClick=\"ambil_akun_kas('$query_hasil[0]','$query_hasil[1]')\" ;\"> PILIH </button>";
					} else if ($_GET[kode]=='2') {
						$view_tmbl_edit="<button id='btn$no' type='button' class='btn btn-primary btn-xs' name='cari_akun' value='$query_hasil[0]' onClick=\"ambil_akun_bank('$query_hasil[0]','$query_hasil[1]')\" ;\"> PILIH </button>";
					} if ($_GET[kode]=='3') {
						$view_tmbl_edit="<button id='btn$no' type='button' class='btn btn-primary btn-xs' name='cari_akun' value='$query_hasil[0]' onClick=\"ambil_akun_bs('$query_hasil[0]','$query_hasil[1]')\" ;\"> PILIH </button>";
					}
					//$view_tmbl_edit="<a href='tabel_akun_edit.php?k_acc=$query_hasil[0]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/edit.png' width='15' height='18' border='0'></a>";
				} else {
					$view_tmbl_edit="";
				}
			echo "<tr> 
				<td width='5%' height='18' align='right'>$no.</td>
				<td width='13%' height='18' align='center'>$query_hasil[0]</td>
				<td width='60%'>$query_hasil[1]</td>
				<td width='4%' align='center'>$view_tmbl_edit</td>
				</tr>";
			$no ++;
			}
			echo "<tr><td colspan='5' align='center'>EOF</td></tr>";
		}
		else{
			echo "<tr><td colspan='5'>Empty Data Record.</td></tr>";
		}
	}
?>
</table>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<![if lt IE 9]>
	  <script src="../../bootstrap/html5shiv.js"></script>
	  <script src="../../bootstrap/respond.min.js"></script>
	<![endif]>
	
    <script src="../../js/jquery-1.11.0.min.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>

</BODY>
</HTML>
<?	
}
else
{
	echo"<title>Manage Care</title>
				<link href=\"../../style\style.css\" rel=stylesheet>";
	echo "<center>";
	echo "<h3>Acess Denied</h3>";
	echo "Please <a href=../../index.php target=$_self>[Login]</a> First<br>";
	echo "</center>";

}
?>
