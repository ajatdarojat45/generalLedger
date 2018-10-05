<?php
session_start();
if (isset($_SESSION['app_glt'])){
	
	if ($_POST[add_new_nbk]) header("location:trans_edit_detail.php?kd_nav_det=ADD&kd_nav=$_GET[kd_nav]&kd_tgp=$_POST[kd_tgp]&kd_nbk=$_POST[kd_nbk]&jenis_trans=$_GET[jenis_trans]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]&tdebet=$_GET[tdebet]&tkredit=$_GET[tkredit]") ;
	
	if ($_POST[btn_go_back]) header("location:trans_kas.php?id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]");

	include "../inc/inc_akses.php";
	include "../inc/func_modul.php";	
	include "../inc/inc_aed.php"; //  Cek Hak Akses Tombol Add, Edit dan Delete

	if ($_GET[k_nav]=="ADD") {
		if ($tmbl_add<>1) {
			echo "<script language=javascript>
					alert (' ANDA TIDAK BERHAK MENGGUNAKAN MENU INI, SILAHKAN REFRESH HALAMAN WEB ANDA !!! ');
				</script>";		
			session_destroy();
			session_unset(); 
			exit ;			}
	} else {
		if ($tmbl_edit<>2) {
			echo "<script language=javascript>
					alert (' ANDA TIDAK BERHAK MENGGUNAKAN MENU INI, SILAHKAN REFRESH HALAMAN WEB ANDA !!! ');
				</script>";		
			session_destroy();
			session_unset(); 
			exit ;}				
	}
	if ($_GET[kd_temp]=="YA"){
		$qry_tmp_del = mysql_query("DELETE FROM trx_tmp_caseglt WHERE user_id='$user_id'", $conn) or die("Error Delete Temp Caseglt ".mysql_error());	
		$qry_tmp_ins = mysql_query("INSERT INTO trx_tmp_caseglt (SELECT * FROM trx_caseglt WHERE nbk='$_GET[kd_nbk]' AND trx_status='1' AND mcom_id='$company_id')", $conn) or die("Error Insert Temp Caseglt ".mysql_error());			
		$qry_tmp_upd = mysql_query("UPDATE trx_tmp_caseglt SET user_id='$user_id' WHERE nbk='$_GET[kd_nbk]'", $conn) or die("Error Insert Temp Caseglt ".mysql_error());					
	}
?>
<HTML>
<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.min.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/gltempo/css/black-tie/jquery-ui-1.10.4.custom.css" rel="stylesheet">
<script src="/gltempo/js/jquery-1.10.2.js"></script>
<script src="/gltempo/js/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	//$.ui.mask.definitions['~'] = "[+-]";
	//$.ui.mask.defaults.allowPartials = true; //used to allowPartials on all instances of mask() on the page.
	//$("#nilai").mask({mask: "############.##", allowPartials: true});
	//$("#kas_masuk").mask({mask: "KM******************", allowPartials: true});
	//$("#kas_keluar").mask({mask: "KK******************", allowPartials: true});
	//$("#bank_masuk").mask({mask: "BM******************", allowPartials: true});
	//$("#bank_keluar").mask({mask: "BK******************", allowPartials: true});		
	//{dateFormat: 'dd-mm-yyyy'}
	
	$("#tgl_kalender1").datepicker({dateFormat: "dd-mm-yy"});	
	$("#tgl_kalender2").datepicker({dateFormat: "dd-mm-yy"});	
	
});
</script>

</HEAD>
<BODY>
<form method="POST" name="frm_trans_edit" target="_self" >
<table border="0" width="98%">
	<tr class="font_label_form">
		<td width="93%"><? if ($_GET[kd_nav]=="ADD"){echo "Tambah Transaksi Baru";} else {echo "Ubah Transaksi ";}; ?></td>
	</tr>
</table>

