<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {	
	
	include "../inc/inc_akses.php";
	//include "../inc/inc_trans_menu.php";
	//ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	include "../inc/func_modul.php";
	include "../inc/inc_aed.php";
	
	$jenis_trans="Cashflow";
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tbl_mst_sup="mst_sup_".$company_id ;
	$tbl_mst_sis="mst_sistem" ;
	
	$tbl_trx_temp_fin="trx_tmp_case_fin";
	$tbl_trx_temp_tam="trx_tmp_case_tam";
	
	$tbl_trx="trx_caseglt_".$company_id ;

	// table data sistem asli (tidak bisa diubah oleh user akunting)
	$tbl_trx1="trx_case_tam_".$company_id ;

	// table data final jika ada perubahan isi jurnal oleh user akunting (posting ke trx_caseglt juga dari table ini)
	$tbl_trx2="trx_case_fin_".$company_id ;
	 
	$tgl_trans=substr($aktif_tgl,0,7);
	
	$qry_tmp_del=mysql_query("DELETE FROM $tbl_trx_temp_fin WHERE pemakai='$user_id'", $conn) or die("Error Delete Temp Case fin ");	
	$qry_tmp_del=mysql_query("DELETE FROM $tbl_trx_temp_tam WHERE pemakai='$user_id'", $conn) or die("Error Delete Temp Case tam ");	
	
	// case fin
	$qry_tmp_ins = mysql_query("INSERT INTO $tbl_trx_temp_fin (SELECT * FROM $tbl_trx2 WHERE nbk='$_GET[nbk]' AND trx_status='1' AND mcom_id='$company_id' order by seq)", $conn) or die("Error Insert Temp Case fin ".mysql_error());	
	$qry_tmp_upd = mysql_query("UPDATE $tbl_trx_temp_fin SET pemakai='$user_id' WHERE nbk='$_GET[nbk]'", $conn) or die("Error Update Temp Case fin ".mysql_error());		
	
	// case tam
	$qry_tmp_ins = mysql_query("INSERT INTO $tbl_trx_temp_tam (SELECT * FROM $tbl_trx1 WHERE nbk='$_GET[nbk]' AND trx_status='1' AND mcom_id='$company_id' order by seq)", $conn) or die("Error Insert Temp Case tam ".mysql_error());	
				
	$qry_tmp_upd = mysql_query("UPDATE $tbl_trx_temp_tam SET pemakai='$user_id' WHERE nbk='$_GET[nbk]'", $conn) or die("Error Update Temp Case tam ".mysql_error());		

	$qry_fin = mysql_query("SELECT a.*,b.nmp as nmp,c.nm_sup as nm_sup FROM $tbl_trx_temp_fin as a left join $tbl_mst_akun as b on a.acc=b.acc left join ".$tbl_mst_sup." as c on a.kd_sup=c.kd_sup WHERE a.pemakai='$user_id' AND a.trx_status='1' ORDER BY a.seq", $conn) or die("Error Select Show Detail ".mysql_error());

	$qry_bgc = mysql_query("SELECT * FROM $tbl_trx_temp_fin WHERE pemakai='$user_id' AND trx_status='1' ORDER BY seq", $conn) or die("Error Select Show Detail ".mysql_error());
	$databgc = mysql_fetch_array($qry_bgc);
	
	if (trim($databgc[nobgc])=="") { $nobgc="-"; } else {$nobgc=$databgc[nobgc];}
	
	echo "Nomor Cek/Giro : $nobgc Tipe Transaksi : $databgc[typ] <br>";
	
	echo "<table class='table table-bordered table-hover table-condensed' align='center'>
		<th width='2%' class='info'>SEQ</th>
		<th width='10%' class='info'>Kode Akun</th>
		<th width='30%' class='info'>Nama Akun</th>
		<th width='30%' class='info'>Keterangan</th>
		<th width='5%' class='info'>Supplier</th>
		<th width='10%' class='info text-right'>Debet</th>
		<th width='10%' class='info text-right'>Kredit</th>";	
	
	$no=0;	
	$total_d=0;
	$total_k=0;
	
	while ($data = mysql_fetch_array($qry_fin)) {
		$no ++;
		$total_d=$total_d+$data[deb];
		$total_k=$total_k+$data[krd];
		
		if ($data[deb]==0){
			$nildebet=" - ";
		} else {
			$nildebet=tampil_uang($data[deb],true);			
		}
		
		if ($data[krd]==0){
			$nilkredit=" - ";
		} else {				
			$nilkredit=tampil_uang($data[krd],true);
		}
		echo "<tr height='20'>
			<td><input type='hidden' name=\"nomor_seq\" id=\"seq_$data[seq]\" value=\"$data[seq]\"/>$data[seq]</td>
			<td id=\"acc_$data[seq]\">$data[acc]</td>
			<td id=\"nmp_$data[seq]\">$data[nmp]</td>
			<td id=\"ket_$data[seq]\">$data[ket]</td>
			<td id=\"kdsup_$data[seq]\">$data[kd_sup]</td>
			<td align=\"right\"> $nildebet</td>
			<td align=\"right\"> $nilkredit</td>
			</tr>" ;
	}
	
	$totaldebet=tampil_uang($total_d,true);			
	$totalkredit=tampil_uang($total_k,true);			
	
	echo "<tr height='30' class='text-danger'><td colspan='5' align='right'><b>TOTAL JUMLAH&nbsp;&nbsp;</b></td>
			<td align=\"right\" ><b> $totaldebet</b></td>
			<td align=\"right\"><b> $totalkredit</b></td>
			</tr>" ;
	
	if ($no==0) {  echo "<tr><td colspan='9'>Empty detail..</td></tr>" ; }
			  
	echo "</table>";
	

}
?>
