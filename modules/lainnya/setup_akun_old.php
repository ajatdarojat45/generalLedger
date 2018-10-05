<?php
session_start();
if (isset($_SESSION['app_glt'])) {
	include "../inc/inc_akses.php";
	if($_POST[btn_save_setup]){
		// Simpan perubahan setup akun
		$query_data = mysql_query("UPDATE mst_company SET acc_kas='$_POST[kd_kas]',acc_bank='$_POST[kd_bank]',acc_bs='$_POST[kd_bs]' WHERE mcom_id='$company_id' ", $conn) or die("Error Update Acc Setup ".mysql_error());		
	
		echo "<script language=javascript>
				alert (' Data Setup Berhasil Disimpan !');
			</script>";
	}
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
<BODY>
<div class="panel panel-primary">
	<div class="panel-heading"><span class="glyphicon glyphicon-cog"></span> SETUP AKUN JURNAL TRANSAKSI
	</div>
	
	<?
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
			$query_data = mysql_query("SELECT nmp FROM mst_akun WHERE acc='$kd_kas'  AND acc_status='1' ", $conn) or die("Error Select Show Acc ".mysql_error());		
			$data_hasil1 = mysql_fetch_array($query_data);		
			$nmp_kas=$data_hasil1[0];
		}
		if (!empty($kd_bank)) {
			$query_data = mysql_query("SELECT nmp FROM mst_akun WHERE acc='$kd_bank'  AND acc_status='1' ", $conn) or die("Error Select Show Acc ".mysql_error());		
			$data_hasil2 = mysql_fetch_array($query_data);		
			$nmp_bank=$data_hasil2[0];
		}
		if (!empty($kd_bs)) {
			$query_data = mysql_query("SELECT nmp FROM mst_akun WHERE acc='$kd_bs'  AND acc_status='1' ", $conn) or die("Error Select Show Acc ".mysql_error());		
			$data_hasil3 = mysql_fetch_array($query_data);		
			$nmp_bs=$data_hasil3[0];		
		}
	?>
	
	<form name="frm_setup_akun_jurnal" class="form-horizontal" method="POST" >
		<input name="kd_kas" type="text" class="text" value="<? echo "$kd_kas"; ?>" size="13" readonly >
	
	<table width="790" border="0">
		<tr><td colspan="5"><hr></hr></td></tr>
		<tr colspan=5>
		  <td height="17" class="font_field_form">      
		  <td align="right">&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<tr colspan=5>
		  <td width="108" height="24" class="font_field_form">  Akun Transaksi Kas  
		  <td width="5" align="right"> : </td>
		  <td width="96"></td>
		  <td width="376"><input name="nmp_kas" type="text" class="text" value="<? echo "$nmp_kas"; ?>" size="70" readonly ></td>
		  <td width="183"><a href="<? echo "setup_cari_akun.php?company_id=$company_id&id_menu=$_GET[id_menu]&jenis_kode=KAS&kd_kas_cari=$kd_kas&kd_bank_cari=$kd_bank&kd_bs_cari=$kd_bs"; ?>" class="tooltip"><img src="../../img/search_button.png"></img><span>Cari data akun kas</span></a></td>
		</tr>	
		<tr colspan=5>
		  <td width="108" class="font_field_form"> Akun Transaksi Bank  
		<td width="5" align="right"> : </td>
		<td><input name="kd_bank" type="text" class="text" value="<? echo "$kd_bank"; ?>" size="13" readonly ></td>
		<td><input name="nmp_bank" type="text" class="text" value="<? echo "$nmp_bank"; ?>" size="70" readonly ></td>
		<td><a href="<? echo "setup_cari_akun.php?company_id=$company_id&id_menu=$_GET[id_menu]&jenis_kode=BANK&kd_kas_cari=$kd_kas&kd_bank_cari=$kd_bank&kd_bs_cari=$kd_bs"; ?>" class="tooltip"><img src="../../img/search_button.png"></img><span>Cari data akun bank</span></a></td>
		</tr>
		<tr colspan=5>
		<td width="108" class="font_field_form">  Akun Transaksi BS  </td>
		<td width="5" align="right"> : </td>
		<td><input name="kd_bs" type="text" class="text" value="<? echo "$kd_bs"; ?>" size="13" readonly ></td>
		<td><input name="nmp_bs" type="text" class="text" value="<? echo "$nmp_bs"; ?>" size="70" readonly ></td>
		<td><a href="<? echo "setup_cari_akun.php?company_id=$company_id&id_menu=$_GET[id_menu]&jenis_kode=BS&kd_kas_cari=$kd_kas&kd_bank_cari=$kd_bank&kd_bs_cari=$kd_bs"; ?>" class="tooltip"><img src="../../img/search_button.png"></img><span>Cari data akun bs</span></a></td>
		</tr>
		<tr colspan=5>
			<td width="108" height="15"></td>
			<td width="5" height="15"></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>	
		<tr><td colspan="5"><hr></hr></td></tr>
		<tr colspan=5>
			<td width="108" height="22"></td>
			<td width="5"></td>
			<td><input type="submit" value="   S A V E    " name="btn_save_setup" class="tombol_1"></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td></tr>
	  </table>
	</form>
</div>

</BODY>
</HTML>
<!-- session -->
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