<?
	$qry_tmp = mysql_query("SELECT * FROM trx_tmp_caseglt WHERE user_id='$user_id'", $conn) or die("Error Select Temp Caseglt ".mysql_error());		
	$data_hasil = mysql_fetch_array($qry_tmp);
	$qry_tmp = mysql_query("SELECT a.acc as acc,b.nmp as nmp FROM trx_tmp_caseglt as a left join mst_akun as b on a.acc=b.acc WHERE a.user_id='$user_id' AND a.seq='000' ", $conn) or die("Error Select Temp Caseglt ".mysql_error());
	$data_top = mysql_fetch_array($qry_tmp);
			
?>

<table border="0" width="99%">
	<tr colspan=6><td colspan="5"><hr></hr></td></tr>
	<tr colspan=6>
	  <td width="195" class="font_field_form">Transaksi <? echo "$_GET[jenis_trans]"; ?>
	  <td align="right"><font color="#FF0000">*</font>:</td>
	  <td width="385">
      <table width="211" class="zebra_style_3" >
      <tr>
	      <? if ($_POST[tipe_trs]) { $tipe=$_POST[tipe_trs]; } else { $tipe=substr($data_hasil[3],1,1);} ?>
	      <td width="75" height="23"><label>
	        <input type="radio" name="tipe_trs" value="M" <? if ($tipe=='M') { echo "Checked";}?> <? if ($_GET[kd_nav]=='EDIT') { echo "disabled"; } ?>>
	        Masuk</label></td>
	      <td width="81"><label>
	        <input type="radio" name="tipe_trs" value="K" <? if ($tipe=='K') { echo "Checked";} ?> <? if ($_GET[kd_nav]=='EDIT') { echo "disabled"; } ?>>
	        Keluar</label></td>
	      </tr>
      </table></td>
	  <td align="right" class="font_field_form" >Total Debet :</td>
	  <td width="224" class="font_label_form"><? if ($_GET[kd_nav]=='EDIT') { $totaldebet=tampil_uang($_GET[tdebet],true); echo $totaldebet; } ?>
      </td>
    </tr>
	<tr colspan=5>
	  <td height="24" class="font_field_form">Nomor  Bukti
	  <td width="13" align="right"><font color="#FF0000">*</font>:</td>      
	  <td ><input name="kd_nbk" type="text" class="text" value="<? if ($_GET[kd_nav]=="ADD"){ if ($_POST[kd_nbk]) { echo "$_POST[kd_nbk]"; } else { echo "";}}else{echo "$_GET[kd_nbk]";};?>" size="25" maxlength="20" <? if ($_GET[kd_nav]=="EDIT") {echo "readonly";} ?> ></td>
	  <td align="right" class="font_field_form" >Total Kredit :</td>
	  <td class="font_label_form"><? if ($_GET[kd_nav]=='EDIT') { $totalkredit=tampil_uang($_GET[tkredit],true); echo $totalkredit; } ?></td>
    </tr>
	<tr colspan=5>    
	  <td class="font_field_form">Tanggal  Bukti</td>
	  <td width="13" align="right"><font color="#FF0000">*</font></td>
      <? if ($_POST[kd_tgp]) { $tgp=$_POST[kd_tgp]; } else { $tgp=ubah_tgl(substr($data_hasil[4],0,10)); } ?>
	  <td><input name="kd_tgp" type="text" class="text" id="tgl_kalender1" value="<? echo $tgp; ?>" size="10" maxlength="10" <? if ($_GET[kd_nav]=="EDIT") {echo "readonly";} ?>> </td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
	<tr colspan=5>
      <td class="font_field_form">Perkiraan Pusat</td>
      <td align="right"><font color="#FF0000">*</font>:</td>
      <td height="28" colspan="2" valign="middle" class="font_field_form"><input name="kd_acc_pusat" type="text" class="text" id="ssn2" value="<? echo $data_top[0]; ?>" size="13" maxlength="13" readonly>&nbsp; 
      <input name="kd_nmp_pusat" type="text" class="text" value="<? echo $data_top[1]; ?>" size="70" maxlength="50" readonly><a href="<? echo "tabel_cari_akun.php?company_id=$company_id&id_menu=$_GET[id_menu]&no_urut=$_GET[no_urut]&page=$_GET[page]"; ?>" class="tooltip"></img> <span> Cari data akun</span></a></td>
      <td height="28" valign="middle" class="font_field_form"><a href="<? ; echo "tabel_cari_akun.php?company_id=$company_id&id_menu=$_GET[id_menu]&no_urut=$_GET[no_urut]&page=$_GET[page]"; ?>" class="tooltip"><img src="../../img/search_button.png"></a></td>
    <tr colspan=5><td colspan="6"></hr></td></tr>
    <tr colspan=5>
      <td height="30" colspan="5">
      <table width="100%" height="46" border="0" bgcolor="#FFFFFF">
        <tr >
          <td width="5%" height="31" align="center" class="field_head">SEQ</td>
          <td width="12%" align="center" class="field_head">Kode Perkiraan</td>
          <td width="50%" align="center" class="field_head">Keterangan</td>
          <td width="15%" align="center" class="field_head">Debet</td>
          <td width="15%" align="center" class="field_head">Kredit</td>
          <td width="7%" colspan="2" align="center" class="field_head">Editing</td>
          </tr>
        <?			
			$query_detail = mysql_query("SELECT * FROM trx_tmp_caseglt WHERE user_id='$user_id' AND trx_status='1'  ORDER BY seq ASC", $conn) or die("Error Select Show Acc ".mysql_error());				
			while ($data = mysql_fetch_array($query_detail)) {
				if ($tmbl_edit==2) {
					$view_tmbl_edit="<a href='trans_edit_detail.php?kd_nav_det=EDIT&kd_nav=$_GET[kd_nav]&k_nbk=$_GET[k_nbk]&jenis_trans=$_GET[jenis_trans]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/edit.png' width='15' height='18' border='0'></a>";
				} else {
					$view_tmbl_edit="";
				}
				if ($tmbl_del==3) {
					$view_tmbl_del="<a href='trans_edit.php?k_nbk_del=$_GET[k_nbk]&jenis_trans=$_GET[jenis_trans]&seq=$data[6]&k_acc=$data[9]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/delete.png' width='15' height='18' border='0' onClick=\"return confirm('Hapus Nomor Bukti $query_hasil[1] ?')\"></a>";					
				} else {
					$view_tmbl_del="";
				}
				
				if ($data[11]==0){
					$nildebet=" - ";
				} else {
					$nildebet=tampil_uang($data[11],true);
				}
				
				if ($data[12]==0){
					$nilkredit=" - ";
				} else {				
					$nilkredit=tampil_uang($data[12],true);
				}
				
				if ($data[6]=='000'){
					echo "<tr class='field_data'>
						 <td align='center'>$data[6]</td>
					 	<td>$data[9]</td>
					 	<td>$data[13]</td>
					 	<td align='right'>$nildebet</td>
					 	<td align='right'>$nilkredit</td>
						<td></td>
						<td></td></tr>" ;
				} else {
					echo "<tr class='field_data'>
						 <td align='center'>$data[6]</td>
					 	<td>$data[9]</td>
					 	<td>$data[13]</td>
					 	<td align='right'>$nildebet</td>
					 	<td align='right'>$nilkredit</td>
						<td>$view_tmbl_edit</td>
						<td>$view_tmbl_del</td></tr>" ;
				}
			}		
		?>
        <tr>
          <td height="2" colspan="7" class="field_head">
		  </td>
          </tr>
      </table>
      <input type="submit" name="add_new_nbk" class="lov_new_data" value="   " id="tooltip">
      </td>
    </tr>
    <tr colspan=5>
      <td height="4" colspan="5"><hr></td>
    </tr>
    <tr colspan=5>
	  <td></td>
	  <td></td>
    <td height="15" colspan="3" class="font_field_form">Note : (<font color="#FF0000">*</font>) Can not be Empty</td></tr>
    <tr colspan=5>
		<td></td>
		<td width="13"></td>
	  	<td height="30" colspan="3" ><input type="submit" value="     S A V E      " name="btn_save_akun" class="tombol_1" onClick="return confirm('Simpan Data Tersebut ?')">&nbsp;&nbsp;&nbsp;<input type="submit" value="     GO BACK      " name="btn_go_back" class="tombol_1" ></td>
  </table>
