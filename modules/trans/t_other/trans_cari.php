<?php 
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {

	include "../inc/inc_akses.php"; 
	include "../inc/func_modul.php";
	include "../inc/inc_aed.php";
	
	$tgl_trans=substr($aktif_tgl,0,7);
	
	$view_tabel="trx_caseglt_".$company_id;
	
	if ($_GET[jenis_trans]=="Kas"){	
		$tipe='K';	
		$btn="cari_kas";
		}
	if ($_GET[jenis_trans]=="Bank"){
		$tipe='B';	
		$btn="cari_bank";
		}
	if ($_GET[jenis_trans]=="Memorial"){
		$tipe='M';	
		$btn="cari_memorial";
		}
	if ($_GET[jenis_trans]=="BS"){
		$tipe='S';	
		$btn="cari_bs";
		}
	if ($_GET[jenis_trans]=="Pajak"){
		$tipe='P';	
		$btn="cari_pajak";
		}
	if ($_GET[jenis_trans]=="Cashflow"){
		$tipe='';	
		$btn="cari_cashflow";
		}
		
	//echo "$tmbl_add , $tmbl_edit, $tmbl_add";
?>

<HTML>
<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.min.css">
</HEAD>
<BODY>
<!--<form name="cari_akun" method="POST">-->
<table class="table table-condensed table-hover">
	<tr>
		<td width="1%">#</td>
		<td width="10%">TANGGAL</td>
		<td width="15%">NOMOR BUKTI</td>
		<td width="15%">JUMLAH DEBET</td>
		<td width="15%">JUMLAH KREDIT</td>
		<td width="30%">KETERANGAN</td>
		<td>Action</td>
		
    </tr>
<?php
	if ($_GET[btn_go_find]=='true') {
		
		$tgp=substr($_POST[f_kd_tgp],6,4).'-'.substr($_POST[f_kd_tgp],3,2).'-'.substr($_POST[f_kd_tgp],0,2);
			
		if (!empty($_POST[f_kd_nbk])) {
			$query_data_akun = mysql_query("SELECT max(tgp) as tgp,max(nbk) as nbk,sum(deb) as deb,sum(krd) as krd,ket FROM ".$view_tabel." WHERE nbk LIKE '%$_POST[f_kd_nbk]%' AND left(tgp,7)='$tgl_trans' AND typ='$tipe' AND trx_status='1' GROUP BY nbk ORDER BY nbk", $conn) or die("Error Select Find Trx 1".mysql_error());							
		} 
		if (!empty($_POST[f_kd_tgp])) {
			$query_data_akun = mysql_query("SELECT max(tgp) as tgp,max(nbk) as nbk,sum(deb) as deb,sum(krd) as krd,ket FROM ".$view_tabel." WHERE tgp LIKE '%$tgp%' AND left(tgp,7)='$tgl_trans' AND typ='$tipe' AND trx_status='1' GROUP BY nbk ORDER BY tgp", $conn) or die("Error Select Find Trx 2".mysql_error());							
		}
		if (!empty($_POST[f_kd_ket])) {
			$query_data_akun = mysql_query("SELECT max(tgp) as tgp,max(nbk) as nbk,sum(deb) as deb,sum(krd) as krd,ket FROM ".$view_tabel." WHERE ket LIKE '%$_POST[f_kd_ket]%' AND left(tgp,7)='$tgl_trans' AND typ='$tipe' AND trx_status='1' GROUP BY nbk ORDER BY ket", $conn) or die("Error Select Find Trx ".mysql_error());							
		}
		
		if (empty($_POST[f_kd_ket]) && empty($_POST[f_kd_tgp]) && empty($_POST[f_kd_nbk])) {
			$query_data_akun = mysql_query("SELECT max(tgp) as tgp,max(nbk) as nbk,sum(deb) as deb,sum(krd) as krd,ket FROM ".$view_tabel." WHERE left(tgp,7)='$tgl_trans' AND typ='$tipe' AND trx_status='1' GROUP BY nbk ORDER BY tgp,nbk", $conn) or die("Error Select Find Trx ".mysql_error());							
		}
		
		$jml_rec_data=mysql_num_rows($query_data_akun);	
		$no=1;	
		if ($jml_rec_data>0) {			
			$no = 1;
			while ($query_hasil = mysql_fetch_array($query_data_akun)){
				if ($tmbl_edit==2) {
					$view_tmbl_edit="<button type='submit' class='btn btn-primary btn-xs' id='btn$no' name='$btn' value='$query_hasil[1]' data-loading-text=\"Loading...\" onclick=\"$('#btn$no').button('loading');\">PILIH</button>";
				} else {
					$view_tmbl_edit="";
				}
				if ($tmbl_del==3) {
					$view_tmbl_del="";
				} else {
					$view_tmbl_del="";
				}

			$debet=tampil_uang($query_hasil[2],true);
			$kredit=tampil_uang($query_hasil[3],true);
			$tgl=ubah_tgl(substr($query_hasil[tgp],0,10));  
			//echo $tgl; 
		

			echo "<tr> 
				<td>$no.</td>
				<td >$tgl</td>
				<td>$query_hasil[1]</td>
				<td align=right>$debet</td>
				<td align=right>$kredit</td>
				<td>$query_hasil[4]</td>
				<td>$view_tmbl_edit</td>
				</tr>";
			$no ++;
			}
			
		}
		else{
			echo "<tr><td colspan='8'>Empty Data Record.</td></tr>";
			
		}
	}
?>
</table>
<!--</form>-->


</BODY>
</HTML>
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
