<?php
session_start();
/*
print_r($_POST);
print_r($_GET);
print_r('<br>');
*/
if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";
	// include "../inc/inc_trans_menu.php";
	// ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);
	
	//  Cek Hak Akses Tombol Add, Edit dan Delete
	include "../inc/inc_aed.php";
	
	//echo "Add = $tmbl_add - Edit = $tmbl_edit - Delete = $tmbl_del"; 
	
	
	if($_GET[btn_save_pt]=='true'){
		if($_GET[kd_aksi]=='ADD') {
			$nama = str_replace("'","''","$_POST[kd_nmpt]");	
			$que_cek=mysql_query("SELECT * FROM mst_company WHERE mcom_company_name='$nama' AND mcom_company_name<>'$_POST[kd_pt]'", $conn) or die(mysql_error());
			$row_avaibility = mysql_num_rows($que_cek);
			if ($row_avaibility) {
				$tipe_alert=1;
				$textout="Company Name Already Exist !";
			} else {
				$nama = str_replace("'","''","$_POST[kd_nmpt]");			
				$que_saved = mysql_query("INSERT INTO mst_company (mcom_status,mcom_company_name,mcom_address,mcom_zipcode,mcom_phone,mcom_fax,mcom_npwp,mcom_stamp_date,mcom_stamp_user) VALUES ('1','$nama','$_POST[kd_alamat]','$_POST[kd_kpos]','$_POST[kd_telpon]','$_POST[kd_fax]','$_POST[kd_npwp]',now(),'$user_id')", $conn) or die("Error Insert New Company ".mysql_error());
				$textout="New Company Successfully Added !";
			}
		}
		
		if($_GET[kd_aksi]=='EDIT') {
			$nama = str_replace("'","''","$_POST[kd_nmpt]");						
			$query_data = mysql_query("SELECT mcom_company_name FROM mst_company WHERE mcom_id<>'$_POST[kd_pt]' AND mcom_company_name='$nama' ", $conn) or die("Error Validasi New Company ".mysql_error());
					$row_already = mysql_num_rows($query_data);
					if ($row_already) {
					//--already exist
						echo "<script language=javascript>
							alert (' Error : Kode Company dengan nama tersebut sudah ada ! ');
							</script>";
					} else {
						// Simpan perubahan data akun
						$nama = str_replace("'","''","$_POST[kd_nmpt]");						
						$que_saved  = mysql_query("UPDATE mst_company SET mcom_company_name='$nama', mcom_address='$_POST[kd_alamat]', mcom_zipcode='$_POST[kd_kpos]', mcom_phone='$_POST[kd_telpon]', mcom_fax='$_POST[kd_fax]', mcom_npwp='$_POST[kd_npwp]', mcom_stamp_user='$user_id', mcom_stamp_date=now() WHERE mcom_id='$_POST[kd_pt]' ", $conn) or die("Error Update Company ".mysql_error());		
						$textout="Data Company Successfully saved !";
						
					}
		}
		
		if($_GET[kd_aksi]=='DELETE') {
			
			$query_data=mysql_query("SELECT mcom_status FROM mst_company WHERE mcom_id='$_GET[kd_pt]' ", $conn) or die(mysql_error());
			$cekdata = mysql_fetch_array($query_data);
			
			if ($cekdata[0]=='0') { $nil='1';} else { $nil='0';}
			
			$que_saved=mysql_query("UPDATE mst_company SET mcom_status='$nil', mcom_stamp_user='$user_id', mcom_stamp_date=now() WHERE mcom_id='$_GET[kd_pt]' ", $conn) or die(mysql_error());
			$textout="Data Company Disabled successfully saved !";
		}
	}
?>
<HTML>
<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.min.css"></HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<BODY>
<?
	$get_id = mysql_query("SELECT MAX(mcom_id) FROM mst_company ", $conn) or die("Error Query Get Max Company ID");
	$fet_id = mysql_fetch_array($get_id);
	$nui_id = $fet_id[0]+1;
	
	if ($_GET[kd_aksi]<>"ADD" && $_GET[kd_aksi]<>"DELETE"){
		$query_data = mysql_query("SELECT * FROM mst_company WHERE mcom_id='$_POST[kd_pt]'", $conn) or die("Error Select Show Company ".mysql_error());		
		$data_hasil = mysql_fetch_array($query_data);
	}
	
	if ($_GET[kd_aksi]<>"DELETE"){
?>
	Kode Perusahaan:<br>
	<input id="btn01" type="text" name="kd_pt" size="5" value="<? 
	if ($_GET[kd_aksi]=="ADD") 
	{ if ($_POST[kd_pt]) { echo $_POST[kd_pt]; } else {echo $nui_id; } } else { echo $_GET[kd_pt];}
	
	?>" placeholder="Company ID.?" data-toggle="tooltip" data-placement="bottom" title="Kode Perusahaan" readonly >
	<br>
	Nama Perusahaan:
	<div class="input-group input-group-sm">
	<input id="btn02" type="text" name="kd_nmpt" class="form-control input-sm" value="<? if ($_POST[kd_nmpt]){	echo $_POST[kd_nmpt];	} else { echo $data_hasil[mcom_company_name];	} ?>" placeholder="Company Name ?" data-toggle="tooltip" data-placement="bottom" title="Nama Perusahaan" required>
	<span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span>
	</div>
	Alamat Perusahaan:
	<textarea id="btn03" name="kd_alamat" form="frmmodul" class="form-control input-sm" placeholder="Company Address ?" data-toggle="tooltip" data-placement="bottom" title="Alamat Perusahaan" style="overflow:auto;resize:none"><? if ($_POST[kd_alamat]){	echo "$_POST[kd_alamat]"; } else { echo "$data_hasil[mcom_address]";	} ?></textarea>
	Kode Pos :
	<input id="btn04" type="text" name="kd_kpos" class="form-control input-sm" value="<? if ($_POST[kd_kpos]){	echo "$_POST[kd_kpos]";	} else { echo "$data_hasil[mcom_zipcode]";	} ?>" placeholder="Postal Code ?" data-toggle="tooltip" data-placement="bottom" title="Kode Pos">
	Telpon :
	<input id="btn05" type="text" name="kd_telpon" class="form-control input-sm" value="<? if ($_POST[kd_telpon]){	echo "$_POST[kd_telpon]";	} else { echo "$data_hasil[mcom_phone]";	} ?>" placeholder="Phone ?" data-toggle="tooltip" data-placement="bottom" title="Nomor Telpon">
	Fax :
	<input id="btn06" type="text" name="kd_fax" class="form-control input-sm" value="<? if ($_POST[kd_fax]){	echo "$_POST[kd_fax]";	} else { echo "$data_hasil[mcom_fax]";	} ?>" placeholder="Fax ?" data-toggle="tooltip" data-placement="bottom" title="Nomor Fax">
	NPWP :
	<input id="btn07" type="text" name="kd_npwp" class="form-control input-sm" value="<? if ($_POST[kd_npwp]){	echo "$_POST[kd_npwp]";	} else { echo "$data_hasil[mcom_npwp]";	} ?>" placeholder="Tax Number ?" data-toggle="tooltip" data-placement="bottom" title="Nomor NPWP">
<?	
	}
?>
<div id="tempat-pesan">
<?	
	if ($que_saved==1){
			echo "<div class='button btn-sm'><div class='alert alert-success alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a> $textout </div></div>";
			$que_saved=0;					
	}
	
?>
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
