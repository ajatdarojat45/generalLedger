<?php
session_start();
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
    <th width="10%" class="info">Keterangan</th>
    <th width="1%" class="info">Detail / Header</th>
    <th width="1%" class="info">Header / Sub header</th>
    <th width="1%" class="info">Tampil</th>
    <th width="1%" colspan="5" class="info text-center">EDITING</th>
    <div id="area-detail-tabel">
<?			
	$query_det_neraca = mysql_query("SELECT * FROM tmp_mst_set_nrk WHERE trx_status='1' AND user_id='$user_id' AND noset_id='$_GET[k_noset]' AND mcom_id='$company_id' ORDER BY nourut ASC", $conn) or die("Error Select Setup Neraca Table ".mysql_error());		
	$jml_rec_data=mysql_num_rows($query_det_neraca);		
	if ($jml_rec_data) {			
		while ($query_hasil = mysql_fetch_array($query_det_neraca)){
			if ($query_hasil[5]=='D'){
				$lnk_edit="<a href='#' id='btn_edit_$no_urut' onClick=\"klik_d_edit($query_hasil[2])\" title='Ubah' data-toggle='tooltip'><span class='glyphicon glyphicon-edit'></span></a>";
				//$lnk_edit="<a href='tabel_neraca_kons_edit_acc.php?k_noset=$query_hasil[1]&k_noid=$query_hasil[2]&k_ket=$query_hasil[4]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]'><span class='glyphicon glyphicon-edit'></span></a>";
			} else { 
				$lnk_edit="<a href='#' id='btn_edit_$no_urut' onClick=\"klik_d_edit($query_hasil[2])\" title='Ubah' data-toggle='tooltip'><span class='glyphicon glyphicon-edit'></span></a>";
				//$lnk_edit="<a href='tabel_neraca_kons_edit_head.php?k_noset=$query_hasil[1]&k_noid=$query_hasil[2]&k_ket=$query_hasil[4]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]'><span class='glyphicon glyphicon-edit'></span></a>";
			}				
			
			if ($tmbl_edit==2) {
				$view_tmbl_edit=$lnk_edit;
				//$view_tmbl_up="<a href='?row_atas=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]' title='Pindah Ke Atas' data-toggle='tooltip'><span class='glyphicon glyphicon-arrow-up'></span></a>";
				$view_tmbl_up="<a href='#' title='Pindah Ke Atas' data-toggle='tooltip' onClick=\"reset_view_detail('$query_hasil[1]','$_GET[id_menu]')\"><span class='glyphicon glyphicon-arrow-up'></span></a>";
				$view_tmbl_dw="<a href='?row_bawah=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]' title='Pindah Ke Bawah' data-toggle='tooltip'><span class='glyphicon glyphicon-arrow-down'></span></a>";
				$view_tmbl_ins="<a href='?row_sisip=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]' title='Sisipkan baris baru' data-toggle='tooltip'><span class='glyphicon glyphicon-arrow-left'></span></a>";
			} else {
				$view_tmbl_edit="";
				$view_tmbl_up="";
				$view_tmbl_dw="";
				$view_tmbl_ins="";
			}
			if ($tmbl_del==3) {					
				$view_tmbl_del="<a href='?row_hapus=true&nourutid=$query_hasil[2]&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&page=$_GET[page]&no_urut=$_GET[no_urut]' title='Hapus baris' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";					
			} else {
				$view_tmbl_del="";
			}				
			
			echo "<tr> 
			<td>$query_hasil[3]</td>
			<td>$query_hasil[2]</td>
			<td><input type='text' value='$query_hasil[4]' size=\"150\" readonly></td>
			<td align='center'>$query_hasil[5]</td>								
			<td align='center'>$query_hasil[6]</td>
			<td align='center'> &radic; </td>
			<td>$view_tmbl_edit</td>
			<td>$view_tmbl_del</td>
			<td>$view_tmbl_ins</td>
			<td>$view_tmbl_up</td>
			<td>$view_tmbl_dw</td></tr>";
		$no_urut ++;
		}
	}
	else{
		echo "<tr class='field_data'><td colspan='11'>Empty Data Record.</td></tr>";
	}
?> 
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
