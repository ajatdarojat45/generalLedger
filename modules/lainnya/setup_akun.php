<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {
	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php";
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	include "../inc/func_modul.php";
	include "../inc/inc_aed.php";
	
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tgl_trans=substr($aktif_tgl,0,7);
	
	if (isset($_POST[btn_save_setup])) {
			
		// Simpan perubahan setup akun
		$query_data = mysql_query("UPDATE mst_company SET acc_kas='$_POST[kd_kas]',acc_bank='$_POST[kd_bank]',acc_bs='$_POST[kd_bs]' WHERE mcom_id='$company_id' ", $conn) or die("Error Update Acc Setup ".mysql_error());		
	
		echo "<script language=javascript>
				alert (' Data Setup Berhasil Disimpan !');
			</script>";
		}
	//unset($_POST[btn_save_setup]);
	
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

<!--onLoad="klik_reset_button();"-->
<BODY onLoad="klik_reset_button();">
<div class="navbar navbar-default navbar-form" role="navigation">
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	</div>
	<div class="navbar-collapse collapse">
		<ul class="nav navbar-right">
			<li>		
			<?
				include "../inc/inc_top_head.php";
			?>
			</li>
		</ul>
	</div>
</div>

<form name="frm_setup_akun_jurnal" class="form-inline" method="POST" >
<div class="panel panel-primary">
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-6">
				<span class="glyphicon glyphicon-cog"></span> SETUP AKUN JURNAL TRANSAKSI
			</div>
			<div class="col-xs-6 text-right">
			
				<button class="btn btn-success " type="button" name="btn-edit-setup" id="btn-edit" onClick="klik_edit();"><span class="glyphicon glyphicon-edit"></span></button>
				<button class="btn btn-success " type="submit" name="btn_save_setup" id="test-btn"><span class="glyphicon glyphicon-ok"></span></button>
				<!--<button class="btn btn-success " type="button" name="btn_save_setup" id="btn-save" onClick="klik_save();"><span class="glyphicon glyphicon-ok"></span></button>-->
				<button class="btn btn-success " type="button" name="btn-cancel-setup" id="btn-cancel" onClick="klik_cancel();"><span class="glyphicon glyphicon-remove"></span></button>
				<button class="btn btn-success " type="button" name="btn-refresh-setup" id="btn-refresh" onClick="klik_refresh();"><span class="glyphicon glyphicon-refresh"></span></button>
			</div>
		</div>
	</div>
	
	<?
		$tbl_mst_akun="mst_akun_".$company_id ;
		$tgl_trans=substr($aktif_tgl,0,7);
	
		$query_data = mysql_query("SELECT acc_kas,acc_bank,acc_bs FROM mst_company WHERE mcom_id='$company_id' ", $conn) or die("Error Select Acc Company ".mysql_error());		
		$data_hasil = mysql_fetch_array($query_data);		
		
		$kd_kas=$data_hasil[0];
		$kd_bank=$data_hasil[1];
		$kd_bs=$data_hasil[2];
		
		//echo "Kode Kas  : $_GET[kd_kas_cari]";
		//echo "Kode Bank : $_GET[kd_bank_cari]";
		//echo "Kode BS   : $_GET[kd_bs_cari]";
		
		if ($_GET[kd_kas_cari]!=$kd_kas && !empty($_GET[kd_kas_cari])) {
			$kd_kas=$_GET[kd_kas_cari];
		}
	
		if ($_GET[kd_bank_cari]!=$kd_bank && !empty($_GET[kd_bank_cari])) {
			$kd_bank=$_GET[kd_bank_cari];
		}
	
		if ($_GET[kd_bs_cari]!=$kd_bs && !empty($_GET[kd_bs_cari])) {
			$kd_bs=$_GET[kd_bs_cari];
		}
		
		$nmp_kas="";
		$nmp_bank="";
		$nmp_bs="";
	
		if (!empty($kd_kas)) {
			$query_data = mysql_query("SELECT nmp FROM ".$tbl_mst_akun." WHERE acc='$kd_kas'  AND acc_status='1' ", $conn) or die("Error Select Show Acc ".mysql_error());		
			$data_hasil1 = mysql_fetch_array($query_data);		
			$nmp_kas=$data_hasil1[0];
		}
		if (!empty($kd_bank)) {
			$query_data = mysql_query("SELECT nmp FROM ".$tbl_mst_akun." WHERE acc='$kd_bank'  AND acc_status='1' ", $conn) or die("Error Select Show Acc ".mysql_error());		
			$data_hasil2 = mysql_fetch_array($query_data);		
			$nmp_bank=$data_hasil2[0];
		}
		if (!empty($kd_bs)) {
			$query_data = mysql_query("SELECT nmp FROM ".$tbl_mst_akun." WHERE acc='$kd_bs'  AND acc_status='1' ", $conn) or die("Error Select Show Acc ".mysql_error());		
			$data_hasil3 = mysql_fetch_array($query_data);		
			$nmp_bs=$data_hasil3[0];		
		}
	?>
	
		<table border="0" align="center">
			<tr><td>
			<br/>Akun Transaksi Kas : <br/>
			<p><input name="kd_kas" id="kd-kas" type="text" class="form-control" value="<? echo "$kd_kas"; ?>" size="13" readonly >
			<input name="nmp_kas" id="nmp-kas" type="text" class="form-control" value="<? echo "$nmp_kas"; ?>" size="70" readonly >
			<button class="btn btn-success" type="button" name="btn-kas-reset" id="btn-kas-r" onClick="klik_reset(1);"> <span class="glyphicon glyphicon-trash"></span> </button> <button class="btn btn-success" type="button" name="btn-kas" id="btn-kas" onClick="cari_akun('',1)"><span class="glyphicon glyphicon-search"></span></button> </p><br/><td></tr>
			<tr><td>
			Akun Transaksi Bank : <br/>
			<p><input name="kd_bank" id="kd-bank" type="text" class="form-control" value="<? echo "$kd_bank"; ?>" size="13" readonly >
			<input name="nmp_bank" id="nmp-bank" type="text" class="form-control" value="<? echo "$nmp_bank"; ?>" size="70" readonly >
			<button class="btn btn-success" type="button" name="btn-bank-reset" id="btn-bank-r" onClick="klik_reset(2);"><span class="glyphicon glyphicon-trash"></span></button> <button class="btn btn-success" type="button" name="btn-bank" id="btn-bank" onClick="cari_akun('',2)"><span class="glyphicon glyphicon-search"></span></button> </p><br/></td></tr>
			<tr><td>
			Akun Transaksi BS : <br/>
			<p><input name="kd_bs" id="kd-bs" type="text" class="form-control" value="<? echo "$kd_bs"; ?>" size="13" readonly >
			<input name="nmp_bs" id="nmp-bs" type="text" class="form-control" value="<? echo "$nmp_bs"; ?>" size="70" readonly >
			<button class="btn btn-success" type="button" name="btn-bs-reset" id="btn-bs-r" onClick="klik_reset(3);"><span class="glyphicon glyphicon-trash"></span></button> <button class="btn btn-success" type="button" name="btn-bs" id="btn-bs" onClick="cari_akun('',3)"><span class="glyphicon glyphicon-search"></span></button> </p></td></tr>
		</table><br/>	
</form>
</div>

<div id="area_cari_data">  <!-- untuk mencari data akun -->
</div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<![if lt IE 9]>
	  <script src="../../bootstrap/html5shiv.js"></script>
	  <script src="../../bootstrap/respond.min.js"></script>
	<![endif]>
	
    <script src="../../js/jquery-1.11.0.min.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>

	<script src="aksi_setup_akun.js"></script>

</BODY>
</HTML>
<!-- session -->
<?	

//print_r($_POST);

}
else { header("location:/glt/no_akses.htm"); }
?>