</form>
</BODY>
</HTML>
<!-- session -->
<?
/*	if ($_GET[k_acc]=="ADD") {
		// Add New 
		if($_POST[btn_save_akun]){		
			if (empty($_POST[kd_acc]) or empty($_POST[kd_nmp])) {
				echo "<script language=javascript>
					alert (' Error : Kode dan Nama akun tidak boleh kosong ! ');
					</script>";					
			} else {
			$query_data = mysql_query("SELECT acc FROM mst_akun WHERE acc='$_POST[kd_acc]' AND mcom_id='$company_id' ", $conn) or die("Error Validasi New Acc ".mysql_error());
			$row_already = mysql_num_rows($query_data);
			if ($row_already) {
			//--already exist
				echo "<script language=javascript>
					alert (' Error : Kode akun tersebut sudah ada ! ');
					</script>";
			} else {			
					$query_data = mysql_query("SELECT nmp FROM mst_akun WHERE nmp='$_POST[kd_nmp]' AND mcom_id='$company_id' ", $conn) or die("Error Validasi New Acc ".mysql_error());
					$row_already = mysql_num_rows($query_data);
					if ($row_already) {
					//--already exist
						echo "<script language=javascript>
							alert (' Error : Kode akun dengan nama tersebut sudah ada ! ');
							</script>";
					} else {			
						$query_data = mysql_query("INSERT INTO mst_akun (acc_status,mcom_id,acc,nmp,level,tnd,jnp,hpp,pusat,ket,th_sld,bl_sld,jml_sld,pemakai,tgl_input) VALUES ('1','$company_id','$_POST[kd_acc]','$_POST[kd_nmp]','$_POST[LevelGroup1]','$_POST[DKGroup1]','$_POST[NRGroup1]','$_POST[HPPGroup1]','$_POST[PPGroup1]','$_POST[kd_ket]','$user_id',now())", $conn) or die("Error Insert New Acc ".mysql_error());
			
						echo "<script language=javascript>
								alert (' Penambahan Akun baru berhasil. ');
								</script>";
					}
				}
			}
		}
	}
	else {
		// Edit
		if($_POST[btn_save_akun]){
			//echo "SELECT nmp FROM mst_akun WHERE acc<>'$_POST[kd_acc]' AND nmp='$_POST[kd_nmp]' AND mcom_id='$company_id' ";
			$query_data = mysql_query("SELECT nmp FROM mst_akun WHERE acc<>'$_POST[kd_acc]' AND nmp='$_POST[kd_nmp]' AND mcom_id='$company_id' ", $conn) or die("Error Validasi New Acc ".mysql_error());
					$row_already = mysql_num_rows($query_data);
					if ($row_already) {
					//--already exist
						echo "<script language=javascript>
							alert (' Error : Kode akun dengan nama tersebut sudah ada ! ');
							</script>";
					} else {
						// Simpan perubahan data akun
						$query_data = mysql_query("UPDATE mst_akun SET nmp='$_POST[kd_nmp]', level='$_POST[LevelGroup1]', tnd='$_POST[DKGroup1]', jnp='$_POST[NRGroup1]', hpp='$_POST[HPPGroup1]', pusat='$_POST[PPGroup1]', ket='$_POST[kd_ket]', pemakai='$user_id', tgl_input=now() WHERE acc='$_GET[k_acc]' AND acc_status='1' AND mcom_id='$company_id' ", $conn) or die("Error Update Acc ".mysql_error());		
					
						echo "<script language=javascript>
								alert (' Ubah data akun berhasil ');
							</script>";
					}
		}
	}*/
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
