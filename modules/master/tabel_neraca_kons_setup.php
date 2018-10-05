<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";
	
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
</HEAD>
<BODY>
<? 
	if ($_GET[k_noset]<>"ADD"){
		$query_data = mysql_query("SELECT * FROM mst_setnrk WHERE noset_id='$_GET[k_noset]'  AND trx_status='1' AND mcom_id='$company_id' ", $conn) or die("Error Select Show Neraca Kons ".mysql_error());		
		$data_hasil = mysql_fetch_array($query_data);
	} else {
		$data_hasil="";
	}
	
	$kset="";
	$nmset="";	
	$aksi="";
	if ($_GET[k_noset]=="ADD"){$aksi="Buat Baru";} else {$aksi="Ubah";} 
	if ($_GET[k_noset]=="ADD"){ if ($_POST[kd_noset]) { $kset=$_POST[kd_noset]; } else { $kset="";}} else { $kset=$_GET[k_noset];} 
	if ($_POST[kd_nmset]) { $nmset=$_POST[kd_nmset]; } else { $nmset=$data_hasil[2];} 
?>

<div class="form-inline">
	<b><? echo $aksi ;?> Setup Neraca Konsolidasi</b> <br><br>
    Kode Setup 
    <input id="ssn" name="kd_noset" type="text" class="form-control" value="<? echo $kset; ?>" size="3" maxlength="3" <? if ($_GET[k_noset]=="ADD"){echo "";}else{echo "readonly";};?> autofocus >
    Nama Setup 
	<input name="kd_nmset" type="text" class="form-control" value="<? echo $nmset; ?>" size="50" maxlength="100" readonly> 
	
	<button type='button' name="btn_add_detail" class="btn btn-success pull-right hidden-print" onClick="klik_d_add(<? echo $kset; ?>,<? echo $_GET[id_menu]?>)" title="Tambah baris baru" data-toggle="tooltip" ><span class="glyphicon glyphicon-plus"></span></button>
	<button type='button' name="btn_cancel" class="btn btn-success pull-right hidden-print" onClick="klik_cancel()">CANCEL</button>
	<button type='submit' name="btn_save_setup" class="btn btn-danger pull-right hidden-print" value="<? echo $aksi; ?>">SAVE</button> 

</div>
<br>


<div id="area-detail-tabel"> 
<table class="table table-bordered table-hover table-condensed" width="100%">
    <th width="1%" class="info">Nomor Urut</th>
    <th width="1%" class="info">No.ID</th>
    <th width="25%" class="info">Keterangan</th>
    <th width="1%" class="info">Detail / Header</th>
    <th width="1%" class="info">Header / Sub header</th>
    <th width="1%" class="info text-center">Tampil</th>
    <th width="1%" colspan="6" class="info text-center hidden-print">EDITING</th>
