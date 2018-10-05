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
	
	$jenis_trans="BS";
	$tbl_view_trx="trans_bs".$company_id ; 
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tbl_mst_sup="mst_sup_".$company_id ;
	
	$tbl_trx_temp="trx_tmp_caseglt_".$company_id ;
	$tbl_trx="trx_caseglt_".$company_id ;
	 
	$tgl_trans=substr($aktif_tgl,0,7);
	
	$jumdata = "Blank";
	
	$que_sel = "SELECT acc_kas,acc_bank,acc_bs FROM mst_company WHERE mcom_id='$company_id' ";		
	$que_view = mysql_query($que_sel, $conn) or die("Error Query select Acc Pusat");
	$data_pusat = mysql_fetch_array($que_view);		

	$acc=$data_pusat[acc_bs];
	
	$que_sel = "SELECT nmp FROM ".$tbl_mst_akun." WHERE acc='$acc' ";		
	$que_view = mysql_query($que_sel, $conn) or die("Error Query acc name");
	$data_pusat = mysql_fetch_array($que_view);			
	
	$nmp=$data_pusat[nmp];
	
	$nbk="";
	
	$tgp=$_POST[cdate];;

	$kd_sup="";
	$nm_sup="";
	$tipe=substr($nbk,1,1);
	
	//transform
	$txtdebet="0.00";				
	$txtkredit="0.00";						
	
	//murni
	$totdebet="0";				
	$totkredit="0";						

	$qry_tmp_del=mysql_query("DELETE FROM $tbl_trx_temp WHERE user_id='$user_id'", $conn) or die("Error Delete Temp Caseglt ");	
	
	// Jika sedang retriev transaksi lama
	if ($_GET[kode_reset]<>"New"){	
		
		/*echo "<script> alert('reset bukti $_GET[kode_reset]');</script>";*/
		
		$nbk=trim($_GET[kode_reset]);
				
		$qry_tmp_ins = mysql_query("INSERT INTO $tbl_trx_temp (SELECT * FROM $tbl_trx WHERE nbk='$nbk' AND trx_status='1' AND mcom_id='$company_id')", $conn) or die("Error Insert Temp Caseglt ".mysql_error());			
		$qry_tmp_upd = mysql_query("UPDATE $tbl_trx_temp SET user_id='$user_id' WHERE nbk='$nbk'", $conn) or die("Error Insert Temp Caseglt ".mysql_error());					

		$qry_tmp2 = mysql_query("SELECT a.*,b.nmp as nmp,c.nm_sup as nm_sup FROM $tbl_trx_temp as a left join $tbl_mst_akun as b on a.acc=b.acc left join $tbl_mst_sup as c on a.kd_sup=c.kd_sup WHERE a.user_id='$user_id' AND a.seq='000' ", $conn) or die("Error Select Temp Caseglt ".mysql_error());
		$data_top = mysql_fetch_array($qry_tmp2);
	
		$nbk=$data_top[nbk];
		$tgp=$data_top[tgp];
		$acc=$data_top[acc];
		$nmp=$data_top[nmp];
		$kd_sup=$data_top[kd_sup];
		$nm_sup=$data_top[nm_sup];
		$ket=$data_top[ket];
		$flg=$data_top[flg];

		if ($flg==1) {
			$data_cf="From Cashflow";
		}
		
		$tipe=substr($nbk,1,1);
		
		$ket=str_replace("'","\'",$data_top[ket]);
		$ket=str_replace('"',"\'",$ket);
		
		$qry_tmp3 = mysql_query("SELECT SUM(deb) as total_debet, SUM(krd) as total_kredit FROM $tbl_trx_temp WHERE user_id='$user_id' AND trx_status='1' ", $conn) or die("Error Select Total Transaction ".mysql_error());
		$data_total=mysql_fetch_array($qry_tmp3);

		$txtdebet=tampil_uang($data_total[total_debet],true);				
		$txtkredit=tampil_uang($data_total[total_kredit],true);	
							
		$totdebet=$data_total[total_debet];				
		$totkredit=$data_total[total_kredit];	
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
		
	// total debet dan kredit nilai integer murni (tidak ditransform)	
	$view_total="<input type='hidden' name=\"total_debet\" id=\"t_debet\" value=\"$totdebet\"/> <input type='hidden' name=\"total_kredit\" id=\"t_kredit\" value=\"$totkredit\"/><input type='hidden' name=\"total_debet\" id=\"txt_debet\" value=\"$txtdebet\"/> <input type='hidden' name=\"total_kredit\" id=\"txt_kredit\" value=\"$txtkredit\"/>";
		
	$qry_tmp4 = mysql_query("SELECT a.*,b.nmp as nmp,c.nm_sup as nm_sup FROM $tbl_trx_temp as a left join $tbl_mst_akun as b on a.acc=b.acc left join ".$tbl_mst_sup." as c on a.kd_sup=c.kd_sup WHERE a.user_id='$user_id' AND a.trx_status='1' ORDER BY a.seq", $conn) or die("Error Select Show Detail ".mysql_error());
	$no=0;			
	while ($data = mysql_fetch_array($qry_tmp4)) {
		$no ++;
		if ($tmbl_edit==2) {
			$ket=str_replace("'","\'",$data[ket]);
			$ket=str_replace('"',"\'",$ket);
			
			$view_tmbl_edit="<button type='button' class='btn-link' name=\"btn_edit_detail\" id=\"edit_$data[seq]\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit data $data[seq]\" onclick=\"klik_d_edit('$data[seq]','$data[acc]','$data[nmp]','$ket','$data[kd_sup]','$data[nm_sup]','$data[deb]','$data[krd]')\" ><span class=\"glyphicon glyphicon-edit\"/></span></button>";
			
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

	if ($no==0) {  echo "<tr><td colspan='9'>Empty detail..</td></tr>" ; }
			  
	echo "</table> $view_total ";
	

}
?>