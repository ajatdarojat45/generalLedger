<?php 
session_start();
if (isset($_SESSION['app_glt'])) {
	if ($_POST[btn_go_back]) header( "location:tabel_akun.php?id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]");
//
include "../inc/inc_akses.php"; 

include "../inc/inc_aed.php";

//$user_id=$_SESSION[app_glt];
//echo "Add = $tmbl_add - Edit = $tmbl_edit - Delete = $tmbl_del  < $_GET[id_menu] - $user_id >";   
//
//echo "Company ID : - $_GET[company_id] -";

if ($_GET[del_acc_k]) {
		$query_hapus=mysql_query("UPDATE mst_akun SET acc_status='0', pemakai='$user_id', tgl_input=now() WHERE acc='$_GET[del_acc_k]' AND mcom_id='$company_id'", $conn) or die(mysql_error());
		//$data_hasil = mysql_fetch_array($query_data);		
		if ($query_hapus) {
			echo "<div class='font_header'>( $_GET[del_acc_k] - $_GET[del_acc_n] ) Berhasil dihapus</div>";
		}
		else {
			echo "<div class='font_header'>( $_GET[del_acc_k] - $_GET[del_acc_n] ) Gagal dihapus</div>";				
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

	$("#ssn").mask({mask: "#-##-##-##-##", allowPartials: true});
	
});
</script>
</HEAD>
<BODY>
<form name="cari_akun" method="POST">
<table border=0  width="102%">
	<tr colspan="7">
		<td width="12%" class="font_field_form">Cari Akun</td>
	  <td width="12%"><input id="ssn" name="f_kd_acc" type="text" class="text" value="<? if ($_POST[f_kd_acc]) { echo "$_POST[f_kd_acc]";} ?>" size="15" maxlength="13"></td>
	  <td width="44%"><input name="f_kd_nmp" type="text" class="text" value="<? if ($_POST[f_kd_nmp]) { echo "$_POST[f_kd_nmp]";} ?>" size="70" maxlength="100"></td>
	  <td width="32%" colspan="2"><input type="submit" value=" FIND " name="btn_go" class="tombol_1">&nbsp;&nbsp;&nbsp;<input type="submit" value=" GO BACK " name="btn_go_back" class="tombol_1"></td>
	</tr>
</table>
&nbsp;
<table border=0  width="90%" bgcolor="#FFFFFF">
	<tr height="30">
		<th width="7%" class='field_head'>No.Urut</th>
		<th width="17%" class='field_head'>Kode Perkiraan </th>
		<th width="59%" class='field_head'>Nama Perkiraan</th>
		<th width="17%" colspan="2" class='field_head'>Editing</th>
	</tr>
<?php
	if ($_POST[btn_go]) {
		if (!empty($_POST[f_kd_acc])) {
			$query_data_akun = mysql_query("SELECT acc,nmp FROM mst_akun WHERE acc_status='1' AND mcom_id='$company_id' AND acc LIKE '$_POST[f_kd_acc]%'  ORDER BY acc ASC", $conn) or die("Error Select Find Akun ".mysql_error());							
			//echo "SELECT acc,nmp FROM mst_akun WHERE mcom_id=$_GET[company_id] AND acc LIKE '$_POST[f_kd_acc]%'  ORDER BY acc ASC";
		} else {
			$query_data_akun = mysql_query("SELECT acc,nmp FROM mst_akun WHERE acc_status='1' AND mcom_id='$company_id' AND nmp LIKE '%$_POST[f_kd_nmp]%' ORDER BY nmp ASC", $conn) or die("Error Select Find Akun ".mysql_error());	
		}
		
		$jml_rec_data=mysql_num_rows($query_data_akun);		
		if ($jml_rec_data) {			
			$no = 1;
			while ($query_hasil = mysql_fetch_array($query_data_akun)){
				if ($tmbl_edit==2) {
					$view_tmbl_edit="<a href='tabel_akun_edit.php?k_acc=$query_hasil[0]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/edit.png' width='15' height='18' border='0'></a>";
				} else {
					$view_tmbl_edit="";
				}
				if ($tmbl_del==3) {
					$view_tmbl_del="<a href='tabel_cari_akun.php?del_acc_k=$query_hasil[0]&del_acc_n=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/delete.png' width='15' height='18' border='0' onClick=\"return confirm('Hapus Akun $query_hasil[0]-$query_hasil[1] ?')\"></a>";
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
			echo "<tr class='field_head'><td colspan='5' align='center'>EOF</td></tr>";
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
