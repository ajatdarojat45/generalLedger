<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	include "../inc/inc_akses.php";
	include "../inc/inc_aed.php";
	
	$table_akun="mst_akun_".$company_id;
	
	if ($_GET[k_acc]=="ADD") {
		if ($tmbl_add<>1) {
			echo "<script language=javascript>
					alert (' ANDA TIDAK BERHAK MENGGUNAKAN MENU INI, SILAHKAN REFRESH HALAMAN WEB ANDA !!! ');
				</script>";		
			session_destroy();
			session_unset(); 
			exit ;
			}
	} else {
		if ($tmbl_edit<>2) {
			echo "<script language=javascript>
					alert (' ANDA TIDAK BERHAK MENGGUNAKAN MENU INI, SILAHKAN REFRESH HALAMAN WEB ANDA !!! ');
				</script>";		
			session_destroy();
			session_unset(); 
			exit ;
			}	
	}
?>
<!DOCTYPE html>
<HTML>
<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.css">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet">
    <link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet">
    <link href="../../style/style_utama.css" rel="stylesheet">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

     <![if lt IE 9]>
      <script src="bootstrap/html5shiv.js"></script>
      <script src="bootstrap/respond.min.js"></script>
    <![endif]>
</HEAD>

<BODY>
<?
	if ($_GET[k_acc]<>"ADD"){
		$query_data = mysql_query("SELECT * FROM $table_akun WHERE acc='$_GET[k_acc]'  AND acc_status='1' AND mcom_id='$company_id' ", $conn) or die("Error Select Show Acc ".mysql_error());		
		$data_hasil = mysql_fetch_array($query_data);
	} else {
		$data_hasil="";
	}
?>
<div class="panel panel-info">
<div class="panel-heading"><? if ($_GET[k_acc]=="ADD"){ echo "<b>Buat Akun Baru</b>"; } else { echo "<b>Edit Akun $_GET[k_acc]</b>"; }?>
</div>
<div class="panel-body">
<div class="form-inline">
<label for="kd-acc">Perkiraan :</label><br>
<input id="kd-acc" name="kd_acc" type="text" class="form-control" placeholder="Kode ?" value="<? if ($_GET[k_acc]=="ADD"){ if ($_POST[kd_acc]) { echo "$_POST[kd_acc]"; } else { echo "";}}else{echo "$_GET[k_acc]";};?>" size="15" maxlength="13" <? if ($_GET[k_acc]=="ADD"){echo "";}else{echo "readonly";};?> autofocus >
<input id="kd-nmp" name="kd_nmp" type="text" class="form-control" placeholder="Nama ?" value="<? if ($_POST[kd_nmp]){	echo "$_POST[kd_nmp]"; } else { echo "$data_hasil[nmp]"; } ?>" size="90" maxlength="100" autofocus>

<? if ($_POST[LevelGroup1]) { $lvl=$_POST[LevelGroup1]; } else { $lvl=$data_hasil[12] ;} ?>
<br/><br/><label>Level Perkiraan :</label><br>
	<div class="radio-inline text-primary">
		<input type="radio" name="LevelGroup1" id="chk_lvl1" value="1" <? if ($lvl=='1') { echo "Checked";} ?>>
		<label for="chk_lvl1">Level 1</label>	      	
	</div>
	<div class="radio-inline text-primary">
		<input type="radio" name="LevelGroup1" id="chk_lvl2" value="2" <? if ($lvl=='2') { echo "Checked";} ?>> 
		<label for="chk_lvl2">Level 2</label>	      	
	</div>
	<div class="radio-inline text-primary">
		<input type="radio" name="LevelGroup1" id="chk_lvl3" value="3" <? if ($lvl=='3') { echo "Checked";} ?>> 
		<label for="chk_lvl3">Level 3</label>	      	
	</div>
	<div class="radio-inline text-primary">
		<input type="radio" name="LevelGroup1" id="chk_lvl4" value="4" <? if ($lvl=='4') { echo "Checked";} ?>> 
		<label for="chk_lvl4">Level 4</label>	      	
	</div>
	<div class="radio-inline text-primary">
		<input type="radio" name="LevelGroup1" id="chk_lvl5" value="5" <? if ($lvl=='5' || empty($lvl)) { echo "Checked";} ?>> 
		<label for="chk_lvl5">Level 5</label>	      	
	</div>

<? if ($_POST[DKGroup1]) { $dk=$_POST[DKGroup1]; } else { $dk=$data_hasil[3] ;} ?>
<br/><label>Posisi Perkiraan :</label><br/>
	<div class="radio-inline text-primary">
		<input type="radio" id="chk_dk1" name="DKGroup1" value="D" <? if ($dk=='D' || empty($dk)) { echo "Checked";} ?>>
		<label for="chk_dk1">Debet</label>	      	
	</div>
	
	<div class="radio-inline text-primary">
		<input type="radio" id="chk_dk2" name="DKGroup1" value="K" <? if ($dk=='K') { echo "Checked";} ?>>
		<label for="chk_dk2">Kredit</label>	      	
	</div>
	
