<?php 
session_start();
if (isset($_SESSION['app_glt'])) {
//
include "../inc/inc_akses.php"; 

include "../inc/inc_aed.php";

//$user_id=$_SESSION[app_glt];
//echo "Add = $tmbl_add - Edit = $tmbl_edit - Delete = $tmbl_del  < $_GET[id_menu] - $user_id >";   
//
//echo "Company ID : - $_GET[company_id] -";
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
	//$.ui.mask.defaults.allowPartials = true; //used to allowPartials on all instances of mask() on the page.
	
	//$("#date")
	//	.mask({mask: "##-##-####"})
	//	.change(function() { alert("date changed!")}); ;
	//$("#phone").mask({mask: "(###) ###-####-####"})
	//	.change(function() { alert("phone changed!")}); ;
	//$("#phoneExt").mask({mask: "(###) ###-#### x#####"});
	//$("#iphone").mask({mask: "+33 ### ### ###"});
	//$("#tin").mask({mask: "##-#######"});
	
	$("#ssn").mask({mask: "###", allowPartials: true});
		
});
</script>
</HEAD>
<BODY>
<form name="cari_supplier" method="POST">
<table border=0  width="85%">
	<tr colspan="7">
		<td width="14%" class="font_field_form"><strong>Cari Supplier</strong></td>
	  <td width="9%"><input id="ssn" name="f_kd_ksup" type="text" class="textbox_login" value="<? if ($_POST[f_kd_ksup]) { echo "$_POST[f_kd_acc]";} ?>" size="15" maxlength="13"></td>
	  <td width="68%"><input name="f_kd_nsup" type="text" class="textbox_login" value="<? if ($_POST[f_kd_nsup]) { echo "$_POST[f_kd_nsup]";} ?>" size="70" maxlength="100"></td>
	  <td width="9%" colspan="2"><input type="submit" value=" GO " name="btn_go_sup" class="button"></td>
	</tr>
</table>
&nbsp;
<table border=0  width="85%">
	<tr class="font_label_field">
		<th width="7%">No</th>
		<th width="17%">Kode Supplier </th>
		<th width="59%">Nama Supplier </th>
		<th width="17%" colspan="2">Editing</th>
	</tr>
<?php
	if ($_POST[btn_go_sup]) {
		if (!empty($_POST[f_kd_ksup])) {
			$query_data_sup = mysql_query("SELECT kd_sup,nm_sup FROM mst_sup WHERE mcom_id=$company_id AND kd_sup LIKE '$_POST[f_kd_ksup]%'  ORDER BY kd_sup ASC", $conn) or die("Error Select Find Supplier ".mysql_error());							
			//echo "SELECT acc,nmp FROM mst_akun WHERE mcom_id=$_GET[company_id] AND acc LIKE '$_POST[f_kd_acc]%'  ORDER BY acc ASC";
		} else {
			$query_data_sup = mysql_query("SELECT kd_sup,nm_sup FROM mst_sup WHERE mcom_id=$company_id AND nm_sup LIKE '%".$_POST[f_kd_nsup]."%' ORDER BY nm_sup ASC", $conn) or die("Error Select Find Supplier ".mysql_error());	
		}
		
		$jml_rec_data=mysql_num_rows($query_data_sup);		
		if ($jml_rec_data) {			
			$no = 1;
			while ($query_hasil = mysql_fetch_array($query_data_sup)){
				if ($tmbl_edit==2) {
					$view_tmbl_edit="<a href='tabel_sup_edit.php?k_ksup=$query_hasil[0]&id_menu=$_GET[id_menu]'><img src='../../img/edit.png' width='15' height='18' border='0'></a>";
				} else {
					$view_tmbl_edit="";
				}
				if ($tmbl_del==3) {
					$view_tmbl_del="<a href='tabel_sup_delete.php?k_ksup=$query_hasil[0]'><img src='../../img/delete.png' border='0'></a>";
				} else {
					$view_tmbl_del="";
				}
			echo "<tr class='zebra_style_2'> 
				<td width='5%' height='18' align='right'>$no.</td>
				<td width='13%' height='18' align='center'>$query_hasil[0]</td>
				<td width='60%'>$query_hasil[1]</td>
				<td width='4%' align='center'>$view_tmbl_edit</td>
				<td width='5%' align='center'>$view_tmbl_del</td></tr>";
			$no ++;
			}
		}
		else{
			echo "<tr class='zebra_style_2'><td colspan='8'>Empty Data Record.</td></tr>";
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
