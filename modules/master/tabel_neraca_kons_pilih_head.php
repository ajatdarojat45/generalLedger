<?php
session_start();
if (isset($_SESSION['app_glt'])){
	if ($_POST[btn_go_back]) header("location:tabel_neraca_kons_edit_head.php?k_noset=$_GET[k_noset]&k_noid=$_GET[k_noid]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]");
	
	include "../inc/inc_akses.php";
	// include "../inc/inc_trans_menu.php";
	// ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);
	
	//  Cek Hak Akses Tombol Add, Edit dan Delete
	include "../inc/inc_aed.php";
	
	//echo "Add = $tmbl_add - Edit = $tmbl_edit - Delete = $tmbl_del"; 
	
	if ($_GET[k_noset]=="ADD") {
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
		
?>
<HTML>
<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.min.css"></HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="../inc/jquery-1.3.2.js"></script>
<script type="text/javascript" src="../inc/ui.core.js"></script>
<script type="text/javascript" src="../inc/ui.mask.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	$.ui.mask.definitions['~'] = "[+-]";
	//$.ui.mask.defaults.allowPartials = true; //used to allowPartials on all instances of mask() on the page.

	$("#ssn").mask({mask: "***", allowPartials: true});
	
	$("#tahun").mask({mask: "####", allowPartials: true});

	$("#bulan").mask({mask: "##", allowPartials: true});

	$("#nilai").mask({mask: "###,###,###,###", allowPartials: true});
		
});
</script>
<BODY>
<div id="form_modul">
<form method="POST" name="frm_pilih_header" target="_self" >
<div id="form_modul_judul" class="font_label_form">PILIH DATA HEADER <? echo "$_GET[k_noset] -> $_GET[k_noid] - $_GET[k_ket]"; ?> </div>
<table border="0" width="99%">
    <tr colspan=5>
		<td height="30"><input type="submit" value="     GO BACK      " name="btn_go_back" class="tombol_1"></td>
</table>
  <div id="form_modul_nav_top">
 	
    <div id="form_modul_isi">
	<table border="0" width="99%" bgcolor="#FFFFFF">
       	<th width="8%" height="30" align="center" class="field_head">No.ID</th>
		<th width="71%" height="30" align="center" class="field_head">Keterangan</th>
		<th width="5%" align="center" class="field_head">Detail / Header</th>
		<th width="8%" align="center" class="field_head">Header / Sub header</th>
		<th width="8%" align="center" class="field_head">PILIH</th>
		<?			
		$query_det_neraca = mysql_query("SELECT a.* FROM tmp_mst_set_nrk as a WHERE a.trx_status='1' AND a.tampil='Y' AND a.user_id='$user_id' AND a.noset_id='$_GET[k_noset]' AND a.mcom_id='$company_id' AND a.keterangan<>'' ORDER BY a.nourut ASC", $conn) or die("Error Select Setup Neraca Table ".mysql_error());		
		$jml_rec_data=mysql_num_rows($query_det_neraca);		
		if ($jml_rec_data) {			
			while ($query_hasil = mysql_fetch_array($query_det_neraca)){
				if ($tmbl_edit==2) {
					$view_tmbl_edit="<input type=submit value=' PILIH ' name='btn_go_pick' class='tombol_1'>";
				} else {
					$view_tmbl_edit="";
				}	
				echo "<tr class='field_data'> 
				<td width='5%' height='18'  class='text2' align='right' bgcolor='#999999'>$query_hasil[2]</td>
				<td width='35%' height='18'><input type='text' value='$query_hasil[4]' size=\"100\" class='text2' readonly></td>
				<td width='5%' align='center'><select  name='cmb_dh' class='text2' disabled>";
				if ($query_hasil[5]=='D') { 
					echo "<option value='D' selected >D</option><option value='H' >H</option>";
				} else {
					echo "<option value='D' >D</option><option value='H' selected >H</option>";
				}
				echo "</select></td>								
				<td width='5%' align='center'><select  name='cmb_dh' class='text2' value='' disabled>";
				if ($query_hasil[6]=='H') { 
					echo "<option value='-'>-</option><option value='H' selected >H</option><option value='S' >S</option>";
				} 
				if ($query_hasil[6]=='S') { 
					echo "<option value='-'>-</option><option value='H' >H</option><option value='S' selected>S</option>";
				}
				if ($query_hasil[5]=='D') { 
					echo "<option value='-' selected>-</option><option value='H' >H</option><option value='S'>S</option>";
				}
				echo "</select></td>
				<td width='3%' align='center'>$view_tmbl_edit</td></tr>";
			$no_urut ++;
			}
			echo "<tr><td colspan='5' class='field_head'></td></tr>";
		}
		else{
			echo "<tr class='field_data'><td colspan='5'>Empty Data Record.</td></tr>";
		}
		?>        
	</table>
	<table border="0" width="99%">
    <tr colspan=5>
		<td height="30"><input type="submit" value="     GO BACK      " name="btn_go_back" class="tombol_1"></td>