<?	
	$query_tot = mysql_query("SELECT count(*) as tot_rec FROM tmp_mst_set_nrk WHERE trx_status='1' AND user_id='$user_id' AND noset_id='$_GET[k_noset]' AND mcom_id='$company_id' ORDER BY nourut ASC", $conn) or die("Error Select Setup Neraca Table ".mysql_error());		
	$data_rec = mysql_fetch_array($query_tot);
			
	$query_det_neraca = mysql_query("SELECT * FROM tmp_mst_set_nrk WHERE trx_status='1' AND user_id='$user_id' AND noset_id='$_GET[k_noset]' AND mcom_id='$company_id' ORDER BY nourut ASC", $conn) or die("Error Select Setup Neraca Table ".mysql_error());		
	$jml_rec_data=mysql_num_rows($query_det_neraca);		
	if ($jml_rec_data) {	
		$no_urut=1;		
		while ($query_hasil = mysql_fetch_array($query_det_neraca)){
			if ($query_hasil[5]=='D'){
				$lnk_edit="<a href='#baris_edit$query_hasil[3]' id='btn_edit_$no_urut' onClick=\"klik_d_edit('$no_urut','$query_hasil[2]','$_GET[k_noset]')\" title='Ubah' data-toggle='tooltip'><span class='glyphicon glyphicon-edit'></span></a>";				
							
			} else { 
				$lnk_edit="<a href='#baris_edit$query_hasil[3]' id='btn_edit_$no_urut' onClick=\"klik_d_edit('$no_urut','$query_hasil[2]','$_GET[k_noset]')\" title='Ubah' data-toggle='tooltip'><span class='glyphicon glyphicon-edit'></span></a>";
				
			}				
			
			if ($tmbl_edit==2) {
				$view_tmbl_edit=$lnk_edit;
				
				if ($no_urut==1) { $view_tmbl_up=""; } else {
				$view_tmbl_up="<a href='#baris_edit$query_hasil[3]' title='Pindah Ke Atas' data-toggle='tooltip' onClick=\"klik_d_up('$query_hasil[1]','$_GET[id_menu]','$query_hasil[3]')\"><span class='glyphicon glyphicon-arrow-up'></span></a>";
				}
				
				if ($no_urut==$data_rec[tot_rec]) { $view_tmbl_dw=""; } else {
				$view_tmbl_dw="<a href='#baris_edit$query_hasil[3]' title='Pindah Ke Bawah' data-toggle='tooltip' onClick=\"klik_d_down('$query_hasil[1]','$_GET[id_menu]','$query_hasil[3]')\"><span class='glyphicon glyphicon-arrow-down'></span></a>";
				}
				
				$view_tmbl_ins="<a href='#baris_edit$query_hasil[3]' class='text-danger' title='Sisipkan baris baru' data-toggle='tooltip' onClick=\"klik_d_insert('$query_hasil[1]','$_GET[id_menu]','$query_hasil[3]')\"><span class='glyphicon glyphicon-arrow-left'></span></a>";
			} else {
				$view_tmbl_edit="";
				$view_tmbl_up="";
				$view_tmbl_dw="";
				$view_tmbl_ins="";
			}
			if ($tmbl_del==3) {					
				$view_tmbl_del="<a href='#baris_edit$query_hasil[3]' title='Hapus baris' data-toggle='tooltip' onClick=\"klik_d_delete('$query_hasil[1]','$_GET[id_menu]','$query_hasil[3]')\"><span class='glyphicon glyphicon-trash'></span></a>";					
			} else {
				$view_tmbl_del="";
			}				
			
			$sel1="";
			$sel2="";
			$sel11="";
			$sel12="";
			$sel13="";
			$cek="";
			
			if ($query_hasil[5]=="H") { 
				$isi="<b><input id='input_1_$no_urut' name='ket' class='form-control' type='text' value='$query_hasil[4]' disabled='disabled' /></b>" ;
				$sel2="selected";
			} else { 
				$isi="<input id='input_1_$no_urut' name='ket' class='form-control' type='text' value='$query_hasil[4]' disabled='disabled'/>" ;
				$sel1="selected";
			}
			if ($query_hasil[6]=="S" ) { 
				$sel13="selected";
			} else if ($query_hasil[6]=="H")  { 
				$sel12="selected";
			} else {
				$sel11="selected";
			}
			if ($query_hasil[7]=="Y" ) {
				$cek="checked='checked'";
			} 
			$tombol=""; 
			if ($query_hasil[4]!="") { $tombol="<button type='button' class='btn btn-success btn-sm' onClick=\"klik_d_setup($no_urut,$query_hasil[2],$_GET[k_noset],'$query_hasil[4]','$query_hasil[5]','$query_hasil[6]','$query_hasil[7]','$company_id','$company_name')\">SETUP</button>";
			}
			echo "<tr name='baris_edit$query_hasil[3]'> 
			<td>$query_hasil[3]</td>
			<td>$query_hasil[2]</td>
			<td>$isi</td>
			<td align='center'>
			
			<select class='form-control input-xs' disabled='disabled' id='input_2_$no_urut' name='DH1'>
			<option value='D' $sel1>D</option>			
			<option value='H' $sel2>H</option>
			</select>
			
			</td>								
			<td align='center'>

			<select class='form-control' disabled='disabled'  id='input_3_$no_urut' name='DH2'>
			<option value='' $sel11></option>			
			<option value='H' $sel12>H</option>
			<option value='S' $sel13>S</option>
			</select>
			
			</td>
			<td align='center'> <input type='checkbox' name='tampil' id='input_4_$no_urut' value='Y' $cek disabled='disabled'/></td>
			
			<td class='hidden-print'>$view_tmbl_edit</td>
			<td class='hidden-print'>$view_tmbl_del</td>
			<td class='hidden-print'>$view_tmbl_ins</td>
			<td class='hidden-print'>$view_tmbl_up</td>
			<td class='hidden-print'>$view_tmbl_dw</td>
			<td class='hidden-print'><div id='area_btn_row_$no_urut'>$tombol</div></td></tr>";
		$no_urut ++;
		}
	}
	else{
		echo "<tr class='field_data'><td colspan='11'>Empty Data Record.</td></tr>";
	}
?> 
<input type="hidden" id="tot_row" value="<? echo $no_urut ; ?>"> 

</table>
</div>     
	<button type='button' name="btn_add_detail" class="btn btn-success pull-right hidden-print" onClick="klik_d_add(<? echo $kset; ?>,<? echo $_GET[id_menu]?>)" title="Tambah baris baru" data-toggle="tooltip" ><span class="glyphicon glyphicon-plus"></span></button>
	<button type='button' name="btn_cancel" class="btn btn-success pull-right hidden-print" onClick="klik_cancel()">CANCEL</button>
	<button type='submit' name="btn_save_setup" class="btn btn-danger pull-right hidden-print" value="<? echo $aksi; ?>">SAVE</button> 

<!--<span class="glyphicon glyphicon-arrow-up" -->
<div id="form_modul">
<form method="POST" name="frm_neraca_kons_edit" target="_self" >
  <div id="form_modul_nav_top">
 	<div id="tmbl_nav"><a href="<? echo "?row_baru=true&k_noset=$_GET[k_noset]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]"; ?>" class="tooltip"><img src="/glt/img/add_button.png"></img> <span> Add New Rec</span></a></div>
    </div>
    
    <div id="form_modul_nav_botom">
    	<div id="tmbl_nav"><a href="<? echo "?row_baru=true&k_noset=$_GET[k_noset]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]"; ?>" class="tooltip"><img src="/glt/img/add_button.png"></img> <span> Add New Rec</span></a></div>
	</div>
</form>
</div>

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
