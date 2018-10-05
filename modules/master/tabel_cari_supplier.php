<?php 
session_start();
if (isset($_SESSION['app_glt'])) {
	if ($_POST[btn_go_back]) header( "location:tabel_supplier.php?id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]");

include "../inc/inc_akses.php"; 
include "../inc/inc_aed.php";

	if ($_GET[k_sup_del]) {
		$query_hapus=mysql_query("UPDATE mst_sup SET sup_status='0', pemakai='$user_id', tgl_input=now() WHERE kd_sup='$_GET[k_sup_del]' AND mcom_id='$company_id'", $conn) or die(mysql_error());
		if ($query_hapus) {
			echo "<div class='font_header'>( $_GET[k_sup_del] - $_GET[n_sup_del] ) Berhasil dihapus</div>";
		}
		else {
			echo "<div class='font_header'>( $_GET[k_sup_del] - $_GET[n_sup_del] ) Gagal dihapus</div>";				
		}
	}


?>
<HTML>
<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.min.css">
<script type="text/javascript" src="../inc/jquery-1.3.2.js"></script>
<script type="text/javascript" src="../inc/ui.core.js"></script>
<script type="text/javascript" src="../inc/ui.mask.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	$.ui.mask.definitions['~'] = "[+-]";
	
	$("#ssn").mask({mask: "###", allowPartials: true});
		
});
</script>
</HEAD>
<BODY>
<form name="cari_supplier" method="POST">
<table border=0  width="89%">
	<tr colspan="7">
		<td width="13%" class="font_field_form"><strong>Cari Supplier</strong></td>
	  <td width="6%"><input id="ssn" name="f_kd_ksup" type="text" class="text" value="<? if ($_POST[f_kd_ksup]) { echo "$_POST[f_kd_acc]";} ?>" size="4" maxlength="3"></td>
	  <td width="51%"><input name="f_kd_nsup" type="text" class="text" value="<? if ($_POST[f_kd_nsup]) { echo "$_POST[f_kd_nsup]";} ?>" size="60" maxlength="100"></td>
	  <td width="30%" colspan="2"><input type="submit" value=" FIND " name="btn_go_sup" class="tombol_1">&nbsp;&nbsp;<input type="submit" value=" GO BACK " name="btn_go_back" class="tombol_1"></td>
	</tr>
</table>
&nbsp;
<table border=0  width="85%" bgcolor="#FFFFFF">
	<tr>
		<th width="7%" class="field_head">No</th>
		<th width="17%" class="field_head">Kode Supplier </th>
		<th width="59%" class="field_head">Nama Supplier </th>
		<th width="17%" colspan="2" class="field_head">Editing</th>
	</tr>
<?php
	if ($_POST[btn_go_sup]) {
		if (!empty($_POST[f_kd_ksup])) {
			$query_data_sup = mysql_query("SELECT kd_sup,nm_sup FROM mst_sup WHERE  sup_status='1' AND mcom_id=$company_id AND kd_sup LIKE '$_POST[f_kd_ksup]%'  ORDER BY kd_sup ASC", $conn) or die("Error Select Find Supplier ".mysql_error());							
			//echo "SELECT acc,nmp FROM mst_akun WHERE mcom_id=$_GET[company_id] AND acc LIKE '$_POST[f_kd_acc]%'  ORDER BY acc ASC";
		} else {
			$namasup = str_replace("'","''","$_POST[f_kd_nsup]");
			$query_data_sup = mysql_query("SELECT kd_sup,nm_sup FROM mst_sup WHERE sup_status='1' AND mcom_id=$company_id AND nm_sup LIKE '%".$namasup."%' ORDER BY nm_sup ASC", $conn) or die("Error Select Find Supplier ".mysql_error());	
		}
		
		$jml_rec_data=mysql_num_rows($query_data_sup);		
		if ($jml_rec_data) {			
			$no = 1;
			while ($query_hasil = mysql_fetch_array($query_data_sup)){
				if ($tmbl_edit==2) {
					$view_tmbl_edit="<a href='tabel_supplier_edit.php?k_sup=$query_hasil[0]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/edit.png' width='15' height='18' border='0'></a>";
				} else {
					$view_tmbl_edit="";
				}
				if ($tmbl_del==3) {					
					$view_tmbl_del="<a href='tabel_cari_supplier.php?k_sup_del=$query_hasil[0]&n_sup_del=$query_hasil[1]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/delete.png' width='15' height='18' border='0' onClick=\"return confirm('Hapus Akun $query_hasil[0]-$query_hasil[1] ?')\"></a>";					
				} else {
					$view_tmbl_del="";
				}

			echo "<tr class='field_data'> 
				<td width='5%' height='18' align='right'>$no.</td>
				<td width='13%' height='18' align='center'>$query_hasil[0]</td>
				<td width='60%'>$query_hasil[1]</td>
				<td width='4%' align='center'>$view_tmbl_edit</td>
				<td width='5%' align='center'>$view_tmbl_del</td></tr>";
			$no ++;
			}
			echo "<tr><td colspan='5' class='field_head'></td></tr>";			
		}
		else{
			echo "<tr class='field_data'><td colspan='5'>Empty Data Record.</td></tr>";
		}
	}
?>
</table>
</form>
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
