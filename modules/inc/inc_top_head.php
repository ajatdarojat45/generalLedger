<?php session_start(); 
if (isset($_SESSION['app_glt'])) {
	include ("inc_akses.php");
	if($_POST[btn_ok_pt]){
		
		$company_name=$_POST[cmb_pil_pt];

		$que_user_pt = mysql_query("SELECT mcom_id,mcom_company_name,sys_tgl FROM mst_company WHERE mcom_company_name = '$company_name'", $conn) or die("Error Select Company User ".mysql_error());		
		$rec_user_pt = mysql_fetch_array($que_user_pt);	
		
		$company_id=$rec_user_pt[0];
		$sys_tgl=$rec_user_pt[2];
		$aktif_tgl=$sys_tgl;		

		$que_grant_pt = mysql_query("SELECT mgc_username,mcom_id,mcom_periode FROM mst_granting_company WHERE mcom_id = '$company_id' AND mgc_username='$_SESSION[app_glt]' ", $conn) or die(mysql_error());		
		$data_hasil_pt = mysql_fetch_array($que_grant_pt);	
		$ganti_pt=$data_hasil_pt[2];
		
		$que_upd_user = mysql_query("UPDATE mst_login SET mcom_id_last='$company_id', aktif_tgl='$aktif_tgl' WHERE mlog_username = '$_SESSION[app_glt]'", $conn) or die("Error Update Last Company User ".mysql_error());
		
		}	


 if($_POST[btn_ok_per]){
        
            $ubah_aktif_tgl=$_POST[txt_tahun]."-".$_POST[cmb_pil_bln]."-01";		
            
            if ($ubah_aktif_tgl > $sys_tgl) {
                //echo "( Invalid active date ! )";
?>
      <script language=javascript>
                    alert('Invalid Active Date $ubah_aktif_tgl > $sys_tgl !');
                </script>
<? } else {
                $aktif_tgl=$ubah_aktif_tgl;
                $que_upd_user = mysql_query("UPDATE mst_login SET aktif_tgl='$aktif_tgl' WHERE mlog_username = '$_SESSION[app_glt]'", $conn) or die("Error Update Last Active Date ".mysql_error());
            }		
} ?>
<form name="frm_ubah_pt" method="POST">
<div class="form-inline">
 <label class="form-control alert-warning text-left">&nbsp;&nbsp;System date : <? $tgl=get_system_date($sys_tgl); echo "$tgl";?>&nbsp;&nbsp;</label>			
		Perusahaan : <select class="form-control" name="cmb_pil_pt" value="-pilih-">
				<?
				$que_pt = mysql_query("SELECT a.mcom_id AS mcom_id,b.mcom_company_name AS mcom_company_name FROM mst_granting_company AS a LEFT JOIN mst_company AS b ON a.mcom_id=b.mcom_id WHERE a.mgc_username='$_SESSION[app_glt]' AND  a.mcom_id='$company_id' ORDER BY a.mcom_id ASC ", $conn) or die("Error Query Company");
				while ($data_pt = mysql_fetch_array($que_pt)){
					echo "<option>$data_pt[1]</option>";
				}
				$que_pt = mysql_query("SELECT a.mcom_id AS mcom_id,b.mcom_company_name AS mcom_company_name FROM mst_granting_company AS a LEFT JOIN mst_company AS b ON a.mcom_id=b.mcom_id WHERE a.mgc_username='$_SESSION[app_glt]'  AND  a.mcom_id<>'$company_id' ORDER BY a.mcom_id ASC ", $conn) or die("Error Query Company");
				while ($data_pt = mysql_fetch_array($que_pt)){
					echo "<option>$data_pt[1]</option>";
				}
				?>
		</select>
	  <input type="submit" value="Ubah" name="btn_ok_pt" id="btn_pt" class="btn btn-primary btn-xs" style="height:30px" > Active date : 
	  <select class="form-control" name="cmb_pil_bln" value="<? $bulan=get_bulan($aktif_tgl); echo $bulan; ?>" <? if ($ganti_pt=='N'){ echo "disabled style='background-color: #CCCCCC' "; } ?> >
			<option value="01" <? if (substr($aktif_tgl,5,2)=='01') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Januari</option>
			<option value="02" <? if (substr($aktif_tgl,5,2)=='02') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Februari</option>
			<option value="03" <? if (substr($aktif_tgl,5,2)=='03') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Maret</option>
			<option value="04" <? if (substr($aktif_tgl,5,2)=='04') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >April</option>
			<option value="05" <? if (substr($aktif_tgl,5,2)=='05') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Mei</option>
			<option value="06" <? if (substr($aktif_tgl,5,2)=='06') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Juni</option>
			<option value="07" <? if (substr($aktif_tgl,5,2)=='07') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Juli</option>
			<option value="08" <? if (substr($aktif_tgl,5,2)=='08') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Agustus</option>
			<option value="09" <? if (substr($aktif_tgl,5,2)=='09') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >September</option>
			<option value="10" <? if (substr($aktif_tgl,5,2)=='10') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Oktober</option>
			<option value="11" <? if (substr($aktif_tgl,5,2)=='11') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Nopember</option>
			<option value="12" <? if (substr($aktif_tgl,5,2)=='12') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Desember</option>
			</select>
			&nbsp;
			<input class="form-control" name="txt_tahun" type="number" value="<? $tahun=substr($aktif_tgl,0,4); echo $tahun; ?>" size="4" maxlength="4" min="2008" max="2050" step="1" <? if ($ganti_pt=='N'){ echo "disabled='disabled'"; } ?> >
			<input type="submit" value="Ubah" name="btn_ok_per" <? if ($ganti_pt=='N'){ echo "disabled"; } ?> class="btn btn-primary btn-xs"  style="height:30px" >
</div>
</form>

<? }?>