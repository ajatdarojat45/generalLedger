<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";
	// include "../inc/inc_trans_menu.php";
	// ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);
	
	//  Cek Hak Akses Tombol Add, Edit dan Delete
	include "../inc/inc_aed.php";
	
	//echo "Add = $tmbl_add - Edit = $tmbl_edit - Delete = $tmbl_del"; 
	if ($_GET[kd_temp]=='YA'){
		$query_data = mysql_query("DELETE FROM tmp_mst_set_nrk WHERE user_id='$user_id'", $conn) or die("Error Delete User Temp Neraca Kons ".mysql_error());		
		$query_data = mysql_query("INSERT INTO tmp_mst_set_nrk (SELECT * FROM mst_set_nrk WHERE noset_id='$_GET[k_noset]'  AND trx_status='1' AND mcom_id='$company_id') ", $conn) or die("Error Create Select Temp Neraca Kons ".mysql_error());		
		$query_data = mysql_query("UPDATE tmp_mst_set_nrk SET user_id='$user_id' WHERE noset_id='$_GET[k_noset]'", $conn) or die("Error Update User Temp Neraca Kons ".mysql_error());	
	// Detail Setup 
		$query_data = mysql_query("DELETE FROM tmp_mst_set_nrkt WHERE user_id='$user_id'", $conn) or die("Error Delete User Temp Neraca Kons Detail ".mysql_error());		
		$query_data = mysql_query("INSERT INTO tmp_mst_set_nrkt (SELECT * FROM mst_set_nrkt WHERE noset_id='$_GET[k_noset]'  AND trx_status='1' AND mcom_id='$company_id') ", $conn) or die("Error Create Select Temp Neraca Kons Detail ".mysql_error());		
		$query_data = mysql_query("UPDATE tmp_mst_set_nrkt SET user_id='$user_id' WHERE noset_id='$_GET[k_noset]'", $conn) or die("Error Update User Temp Neraca Kons Detail ".mysql_error());	
	}
		
	if ($_GET[row_sisip]==true){
		$mulai=$_GET[nourut];
		$nobaru=$mulai;
		$tmp_data=mysql_query("SELECT * from tmp_mst_set_nrk where nourut >= '$mulai' AND user_id='$user_id' AND mcom_id='$company_id' AND noset_id='$_GET[k_noset]' AND trx_status=1 order by nourut ", $conn) or die(mysql_error());
		
		while ($data_tmp = mysql_fetch_array($tmp_data)) 
		{   $nobaru ++;
			$upd_data=mysql_query("update tmp_mst_set_nrk set nourut=$nobaru where nourut_id=$data_tmp[2] AND user_id='$user_id'", $conn) or die(mysql_error());			
		}
		$data_baru=mysql_query("insert into tmp_mst_set_nrk (mcom_id,noset_id,nourut,tampil,trx_status,user_id,pemakai,tgl_input) values ($company_id,$_GET[k_noset],$mulai,'Y',1,'$user_id','$user_id',now())", $conn) or die(mysql_error());
	}
	
	if ($_GET[row_hapus]==true){
		$mulai=$_GET[nourut];
		$nobaru=$mulai;
		$del_data=mysql_query("update tmp_mst_set_nrk set trx_status=0 where nourut=$_GET[nourut] ", $conn) or die(mysql_error());
		$tmp_data=mysql_query("SELECT * from tmp_mst_set_nrk where nourut > '$mulai'  AND user_id='$user_id' AND trx_status=1 order by nourut ", $conn) or die(mysql_error());
		while ($data_tmp = mysql_fetch_array($tmp_data)) 
		{   $upd_data=mysql_query("update tmp_mst_set_nrk set nourut=$nobaru where nourut_id=$data_tmp[2] AND user_id='$user_id'", $conn) or die(mysql_error());
			$nobaru ++;			
		}
	}
	
	if ($_GET[row_baru]==true){
		$ambil_data=mysql_query("SELECT max(nourut) FROM tmp_mst_set_nrk where user_id='$user_id' ", $conn) or die(mysql_error());	
		$data_hasil=mysql_fetch_array($ambil_data);
		$noakhir=$data_hasil[0] + 1;
		$tambah_data=mysql_query("INSERT INTO tmp_mst_set_nrk (mcom_id,noset_id,nourut,trx_status,pemakai,tgl_input,user_id) VALUES ('$company_id','$_GET[k_noset]',$noakhir,1,'$user_id',now(),'$user_id') ", $conn) or die(mysql_error());	
		}
		
	if ($_GET[row_atas]==true){
		if ($_GET[nourut]<>1){
			$noatas=$_GET[nourut]-1;
			$ambil_data=mysql_query("update tmp_mst_set_nrk set nourut=0 where nourut='$noatas' AND user_id='$user_id' AND mcom_id='$company_id' AND noset_id='$_GET[k_noset]' ", $conn) or die(mysql_error());	
			$ambil_data=mysql_query("update tmp_mst_set_nrk set nourut=$noatas where nourut='$_GET[nourut]' AND user_id='$user_id' AND mcom_id='$company_id' AND noset_id='$_GET[k_noset]'", $conn) or die(mysql_error());	
			$ambil_data=mysql_query("update tmp_mst_set_nrk set nourut=$_GET[nourut] where nourut=0  AND user_id='$user_id' AND mcom_id='$company_id' AND noset_id='$_GET[k_noset]'", $conn) or die(mysql_error());	
			}
		}	
	if ($_GET[row_bawah]==true){
		$ambil_data=mysql_query("SELECT max(nourut) FROM tmp_mst_set_nrk WHERE user_id='$user_id' AND mcom_id='$company_id' AND noset_id='$_GET[k_noset]' ", $conn) or die(mysql_error());
		$data_hasil=mysql_fetch_array($ambil_data);	
		if ($_GET[nourut]<$data_hasil[0]){
			$nobawah=$_GET[nourut]+1;
			$ambil_data=mysql_query("update tmp_mst_set_nrk set nourut=0 where nourut='$_GET[nourut]' AND user_id='$user_id' AND mcom_id='$company_id' AND noset_id='$_GET[k_noset]' ", $conn) or die(mysql_error());	
			$ambil_data=mysql_query("update tmp_mst_set_nrk set nourut=$_GET[nourut] where nourut='$nobawah' AND user_id='$user_id' AND mcom_id='$company_id' AND noset_id='$_GET[k_noset]' ", $conn) or die(mysql_error());	
			$ambil_data=mysql_query("update tmp_mst_set_nrk set nourut=$nobawah where nourut=0 AND user_id='$user_id' AND mcom_id='$company_id' AND noset_id='$_GET[k_noset]' ", $conn) or die(mysql_error());	
			}
		}
	
