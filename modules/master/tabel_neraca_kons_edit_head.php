<?php
session_start();
if (isset($_SESSION['app_glt'])){
	if ($_POST[btn_go_back]) header("location:tabel_neraca_kons_edit.php?k_noset=$_GET[k_noset]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]");
	if ($_POST[btn_add_new]) header("location:tabel_neraca_kons_pilih_head.php?k_ket=$_GET[k_ket]&k_noset=$_GET[k_noset]&k_noid=$_GET[k_noid]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]");
	
	include "../inc/inc_akses.php";
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
	
	if ($_GET[row_hapus]==true){
		$del_data=mysql_query(
"update tmp_mst_set_nrkt set trx_status=0 where noset_id=$_GET[k_noset] AND nourut_id=$_GET[k_noid] AND kode=$_GET[k_head] ", $conn) or die(mysql_error());
		$tmp_data=mysql_query("SELECT * from tmp_mst_set_nrkt where nourut_id=$_GET[k_noid] AND user_id='$user_id' AND trx_status=1 order by kode ", $conn) or die(mysql_error());
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
<form method="POST" name="frm_neraca_kons_edit_head" target="_self" >
<div id="form_modul_judul" class="font_label_form">Setup Header : <? echo "[ ".$_GET[k_noid]." ] - [ ".$_GET[k_ket]." ]"; ?> </div>
<div id="form_modul_isi">
	<table border="0" width="100%" bgcolor="#FFFFFF">
       	<th width="3%" height="30" align="center" class="field_head">No.ID</th>
		<th width="3%" align="center" class="field_head">+ / -</th>
		<th width="65%" height="30" align="center" class="field_head">Keterangan</th>
		<th width="3%" align="center" class="field_head">Detail / Header</th>
		<th width="3%" align="center" class="field_head">Editing</th>
		<?			
		$query_det_neraca = mysql_query("SELECT * FROM tmp_mst_set_nrkt WHERE nourut_id='$_GET[k_noid]' AND trx_status='1' AND user_id='$user_id' AND noset_id='$_GET[k_noset]' AND mcom_id='$company_id' ORDER BY kode ASC", $conn) or die("Error Select Setup Neraca Table ".mysql_error());		
		$jml_rec_data=mysql_num_rows($query_det_neraca);		
		if ($jml_rec_data) {			
			while ($query_hasil = mysql_fetch_array($query_det_neraca)){
				if ($tmbl_del==3) {					
					$view_tmbl_del="<a href='?row_hapus=true&k_noid=$_GET[k_noid]&k_noset=$_GET[k_noset]&k_head=$query_hasil[7]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/delete.png' width='15' height='18' border='0' onClick=\"return confirm('Hapus Header $query_hasil[7] ?')\"></a>";					
				} else {
					$view_tmbl_del="";
				}				
				
				echo "<tr class='field_data'> 
				<td width='5%' height='18'  class='text2' align='center' bgcolor='#999999'>$query_hasil[7]</td>
				<td width='3%' align='center'>$query_hasil[6]</td>
				<td width='50%' height='18'  class='text2' >$query_hasil[8]</td>
				<td width='5%' align='center'>$query_hasil[9]</td>
				<td width='3%' align='center'>$view_tmbl_del</td></tr>";
			$no_urut ++;
			}
			echo "<tr><td colspan='5' class='field_head'></td></tr>";
		}
		else{
			echo "<tr class='field_data'><td colspan='5'>Empty Data Record.</td></tr>";
		}
		?>        
	</table>
    </div>
    <div id="form_modul_nav_botom">
		<input type="submit" value="     GO BACK      " name="btn_go_back" class="tombol_1">
    	<input type="submit" value="   ADD NEW REC    " name="btn_add_new" class="tombol_1">
	</div>
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
