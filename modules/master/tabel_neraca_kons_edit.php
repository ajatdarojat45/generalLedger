<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";
	
	//  Cek Hak Akses Tombol Add, Edit dan Delete
	include "../inc/inc_aed.php";
	if ($_GET[kd_aksi]=="ADD") { 
		$aksi="Tambah";
		$kode="AUTO";
		$nama="";
	} else { 
		$aksi="Ubah";
		$kode=$_GET[k_noset];
		$nama=$_GET[k_nmset];
	}
?>

<div class="form-inline">
	<b><? echo $aksi ;?> Daftar Neraca Konsolidasi</b> <br><br>
    Kode Setup 
    <input id="ssn" name="kd_noset" type="text" class="form-control" value="<? echo $kode; ?>" size="3" maxlength="3" readonly autofocus >
    Nama Setup 
	<input name="kd_nmset" type="text" class="form-control" value="<? echo $nama; ?>" size="50" maxlength="100"> 
    <button type='submit' name="btn_save_neraca" class="btn btn-danger" value="<? echo $aksi; ?>">SAVE</button>
    <button type='button' name="btn_cancel" class="btn btn-success" onClick="klik_cancel()">CANCEL</button>
	<br><br>
</div>
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
