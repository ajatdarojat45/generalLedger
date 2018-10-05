<?php
session_start();
if (isset($_SESSION['app_glt'])){

	include "../inc/inc_akses.php";

	//  Cek Hak Akses Tombol Add, Edit dan Delete
	include "../inc/inc_aed.php";
	include "../inc/func_modul.php";

	
?>
<html><head>
<title>GL TEMPO</title>

<!-- Bootstrap core CSS -->
<link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet">

</HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="../inc/jquery-1.3.2.js"></script>
<script type="text/javascript" src="../inc/ui.core.js"></script>
<script type="text/javascript" src="../inc/ui.mask.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	$.ui.mask.definitions['~'] = "[+-]";
	//$.ui.mask.defaults.allowPartials = true; //used to allowPartials on all instances of mask() on the page.

	$("#ssn").mask({mask: "#-##-##-##-##", allowPartials: true});
	
	$("#tanggal").mask({mask: "##-##-####", allowPartials: true});
	
	$("#tahun").mask({mask: "####", allowPartials: true});

	$("#bulan").mask({mask: "##", allowPartials: true});

	$("#nilai").mask({mask: "############.##", allowPartials: true});
	
	$("#kas_masuk").mask({mask: "KM******************", allowPartials: true});
	$("#kas_keluar").mask({mask: "KK******************", allowPartials: true});
	$("#bank_masuk").mask({mask: "BM******************", allowPartials: true});
	$("#bank_keluar").mask({mask: "BK******************", allowPartials: true});
		
});
</script>
<BODY>
<form method="POST" name="frm_trans_edit_detail" target="_self" >

<button type="button" class="btn-success" id="btn_test" onClick="cari_kode_akun()"> TEST MODAL TAMPIL DIATAS MODAL INI </button>
 
<table border="0" width="98%">
	<tr class="font_label_form">
		<td width="93%"><? echo "$_GET[kd_nav_det]"; ?> Detail Transaksi </td>
	</tr>
</table>
<table border="0" width="99%">
	<td height="9" colspan="5" class="font_field_form"><hr></td>
	<tr colspan=5>
	  <td colspan="5" align="center"><div class="field_head" style="height:20px;padding:10px; font-size:14px; font-family:arial;"><strong>&nbsp;No.Bukti : <? echo "$_GET[kd_nbk]"; ?>&nbsp;&nbsp;&nbsp; Tgl.Bukti : <? echo "$_GET[kd_tgp]"; ?>&nbsp;&nbsp;&nbsp;Total: <? $total=tampil_uang($_GET[tdebet],true); echo "$total"; ?></strong></div></td></tr>
	<tr colspan=5>
      <td height="9" colspan="5" class="font_field_form"><hr></td>
    <tr colspan=5>
    <td height="27" align="right"><span class="font_field_form"><strong>No. SEQ : </strong>        
    </span>
    <td height="27">    
    <td width="156" height="27">
    <input name="textfield" type="text" class="text" id="textfield" size="3">    
    <td width="403" height="27">    
    <td width="179" height="27">        
    </tr>
    <tr colspan=5>
      <td height="30" align="right"><strong><span class="font_field_form">Kode Perkiraan :</span></strong></td>
      <td height="30">&nbsp;</td>
      <td height="30" colspan="2"><input name="textfield2" type="text" class="text" id="textfield2" size="15">        <input name="textfield3" type="text" class="text" id="textfield3" size="65"></td>
      <td height="30">&nbsp;<a href="<? $nomor=$no_next-$dataPerPage; echo "tabel_cari_akun.php?company_id=$company_id&id_menu=$_GET[id_menu]&no_urut=$no_urut&page=$noPage"; ?>" class="tooltip"><img src="../../img/search_button.png"></a></td>
    </tr>
    <tr colspan=5>
      <td height="30" align="right"><strong><span class="font_field_form">Supplier :</span></strong></td>
      <td height="30">&nbsp;</td>
      <td height="30" colspan="2"><input name="textfield4" type="text" class="text" id="textfield4" size="4">        <input name="textfield5" type="text" class="text" id="textfield5" size="76"></td>
      <td height="30">&nbsp;<a href="<? $nomor=$no_next-$dataPerPage; echo "tabel_cari_akun.php?company_id=$company_id&id_menu=$_GET[id_menu]&no_urut=$no_urut&page=$noPage"; ?>" class="tooltip"><img src="../../img/search_button.png"></a></td>
    </tr>
    <tr colspan=5>
      <td height="30" align="right"><strong><span class="font_field_form">Posisi :</span></strong></td>
      <td height="30">&nbsp;</td>
      <td height="30"><table width="150" class="zebra_style_3">
        <tr>
          <? if ($_POST[LevelGroup1]) { $lvl=$_POST[LevelGroup1]; } else { $lvl=$data_hasil[12] ;} ?>
          <td width="69"><label>
            <input type="radio" name="LevelGroup1" value="1" <? if ($lvl=='1') { echo "Checked";} ?>>
            Debet
          </label></td>
          <td width="69"><label>
            <input type="radio" name="LevelGroup1" value="2" <? if ($lvl=='2') { echo "Checked";} ?>>
            Kredit</label></td>
        </tr>
      </table></td>
      <td height="30">&nbsp;</td>
      <td height="30">&nbsp;</td>
    </tr>
    <tr colspan=5>
      <td height="27" align="right"><strong><span class="font_field_form">Keterangan :</span></strong></td>
      <td height="27">&nbsp;</td>
      <td height="27" colspan="2"><input name="textfield6" type="text" class="text" id="textfield6" size="85"></td>
      <td height="27">&nbsp;</td>
    </tr>
    <tr colspan=5>
      <td height="27" align="right"><strong><span class="font_field_form">Jumlah :</span></strong></td>
      <td height="27">&nbsp;</td>
      <td height="27" colspan="2"><input name="textfield7" type="text" class="text" id="textfield7" size="20" style="text-align:right"></td>
      <td height="27">&nbsp;</td>
    </tr>
    <tr colspan=5>
      <td height="40" colspan="5">&nbsp;</td>
    </tr>
    <tr colspan=5>
	  <td width="130"></td>
	  <td></td>
	  <td height="25" colspan="3" class="font_field_form">Note : (<font color="#FF0000">*</font>) Can not be Empty</td></tr>
    <tr colspan=5>
		<td></td>
		<td width="2"></td>
	  	<td height="30" colspan="3" ><input type="submit" value="     S A V E      " name="btn_save_akun" class="tombol_1" onClick="return confirm('Simpan Data Tersebut ?')">&nbsp;&nbsp;&nbsp;<input type="submit" value="     GO BACK      " name="btn_go_back" class="tombol_1" )"></td>
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
	echo "Please <a href=../../home_login.php target=$_self>[Login]</a> First<br>";
	echo "</center>";

}
?>
