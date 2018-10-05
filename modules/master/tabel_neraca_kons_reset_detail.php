<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";
	
	//  Cek Hak Akses Tombol Add, Edit dan Delete
	include "../inc/inc_aed.php";
	
	//echo "Add = $tmbl_add - Edit = $tmbl_edit - Delete = $tmbl_del"; 
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
		$tambah_data=mysql_query("INSERT INTO tmp_mst_set_nrk (mcom_id,noset_id,nourut,trx_status,flg,hdr,tampil,pemakai,tgl_input,user_id) VALUES ('$company_id','$_GET[k_noset]',$noakhir,1,'H','H','Y','$user_id',now(),'$user_id') ", $conn) or die(mysql_error());	
	}

	if ($_GET[btn_save_row]==true){
		
		if ($_POST[tampil]) { $tampil=$_POST[tampil]; } else { $tampil="T";}
		
		$upd_data=mysql_query("update tmp_mst_set_nrk set keterangan='$_POST[ket]',flg='$_POST[DH1]',hdr='$_POST[DH2]',tampil='$tampil' where nourut_id='$_GET[nourut_id]' AND noset_id='$_GET[k_noset]' AND user_id='$user_id' AND mcom_id='$company_id'", $conn) or die(mysql_error());
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
<table class="table table-bordered table-hover table-condensed" width="100%">
    <th width="1%" class="info">Nomor Urut</th>
    <th width="1%" class="info">No.ID</th>
    <th width="25%" class="info">Keterangan</th>
    <th width="1%" class="info">Detail / Header</th>
    <th width="1%" class="info">Header / Sub header</th>
    <th width="1%" class="info">Tampil</th>
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
				$view_tmbl_del="<a href='#$query_hasil[3]' title='Hapus baris' data-toggle='tooltip' onClick=\"klik_d_delete('$query_hasil[1]','$_GET[id_menu]','$query_hasil[3]')\"><span class='glyphicon glyphicon-trash'></span></a>";					
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
				$isi="<b><input id='input_1_$no_urut' name='ket' class='form-control' type='text' value='$query_hasil[4]' disabled='disabled'/></b>" ;
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
			if ($query_hasil[4]!="") { $tombol="<button type='button' class='btn btn-success btn-sm' onClick=\"klik_d_setup($no_urut,$query_hasil[2],$_GET[k_noset],'$query_hasil[4]','$query_hasil[5]','$query_hasil[6]','$query_hasil[7]','$company_id')\">SETUP</button>";
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
			<td align='center'> <input type='checkbox' id='input_4_$no_urut' name='tampil' value='Y' $cek disabled='disabled'/></td>
			
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
