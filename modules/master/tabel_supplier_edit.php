<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	include "../inc/inc_akses.php";
	include "../inc/inc_aed.php";
	
	$table_sup="mst_sup_".$company_id;
	
	if ($_GET[k_acc]=="ADD") {
		if ($tmbl_add<>1) {
			echo "<script language=javascript>
					alert (' ANDA TIDAK BERHAK MENGGUNAKAN MENU INI, SILAHKAN REFRESH HALAMAN WEB ANDA !!! ');
				</script>";		
			session_destroy();
			session_unset(); 
			exit ;
			}
	} else {
		if ($tmbl_edit<>2) {
			echo "<script language=javascript>
					alert (' ANDA TIDAK BERHAK MENGGUNAKAN MENU INI, SILAHKAN REFRESH HALAMAN WEB ANDA !!! ');
				</script>";		
			session_destroy();
			session_unset(); 
			exit ;
			}	
	}
	$alert="";
if ($_GET[btn_save]=="true") {
	
	$kode=$_POST[kd_sup];
	$nama=$_POST[kd_nmsup];	
	if (trim($kode)=="" || trim($nama)=="") {
		$alert="<div class='alert alert-danger'>Kode dan Nama supplier tidak boleh kosong !</div>";					
	} else {	
		if ($_GET[kd_aksi]=="ADD") {
			$query = mysql_query("SELECT kd_sup,nm_sup FROM $table_sup WHERE kd_sup='$kode' ", $conn) or die("Error Select Supplier Kode ".mysql_error());	
			$ada=mysql_num_rows($query);		
			if ($ada>0) {
				$alert="<div class='alert alert-danger'>Kode supplier sudah ada !</div>";			
			} else {
				$query = mysql_query("SELECT nm_sup FROM $table_sup WHERE nm_sup='$nama' ", $conn) or die("Error Select Supplier Name ".mysql_error());	
				$ada=mysql_num_rows($query);
				if ($ada>0) {
					$alert="<div class='alert alert-danger'>Nama supplier sudah ada !</div>";
				} else {
					$query = mysql_query("INSERT INTO $table_sup (mcom_id,kd_sup,nm_sup,sup_status,pemakai,tgl_input) VALUES ('$company_id','$kode','$nama','1','$user_id',now())", $conn) or die("Error Input Supplier Table ".mysql_error());	
					$alert="<div class='alert alert-success'>Data Supplier $nama berhasil ditambah !</div>";
				}
			}		
		} else {
			$query = mysql_query("SELECT nm_sup FROM $table_sup WHERE nm_sup='$nama' AND kd_sup<>'$kode' ", $conn) or die("Error Select Supplier Name ".mysql_error());	
			$ada=mysql_num_rows($query);
			if ($ada>0) {
				$alert="<div class='alert alert-danger'>Nama supplier sudah ada !</div>";
			} else {
				$query = mysql_query("UPDATE $table_sup SET nm_sup='$nama', pemakai='$user_id', tgl_input=now() WHERE kd_sup='$kode'", $conn) or die("Error Update Supplier Table ".mysql_error());	
					$alert="<div class='alert alert-success'>Data Supplier $kode berhasil diubah !</div>";
			}
		}
	}
}	
	
?>
<!DOCTYPE html>
<HTML>
<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.css">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet">
    <link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet">
    <link href="../../style/style_utama.css" rel="stylesheet">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

     <![if lt IE 9]>
      <script src="bootstrap/html5shiv.js"></script>
      <script src="bootstrap/respond.min.js"></script>
    <![endif]>
</HEAD>

<BODY>
<?

$kode="";
$nama="";

if ($_GET[kd_aksi]=="EDIT") {
	$query = mysql_query("SELECT * FROM $table_sup WHERE sup_status='1' AND mcom_id='$company_id' AND kd_sup='$_GET[kd_sup]' ORDER BY kd_sup ASC ", $conn) or die("Error Select Supplier Table ".mysql_error());	
	$data=mysql_fetch_array($query);
	$kode=$data[kd_sup];
	$nama=$data[nm_sup];	
} 

// save button

?>

<div class="form-inline">
    <input id="kd-sup" name="kd_sup" type="text" class="form-control" placeholder="Kode ?" value="<? echo $kode; ?>" size="15" maxlength="3" <? if ($_GET[kd_aksi]=="EDIT") { echo "readonly='readonly'";}  ?> autofocus>
    <input id="kd-nmsup" name="kd_nmsup" type="text" class="form-control" placeholder="Nama Supplier?" value="<? echo $nama; ?>" size="90" maxlength="100">
</div>
<div id="area_pesan_edit"><? echo $alert; ?></div>

<?



?>

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
<!-- session -->
<?
	
}

else { header("location:/glt/no_akses.htm"); }
?>