</table>    </div>
</form>
</div>
</BODY>
</HTML>
<!-- session -->
<?
	if ($_GET[k_sup]=="ADD") {
		// Add New 
		if($_POST[btn_save_sup]){		
			if (empty($_POST[kd_sup]) or empty($_POST[kd_sup])) {
				echo "<script language=javascript>
					alert (' Error : Kode dan Nama Supplier tidak boleh kosong ! ');
					</script>";					
			} else {
			$query_data = mysql_query("SELECT kd_sup FROM mst_sup WHERE kd_sup='$_POST[kd_sup]' AND mcom_id='$company_id' ", $conn) or die("Error Validasi New Supplier ".mysql_error());
			$row_already = mysql_num_rows($query_data);
			if ($row_already) {
			//--already exist
				echo "<script language=javascript>
					alert (' Error : Kode supplier tersebut sudah ada ! ');
					</script>";
			} else {
					$namasup = str_replace("'","''","$_POST[kd_nmsup]");			
					$query_data = mysql_query("SELECT nm_sup FROM mst_sup WHERE nm_sup='$namasup' AND mcom_id='$company_id' ", $conn) or die("Error Validasi New Supplier ".mysql_error());
					$row_already = mysql_num_rows($query_data);
					if ($row_already) {
					//--already exist
						echo "<script language=javascript>
							alert (' Error : Kode supplier dengan nama tersebut sudah ada ! ');
							</script>";
					} else {
						$namasup = str_replace("'","''","$_POST[kd_nmsup]");			
						$query_data = mysql_query("INSERT INTO mst_sup (sup_status,mcom_id,kd_sup,nm_sup,pemakai,tgl_input) VALUES ('1','$company_id','$_POST[kd_sup]','$namasup','$user_id',now())", $conn) or die("Error Insert New Supplier ".mysql_error());
			
						echo "<script language=javascript>
								alert (' Penambahan Supplier baru berhasil. ');
								</script>";
					}
				}
			}
		}
	}
	else {
		// Edit
		if($_POST[btn_save_sup]){
			$namasup = str_replace("'","''","$_POST[kd_nmsup]");						
			$query_data = mysql_query("SELECT nm_sup FROM mst_sup WHERE kd_sup<>'$_POST[kd_sup]' AND nm_sup='$namasup' AND mcom_id='$company_id' ", $conn) or die("Error Validasi New supplier ".mysql_error());
					$row_already = mysql_num_rows($query_data);
					if ($row_already) {
					//--already exist
						echo "<script language=javascript>
							alert (' Error : Kode supplier dengan nama tersebut sudah ada ! ');
							</script>";
					} else {
						// Simpan perubahan data akun
						$namasup = str_replace("'","''","$_POST[kd_nmsup]");						
						$query_data = mysql_query("UPDATE mst_sup SET nm_sup='$namasup', pemakai='$user_id', tgl_input=now() WHERE kd_sup='$_GET[k_sup]' AND sup_status='1' AND mcom_id='$company_id' ", $conn) or die("Error Update Supplier ".mysql_error());		
					
						echo "<script language=javascript>
								alert (' Ubah data supplier berhasil ');
							</script>";
					}
		}
	}
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
