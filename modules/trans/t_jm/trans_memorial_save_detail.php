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
	
	$jenis_trans="Memorial";
	$tipe_trans="M";
	
	$tbl_view_trx="trans_jm".$company_id ; 
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tbl_mst_sup="mst_sup_".$company_id ;
	
	$tbl_trx_temp="trx_tmp_caseglt_".$company_id ;
	$tbl_trx="trx_caseglt_".$company_id ;
	
	/* 
	
	ADD DETAIL
	----------------------
	Array POST ( 
	
	[txt_tglaktif] => 01-02-2014 
	[txt_noakun] => 1-01-01-01-01 
	[txt_nmakun] => Kas Kecil 
	[txt_ket] => Biaya perhitungan Aktuaria No. Invoice 5418/INV/I/ 
	[txt_debet] => 1,080,000.00 
	[tot_debet] => 1080000 
	[tot_kredit] => 1080000 
	[txt_kredit] => 1,080,000.00 
	[txt_d_no_akun] => 5-40-05-01-01 
	[txt_d_nm_akun] => Beban Pengiriman & Pengepakan Sirkulasi MBM DIY-Ja 
	[txt_d_no_sup] => 
	[txt_d_nm_sup] => 
	[txt_d_jml] => 88888 
	[txt_d_nm_ket] => asdfasdfasd 
	[nomor_seq] => 003 ) 
	
	Array GET( 
	[add_detail] => true 
	[no_bukti] => KK-033/02/14 
	[seq] => 004
	[tipe] => K ) 
	[txt_tglbukti] => 19-02-2014 
	
	Add...004 2014-02-19
	*/
	
	if ($_GET[add_detail]=='true') {
		if ($_GET[dk]=='D') {
			$jml_debet=$_POST[txt_d_jml];		 
			$jml_kredit=0;
		} else {
			$jml_debet=0;		 
			$jml_kredit=$_POST[txt_d_jml];
		}
		
		$seq=$_GET[seq];
		$tgp=substr($_GET[txt_tglbukti],6,4).'-'.substr($_GET[txt_tglbukti],3,2).'-'.substr($_GET[txt_tglbukti],0,2);
		
		 // simpan SEQ transaksinya 001 dst..
		 // Kode Akun detail
		$qry ="SELECT acc FROM $tbl_trx_temp  WHERE nbk='$_GET[no_bukti]' AND user_id='$user_id' AND acc='$_POST[txt_d_no_akun]'";		
		$qry_sel = mysql_query($qry, $conn) or die("Error Insert Temp Caseglt ".mysql_error());
		$data = mysql_fetch_array($qry_sel);
		$macc=$data[acc];
		 // cek apakah akun detail sudah ada		 
		if ($macc==$_POST[txt_d_no_akun]) {
			echo "<script>	$(\"#area_cari_data\").html(\"<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button> <b>Kode Akun Detail</b> sudah ada ! </div>\"); </script>";	
				
		} else {
			$ket=str_replace("'","\'",$_POST[txt_d_nm_ket]);
			$ket=str_replace('"',"\'",$ket);
				 
			$qry ="INSERT INTO $tbl_trx_temp (mcom_id, trx_status, nbk, tgp, seq, typ, acc, deb, krd, ket, flg, kd_sup, pemakai, tgl_input, user_id) VALUES ('$company_id', '1', '$_GET[no_bukti]', '$tgp', '$seq', '$tipe_trans', '$_POST[txt_d_no_akun]', $jml_debet, $jml_kredit, '$ket', '0','$_POST[txt_d_no_sup]', '$user_id', now(), '$user_id') ";		
			$qry_ins = mysql_query($qry, $conn) or die("Error Insert New Detail ".mysql_error());
		}
	}		
	
	if ($_GET[edit_detail]=='true') {
	
		if ($_GET[dk]=='D') {
			$jml_debet=$_POST[txt_d_jml];		 
			$jml_kredit=0;
		} else {
			$jml_debet=0;		 
			$jml_kredit=$_POST[txt_d_jml];
		}
		
		$seq=$_GET[seq];
		
		$ket=str_replace("'","\'",$_POST[txt_d_nm_ket]);
		$ket=str_replace('"',"\'",$ket);
		
		$qry ="UPDATE $tbl_trx_temp SET acc='$_POST[txt_d_no_akun]' , deb=$jml_debet, krd=$jml_kredit, ket='$ket', kd_sup='$_POST[txt_d_no_sup]', pemakai='$user_id', tgl_input='$_POST[cdatetime]', user_id='$user_id' WHERE nbk='$_GET[no_bukti]' AND seq='$seq' AND mcom_id='$company_id' AND user_id='$user_id'";		
		$qry_ins = mysql_query($qry, $conn) or die("Error Update Temp Caseglt ".mysql_error());

		// kalkulasi ulang total debet dan kredit
		$qry_tmp = mysql_query("SELECT SUM(deb) as total_debet, SUM(krd) as total_kredit FROM ".$tbl_trx_temp." WHERE user_id='$user_id' AND trx_status='1' ", $conn) or die("Error Select Total Transaction ".mysql_error());
		$data_total=mysql_fetch_array($qry_tmp);	
	}
	
	if ($_GET[delete_detail]=='true') {
		$seq=$_GET[seq];
		
		$qry ="DELETE FROM $tbl_trx_temp WHERE nbk='$_GET[no_bukti]' AND seq='$seq' AND mcom_id='$company_id' AND user_id='$user_id'";		
		$qry_del = mysql_query($qry, $conn) or die("Error Delete Temp Caseglt ".mysql_error());
		
		// urut ulang nomor seq
		$qry ="SELECT * FROM $tbl_trx_temp WHERE nbk='$_GET[no_bukti]' AND mcom_id='$company_id' AND user_id='$user_id' ORDER BY seq";		
		$qry_sel = mysql_query($qry, $conn) or die("Error Delete Temp Caseglt ".mysql_error());
		$no=1;			
		while ($data=mysql_fetch_array($qry_sel)) {
			if ($data[seq]<>'000'){
				$cari_seq=$data[seq];
				$no_seq="000$no";
				
				//ambil tiga karakter dari belakang					
				$new_seq=substr($no_seq, -3); 
									
				$qry ="UPDATE $tbl_trx_temp SET seq='$new_seq' WHERE nbk='$_GET[no_bukti]' AND seq='$cari_seq' AND mcom_id='$company_id' AND user_id='$user_id'";		
				$qry_del = mysql_query($qry, $conn) or die("Error Delete 000 Caseglt ".mysql_error());								
				$no ++;
			}
		}
	}
	
	echo "<table class='table table-bordered table-hover table-condensed' align='center'>
		<th colspan='2' width='2%' class='info'>Editing</th>
		<th width='2%' class='info'>SEQ</th>
		<th width='10%' class='info'>Kode Akun</th>
		<th width='30%' class='info'>Nama Akun</th>
		<th width='30%' class='info'>Keterangan</th>
		<th width='5%' class='info'>Supplier</th>
		<th width='10%' class='info text-right'>Debet</th>
		<th width='10%' class='info text-right'>Kredit</th>";	
	
	// refresh total debet dan kredit
		
	$qry_tmp = mysql_query("SELECT SUM(deb) as total_debet, SUM(krd) as total_kredit FROM ".$tbl_trx_temp." WHERE user_id='$user_id' AND trx_status='1' ", $conn) or die("Error Select Total Transaction ".mysql_error());
	$data_total=mysql_fetch_array($qry_tmp);
	
	$txtdebet=tampil_uang($data_total[total_debet],true);				
	$txtkredit=tampil_uang($data_total[total_kredit],true);	
		
	$view_total="<input type='hidden' name=\"total_debet\" id=\"t_debet\" value=\"$data_total[total_debet]\"/> <input type='hidden' name=\"total_kredit\" id=\"t_kredit\" value=\"$data_total[total_kredit]\"/><input type='hidden' name=\"total_debet\" id=\"txt_debet\" value=\"$txtdebet\"/> <input type='hidden' name=\"total_kredit\" id=\"txt_kredit\" value=\"$txtkredit\"/>";
		
	$qry_tmp = mysql_query("SELECT a.*,b.nmp as nmp,c.nm_sup as nm_sup FROM ".$tbl_trx_temp." as a left join ".$tbl_mst_akun." as b on a.acc=b.acc left join ".$tbl_mst_sup." as c on a.kd_sup=c.kd_sup WHERE a.user_id='$user_id' AND a.trx_status='1' ORDER BY a.seq", $conn) or die("Error Select Show Detail ".mysql_error());
	$no=0;			
	while ($data = mysql_fetch_array($qry_tmp)) {
		$no ++;
		if ($tmbl_edit==2) {
			if ($data[deb]==0) { $dk="K" ;} else { $dk="D" ;}
			
			$ket=str_replace("'","\'",$data[ket]);
			$ket=str_replace('"',"\'",$ket);
		
			$view_tmbl_edit="<button type='button' class='btn-link' name=\"btn_edit_detail\" id=\"edit_$data[seq]\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit data $data[seq]\" onclick=\"klik_d_edit('$data[seq]','$data[acc]','$data[nmp]','$ket','$data[kd_sup]','$data[nm_sup]','$data[deb]','$data[krd]','$dk')\" ><span class=\"glyphicon glyphicon-edit\"/></span></button>";
			
		} else {
			$view_tmbl_edit="";
		}
		if ($tmbl_del==3) {
			$view_tmbl_del="<button type='button' class='btn-link' onclick=\"klik_d_delete('$data[seq]')\" name=\"btn_delete_detail\" id=\"delete_$data[seq]\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete data $data[seq]\" ><span class=\"glyphicon glyphicon-trash\"/></span></button>";						
						
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
				 <td></td>
				<td></td>
				<td >$data[seq]</td>
				<td id=\"acc_$data[seq]\">$data[acc]</td>
				<td id=\"nmp_$data[seq]\">$data[nmp]</td>
				<td id=\"ket_$data[seq]\">$data[ket]</td>
				<td id=\"kdsup_$data[seq]\">$data[kd_sup]</td>
				<td align=\"right\"> $nildebet</td>
				<td align=\"right\"> $nilkredit</td>
				</tr>" ;
		} else {
			echo "<tr>
				 <td>$view_tmbl_edit</td>
				<td>$view_tmbl_del</td>
				<td><input type='hidden' name=\"nomor_seq\" id=\"seq_$data[seq]\" value=\"$data[seq]\"/>$data[seq]</td>
				<td id=\"acc_$data[seq]\">$data[acc]</td>
				<td id=\"nmp_$data[seq]\">$data[nmp]</td>
				<td id=\"ket_$data[seq]\">$data[ket]</td>
				<td id=\"kdsup_$data[seq]\">$data[kd_sup]</td>
				<td align=\"right\"> $nildebet</td>
				<td align=\"right\"> $nilkredit</td>
				</tr><tr><td colspan='9'><div id='hapus-detail-$data[seq]'></div></td></tr>" ;
		}
	}

	if ($no==0) {  echo "<tr><td colspan='9'>Empty detail</td></tr>" ; }
			  
	echo "</table> $view_total ";
	

}
?>