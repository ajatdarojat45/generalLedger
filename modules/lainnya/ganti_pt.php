<?php
session_start();
if (isset($_SESSION['app_glt']))
{
	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php";
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	
	if($_POST[btn_ok]){
		$company_name=$_POST[cmb_pil_pt];
		
		$que_user_pt = mysql_query("SELECT mcom_id,mcom_company_name,aktif_tgl FROM mst_company WHERE mcom_company_name = '$company_name'", $conn) or die("Error Select Company User ".mysql_error());		
		$rec_user_pt = mysql_fetch_array($que_user_pt);	
		
		$company_id=$rec_user_pt[0];
		$aktif_tgl=$rec_user_pt[2];
		
		$que_upd_user = mysql_query("UPDATE mst_login SET mcom_id_last='$company_id' WHERE mlog_username = '$_SESSION[app_glt]'", $conn) or die("Error Update Last Company User ".mysql_error());				
}	
?>
<HTML>
<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.min.css">
</HEAD>
<BODY>
<form name="frm_ubah_pt" method="POST" >
<table border="0" width="750">
	<tr class="font_label_form">
		<td colspan=3>Ubah Akses Data Perusahaan </td>
	</tr>
	<tr>
		<td colspan=3>&nbsp;</td>
	</tr>
	<tr class="font_field_form">
		<td width="14%">Pilih Perusahaan </td>
		<td width="1%">:</td>
		<td width="85%"><select name="cmb_pil_pt" class="font_footer" value="-pilih-">
		<?
			$que_pt = mysql_query("SELECT a.mcom_id AS mcom_id,b.mcom_company_name AS mcom_company_name FROM mst_granting_company AS a LEFT JOIN mst_company AS b ON a.mcom_id=b.mcom_id WHERE a.mgc_username='$_SESSION[app_glt]' ORDER BY a.mcom_id ASC ", $conn) or die("Error Query Company");
			while ($data_pt = mysql_fetch_array($que_pt)){
				echo "<option>$data_pt[1]</option>";
			}
		?>
		  </select>	  </td>
	</tr>
		<tr class="warning_form">
		<td colspan=2>&nbsp;</td>
		<td colspan=2>&nbsp;</td>
		</tr>
	<tr rowspan=4>
		<td>&nbsp;</td>
		<td>&nbsp;</td>		
      <td width="85%"><input type="submit" value=" P i l i h  " name="btn_ok" class="button">&nbsp;&nbsp;&nbsp;&nbsp;
      Lalu klik <a href="../..">[Refresh]</a></td>
	</tr>
</table>
</form>
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
	echo "Please <a href=../../index.php target=$_self>[Login]</a> First<br>";
	echo "</center>";

}
?>