<? if ($_POST[NRGroup1]) { $nr=$_POST[NRGroup1]; } else { $nr=$data_hasil[2] ;} ?>
<br/><label>Jenis Perkiran :</label><br/>
	<div class="radio-inline text-primary">
		<input type="radio" id="chk_nr1" name="NRGroup1" value="N" <? if ($nr=='N') { echo "Checked";} ?>>
		<label for="chk_nr1">Neraca</label>	      	
	</div>
	
	<div class="radio-inline text-primary">
		<input type="radio" id="chk_nr2" name="NRGroup1" value="R" <? if ($nr=='R' || empty($nr) ) { echo "Checked";} ?>>
		<label for="chk_nr2">Rugi/Laba</label>	      	
	</div>

<? if ($_POST[HPPGroup1]) { $hpp=$_POST[HPPGroup1]; } else { $hpp=$data_hasil[8] ;} ?>
<br/><label>Jenis Perkiraan HPP :</label><br/>
	<div class="radio-inline text-primary">
		<input type="radio" id="chk_hpp1" name="HPPGroup1" value="Y" <? if ($hpp=='Y' || empty($hpp)) { echo "Checked";} ?>>
		<label for="chk_hpp1">Ya</label>	      	
	</div>
	
	<div class="radio-inline text-primary">
		<input type="radio" id="chk_hpp2" name="HPPGroup1" value="T" <? if ($hpp=='T') { echo "Checked";} ?>>
		<label for="chk_hpp2">Tidak</label>	      	
	</div>

<? if ($_POST[PPGroup1]) { $pusat=$_POST[PPGroup1]; } else { $pusat=$data_hasil[pusat] ;} ?>
<br/><label>Perkiraan Pusat :</label><br/>
	<div class="radio-inline text-primary">
		<input type="radio" id="chk_pp1" name="PPGroup1" value="K" <? if ($pusat=='K') { echo "Checked";} ?>>
		<label for="chk_hpp1">Kas</label>	      	
	</div>
	
	<div class="radio-inline text-primary">
		<input type="radio" id="chk_pp2" name="PPGroup1" value="B" <? if ($pusat=='B') { echo "Checked";} ?>>
		<label for="chk_hpp2">Bank</label>	      	
	</div>

	<div class="radio-inline text-primary">
		<input type="radio" id="chk_pp3" name="PPGroup1" value="A" <? if ($pusat=='A' || empty($pusat)) { echo "Checked";} ?>>
		<label for="chk_hpp2">Semua</label>	      	
	</div>

<? 

if ($_POST[kd_ket]) { $ket=$_POST[kd_ket]; } else { $ket=$data_hasil[ket] ;} 

?>

<br/><br/><label>Keterangan :</label><br/>
	<input name="kd_ket" type="text" class="form-control" placeholder="Keterangan ?" value="<? echo "$ket"; ?>" size="115" maxlength="50"></td>

<? 	if ($_POST[sal_thn]) { $salperthn=$_POST[sal_thn]; } else { $salperthn=$data_hasil[13] ;} 
	if ($_POST[sal_bln]) { $salperbln=$_POST[sal_bln]; } else { $salperbln=$data_hasil[14] ;} 
	if ($_POST[sal_jml]) { $salperjml=$_POST[saldo_jml]; } else { $salperjml=$data_hasil[15] ;} 
	
	if ($salperjml==0 || empty($salperjml)) { $salperjml=0; }
?>
<hr/>
	<label>Saldo Awal Tahun :</label> <input class="form-control" name="sal_thn" type="text" size="4" maxlength="4" placeholder="YYYY" value="<? echo "$salperthn"; ?>">	 <input class="form-control" name="sal_bln" type="text" size="2" maxlength="2" placeholder="MM" value="<? echo "$salperbln"; ?>">&nbsp;&nbsp;<label> Jumlah Saldo :</label> <input class="form-control" name="saldo_jml" type="text" style="text-align:right" placeholder="Jumlah Saldo ?" size="30" value="<? echo "$salperjml"; ?>">
<hr/>
<button id="btn_save" name="btn_save" type="submit" class="btn btn-success" accesskey="s" data-toggle="tooltip" data-placement="bottom" title="Save" value="<? echo $_GET[k_acc]; ?>"><span class="glyphicon glyphicon-ok" ></span></button>
				<button id="btn_cancel" name="btn_cancel" type="button" class="btn btn-success" onClick="klik_cancel()" accesskey="c" data-toggle="tooltip" data-placement="bottom" title="Cancel"><span class="glyphicon glyphicon-remove"></span></button>
</div>
</div>
</div>

<div id="area-pesan-edit">
</div>
<br>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<![if lt IE 9]>
	  <script src="../../bootstrap/html5shiv.js"></script>
	  <script src="../../bootstrap/respond.min.js"></script>
	<![endif]>
	
	<script src="../../js/jquery-1.11.0.min.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>
	
    <script src="../../js/jquery-ui-1.10.4.custom.js"></script>
    <script src="../../js/jquery.inputmask.js"></script>

</BODY>
</HTML>
<!-- session -->
<?
	
}

else { header("location:/glt/no_akses.htm"); }
?>