?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet">
    <link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet">
    <link href="../../style/style_utama.css" rel="stylesheet">

   <![if lt IE 9]>
      <script src="bootstrap/html5shiv.js"></script>
      <script src="bootstrap/respond.min.js"></script>
    <![endif]>
	

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

</HEAD>
<BODY>
<div id="form_modul">
<form method="POST" name="frm_neraca_kons_edit" target="_self" >
<?
	if ($_GET[k_noset]<>"ADD"){
		$query_data = mysql_query("SELECT * FROM mst_setnrk WHERE noset_id='$_GET[k_noset]'  AND trx_status='1' AND mcom_id='$company_id' ", $conn) or die("Error Select Show Neraca Kons ".mysql_error());		
		$data_hasil = mysql_fetch_array($query_data);
	} else {
		$data_hasil="";
	}
?>
<div id="form_modul_judul" class="font_label_form"><? if ($_GET[k_noset]=="ADD"){echo "Buat Baru";} else {echo "Ubah";}; ?> Setup Neraca Konsolidasi</div>

<table border="0" width="100%">
	<tr colspan=5><td colspan="3"><hr></hr></td></tr>
	<tr colspan=5 class="font_field_form">
	  <td width="120" height="24" >Kode Setting
	  <td width="12" align="right"><font color="#FF0000">*</font><strong>:</strong></td>
	  <td width="746" ><input id="ssn" name="kd_noset" type="text" class="text" value="<? if ($_GET[k_noset]=="ADD"){ if ($_POST[kd_noset]) { echo "$_POST[kd_noset]"; } else { echo "";}}else{echo "$_GET[k_noset]";};?>" size="4" maxlength="3" <? if ($_GET[k_noset]=="ADD"){echo "";}else{echo "readonly";};?> autofocus > </td>
    </tr>
	<tr colspan=5 class="font_field_form">
	  <td width="120">Nama Setting
	  <td width="12" align="right"><font color="#FF0000">*</font><strong>:</strong></td>
	  <td><input name="kd_nmset" type="text" class="text" value="<? if ($_POST[kd_nmset]){	echo "$_POST[kd_nmset]";
			} else {
	  		echo "$data_hasil[2]";
	  		//}
	  		} ?>" size="80" maxlength="100"></td>	
    </tr>
	<tr colspan=5><td colspan="3"><hr></hr></td></tr>
   <tr colspan=5>
	  <td></td>
	  <td></td>
	  <td class="warning_form">Note : (<font color="#FF0000">*</font>) Can not be Empty</td></tr>
    <tr colspan=5>
		<td width="120"></td>
		<td width="12"></td>
	  	<td height="30"><input type="submit" value="     S A V E      " name="btn_save_set" class="tombol_1" onClick="return confirm('Simpan Data Setup Neraca Tersebut ?')">&nbsp;&nbsp;&nbsp;<input type="submit" value="     GO BACK      " name="btn_go_back" class="tombol_1"></td>
  </table>
  <div id="form_modul_nav_top">
 	<div id="tmbl_nav"><a href="<? echo "?row_baru=true&k_noset=$_GET[k_noset]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]"; ?>" class="tooltip"><img src="/gltempo/img/add_button.png"></img> <span> Add New Rec</span></a></div>
    </div>
    <div id="form_modul_isi">
	<table class="table table-bordered table-hover table-condensed" border="0" width="100%" bgcolor="#FFFFFF">
		<th width="3%" height="30" align="center" class="field_head">Nomor Urut</th>
       	<th width="3%" height="30" align="center" class="field_head">No.ID</th>
		<th width="30%" height="30" align="center" class="field_head">Keterangan</th>
		<th width="5%" align="center" class="field_head">Detail / Header</th>
		<th width="5%" align="center" class="field_head">Header / Sub header</th>
		<th width="5%" align="center" class="field_head">Tampil</th>
		<th width="12%" colspan="5" align="center" class="field_head">Editing</th>
		<?			
		$query_det_neraca = mysql_query("SELECT * FROM tmp_mst_set_nrk WHERE trx_status='1' AND user_id='$user_id' AND noset_id='$_GET[k_noset]' AND mcom_id='$company_id' ORDER BY nourut ASC", $conn) or die("Error Select Setup Neraca Table ".mysql_error());		
		$jml_rec_data=mysql_num_rows($query_det_neraca);		
		if ($jml_rec_data) {			
			while ($query_hasil = mysql_fetch_array($query_det_neraca)){
				if ($query_hasil[5]=='D'){
					$lnk_edit="<a href='tabel_neraca_kons_edit_acc.php?k_noset=$query_hasil[1]&k_noid=$query_hasil[2]&k_ket=$query_hasil[4]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/edit.png' width='15' height='18' border='0'></a>";
				} else { 
					$lnk_edit="<a href='tabel_neraca_kons_edit_head.php?k_noset=$query_hasil[1]&k_noid=$query_hasil[2]&k_ket=$query_hasil[4]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/edit.png' width='15' height='18' border='0'></a>";
				}				
				
				if ($tmbl_edit==2) {
					$view_tmbl_edit=$lnk_edit;
					$view_tmbl_up="<a href='?row_atas=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/arrow_asc.gif' width='15' height='18' border='0'></a>";
					$view_tmbl_dw="<a href='?row_bawah=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/arrow_desc.gif' width='15' height='18' border='0'></a>";
					$view_tmbl_ins="<a href='?row_sisip=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/arrow_right.gif' width='15' height='18' border='0'></a>";
				} else {
					$view_tmbl_edit="";
					$view_tmbl_up="";
					$view_tmbl_dw="";
					$view_tmbl_ins="";
				}
				if ($tmbl_del==3) {					
					$view_tmbl_del="<a href='?row_hapus=true&nourutid=$query_hasil[2]&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&page=$_GET[page]&no_urut=$_GET[no_urut]'><img src='../../img/delete.png' width='15' height='18' border='0' onClick=\"return confirm('Hapus Setup Neraca $query_hasil[1]-$query_hasil[2] ?')\"></a>";					
				} else {
					$view_tmbl_del="";
				}				
				
				echo "<tr class='field_data'> 
				<td width='5%' height='18'  class='text2' align='right' bgcolor='#999999'>$query_hasil[3]</td>
				<td width='5%' height='18'  class='text2' align='right' bgcolor='#999999'>$query_hasil[2]</td>
				<td width='30%' height='18'><input type='text' value='$query_hasil[4]' size=\"75\" class='text2'></td>
				<td width='5%'><select  name='cmb_dh' class='text2'>";
				if ($query_hasil[5]=='D') { 
					echo "<option value='D' selected >D</option><option value='H' >H</option>";
				} else {
					echo "<option value='D' >D</option><option value='H' selected >H</option>";
				}
				echo "</select></td>								
				<td width='5%'><select  name='cmb_dh' class='text2' value=''>";
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
				<td width='5%' align='center'><input type=checkbox value='Y' checked ></td>
				<td width='3%' align='center'>$view_tmbl_edit</td>
				<td width='3%' align='center'>$view_tmbl_del</td>
				<td width='3%' align='center'>$view_tmbl_ins</td>
				<td width='3%' align='center'>$view_tmbl_up</td>
				<td width='3%' align='center'>$view_tmbl_dw</td></tr>";
			$no_urut ++;
			}
			echo "<tr><td colspan='11' class='field_head'></td></tr>";
		}
		else{
			echo "<tr class='field_data'><td colspan='11'>Empty Data Record.</td></tr>";
		}
		?>        
	</table>
    </div>
    <div id="form_modul_nav_botom">
    	<div id="tmbl_nav"><a href="<? echo "?row_baru=true&k_noset=$_GET[k_noset]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]"; ?>" class="tooltip"><img src="/gltempo/img/add_button.png"></img> <span> Add New Rec</span></a></div>
	</div>
</form>
</div>



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<![if lt IE 9]>
	  <script src="../../bootstrap/html5shiv.js"></script>
	  <script src="../../bootstrap/respond.min.js"></script>
	<![endif]>
	
	<script src="../../js/jquery-1.11.0.min.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>

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
