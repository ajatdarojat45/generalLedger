<?php 
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {
	
include "../inc/inc_akses.php"; 

include "../inc/inc_aed.php";

$tbl_mst_sup="mst_sup_".$company_id;

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
<h3 class="text-center">Tabel Daftar Supplier </h3>
<table class="table table-hover table-condensed">
		<th>#</th>
		<th>Kode Supplier </th>
		<th>Nama Supplier</th>
		<th>Action</th>
<?php
	if ($_GET[btn_go_find]=='true') {
		if (empty($_POST[f_kd_sup]) && empty($_POST[f_kd_nmsup])) {
			$query_data_sup = mysql_query("SELECT kd_sup,nm_sup FROM ".$tbl_mst_sup." WHERE sup_status='1' AND mcom_id='$company_id' ORDER BY kd_sup ASC", $conn) or die("Error Select Find Supplier".mysql_error());							
		} else {
			if (!empty($_POST[f_kd_sup])) {
				$query_data_sup = mysql_query("SELECT kd_sup,nm_sup FROM ".$tbl_mst_sup." WHERE sup_status='1' AND mcom_id='$company_id' AND kd_sup LIKE '$_POST[f_kd_sup]%' ORDER BY kd_sup DESC", $conn) or die("Error Select Find Supplier".mysql_error());							
			} else {
				$query_data_sup = mysql_query("SELECT kd_sup,nm_sup FROM ".$tbl_mst_sup." WHERE sup_status='1' AND mcom_id='$company_id' AND nm_sup LIKE '%$_POST[f_kd_nmsup]%' ORDER BY nm_sup ASC", $conn) or die("Error Select Find Supplier".mysql_error());	
			} 		
		}
		
		$jml_rec_data=mysql_num_rows($query_data_sup);		
		if ($jml_rec_data) {			
			$no = 1;
			while ($query_hasil = mysql_fetch_array($query_data_sup)){
				if ($tmbl_edit==2) {
					$view_tmbl_edit="<button id='btn$no' type='button' class='btn btn-primary btn-xs' name='cari_sup' value='$query_hasil[0]' onClick=\"ambil_sup('$query_hasil[0]','$query_hasil[1]')\" ;\"> PILIH </button>";					
				} else {
					$view_tmbl_edit="";
				}
			echo "<tr> 
				<td>$no.</td>
				<td>$query_hasil[0]</td>
				<td>$query_hasil[1]</td>
				<td>$view_tmbl_edit</td>
				</tr>";
			$no ++;
			}
			echo "<tr><td colspan='4' align='center'>EOF</td></tr>";
		}
		else{
			echo "<tr><td colspan='4'>Empty Data Record.</td></tr>";
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
