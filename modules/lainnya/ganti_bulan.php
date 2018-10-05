<?php
session_start();
if (isset($_SESSION['app_glt']))
{
	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php";
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	include "../inc/func_sysdate.php";
	//include "../inc/gl_top_head.php";
?>
<HTML>

<script type="text/javascript">
    function myfunc () {
        document.getElementById('gl_top_head').location.reload();
    }
    window.onload = myfunc;
</script>

<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.min.css">
</HEAD>
<BODY>
<form name="frm_ubah_bulan" method="POST" >
<table border="0" width="839">
	<tr class="font_label_form">
		<td colspan=5>Ubah Periode Aktif </td>
	</tr>
	<tr>
		<td colspan=5>&nbsp;</td>
	</tr>
	<tr class="font_field_form">
		<td width="10%">Periode Aktif</td>
		<td width="1%">:</td>
		<td width="21%">
		Bulan 
				

		<select name="cmb_bln1" class="font_footer" value="<? $bulan=substr($aktif_tgl,5,2); echo $bulan;?>" disabled="disabled">
		<? 
			$bulan=get_bulan($aktif_tgl); 
			echo "<option selected>".$bulan."</option>"; 
		?>
		 </select></td>
	    <td width="31%"><label>
	      Tahun 
	      <input name="txt_thn1" type="text" value="<? echo substr($aktif_tgl,0,4); ?>" size="4" disabled="disabled">
	    </label></td>
	    <td width="37%">&nbsp;</td>
	</tr>
		<tr class="font_field_form">
		  <td width="10%">Periode Input </td>
		<td>:</td>
		<td>Bulan 
		  <? if ($_POST[btn_ubah_per]){ ?>
			  <select name="cmb_bln2" class="font_footer" value="<? $bulan=substr($_POST[cmb_bln2],5,2); echo $bulan;?>" >
			  <option value="01" <? if (substr($_POST[cmb_bln2],5,2)=='01') { echo "selected";} ?>>Januari</option>
			  <option value="02" <? if (substr($_POST[cmb_bln2],5,2)=='02') { echo "selected";} ?>>Februari</option>
			  <option value="03" <? if (substr($_POST[cmb_bln2],5,2)=='03') { echo "selected";} ?>>Maret</option>
			  <option value="04" <? if (substr($_POST[cmb_bln2],5,2)=='04') { echo "selected";} ?>>April</option>
			  <option value="05" <? if (substr($_POST[cmb_bln2],5,2)=='05') { echo "selected";} ?>>Mei</option>
			  <option value="06" <? if (substr($_POST[cmb_bln2],5,2)=='06') { echo "selected";} ?>>Juni</option>
			  <option value="07" <? if (substr($_POST[cmb_bln2],5,2)=='07') { echo "selected";} ?>>Juli</option>
			  <option value="08" <? if (substr($_POST[cmb_bln2],5,2)=='08') { echo "selected";} ?>>Agustus</option>
			  <option value="09" <? if (substr($_POST[cmb_bln2],5,2)=='09') { echo "selected";} ?>>September</option>
			  <option value="10" <? if (substr($_POST[cmb_bln2],5,2)=='10') { echo "selected";} ?>>Oktober</option>
			  <option value="11" <? if (substr($_POST[cmb_bln2],5,2)=='11') { echo "selected";} ?>>Nopember</option>
			  <option value="12" <? if (substr($_POST[cmb_bln2],5,2)=='12') { echo "selected";} ?>>Desember</option>
		  <? 
		  }else { ?>
		  <select name="cmb_bln2" class="font_footer" value="<? $bulan=substr($tgl_input,5,2); echo $bulan;?>" >
          <option value="01" <? if (substr($tgl_input,5,2)=='01') { echo "selected";} ?>>Januari</option>
          <option value="02" <? if (substr($tgl_input,5,2)=='02') { echo "selected";} ?>>Februari</option>
          <option value="03" <? if (substr($tgl_input,5,2)=='03') { echo "selected";} ?>>Maret</option>
          <option value="04" <? if (substr($tgl_input,5,2)=='04') { echo "selected";} ?>>April</option>
          <option value="05" <? if (substr($tgl_input,5,2)=='05') { echo "selected";} ?>>Mei</option>
          <option value="06" <? if (substr($tgl_input,5,2)=='06') { echo "selected";} ?>>Juni</option>
          <option value="07" <? if (substr($tgl_input,5,2)=='07') { echo "selected";} ?>>Juli</option>
          <option value="08" <? if (substr($tgl_input,5,2)=='08') { echo "selected";} ?>>Agustus</option>
          <option value="09" <? if (substr($tgl_input,5,2)=='09') { echo "selected";} ?>>September</option>
          <option value="10" <? if (substr($tgl_input,5,2)=='10') { echo "selected";} ?>>Oktober</option>
          <option value="11" <? if (substr($tgl_input,5,2)=='11') { echo "selected";} ?>>Nopember</option>
          <option value="12" <? if (substr($tgl_input,5,2)=='12') { echo "selected";} ?>>Desember</option>
		  <? } ?>
        </select></td>
		<td>Tahun
		  <input name="txt_thn2" type="text" size="4" value="<? if ($_POST[btn_ubah_per]) {
		  		echo $_POST[txt_thn2];
			} else {
				$mtgl=substr($tgl_input,0,4);
				echo "$mtgl";
			} 
			?>"></td>
		<td>&nbsp;</td>
		</tr>
	    <tr rowspan=4>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
	      <td colspan="3"></td>
    </tr>
    <tr rowspan=4>
		<td>&nbsp;</td>
		<td>&nbsp;</td>		
      <td colspan="3"><input type="submit" value=" Ubah Periode  " name="btn_ubah_per" class="button" onClick="myfunc()">
        &nbsp;&nbsp;&nbsp;</td>
	</tr>
</table>
</form>
<?
	if($_POST[btn_ubah_per]){

	$tgl_input_baru=$_POST[txt_thn2]."-".$_POST[cmb_bln2]."-01";

	//echo $tgl_input_baru;
	
	$tgl_input=$tgl_input_baru;
	
	//echo $tgl_input;
	$que_upd_user = mysql_query("UPDATE mst_login SET tgl_input='$tgl_input' WHERE mlog_username = '$_SESSION[app_glt]'", $conn) or die("Error Update Last Company User ".mysql_error());
		
	include "../admin/main_page.php";

	}
?>
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
