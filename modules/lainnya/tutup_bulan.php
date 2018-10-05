<?php
session_start();
//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	//include "../inc/inc_akses.php";
	include ("../inc/func_modul.php");
	include "../inc/inc_trans_menu.php";
		
	ins_trans_menu($_GET['id_menu'], $_SESSION['app_glt']);	
	
?>
	  
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>GL TEMPO</title>

	<link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet">
	<link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet">
	<link href="../../style/style_utama.css" rel="stylesheet">
	<link href="../../themes/sunny/easyui.css" rel="stylesheet" >
	<link href="../../themes/icon.css" rel="stylesheet" >
	
</head>
<body>
	<div class="navbar navbar-default navbar-form" role="navigation">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-right">
				<li>		
				<?
					include "../inc/inc_top_head.php";
					
					$table_akun="mst_akun_".$company_id;
					$table_glm="trx_caseglm_".$company_id;
					$table_glt="trx_caseglt_".$company_id;
					$table_fin="trx_case_fin_".$company_id;
					$table_tam="trx_case_tam_".$company_id;
				?>
				</li>
			</ul>
		</div>
	</div>
	
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><span class="glyphicon glyphicon-refresh"></span> PROSES TUTUP BULAN SYSTEM</h3>
		</div> 
		<div class="panel-body">
			<form name="frmtutup" method="POST" >
				<div class="row btn-sm">
					<label class="col-md-2 text-right">Periode Sistem :</label>
					<div class="col-md-2">
						<select  name="cmb_sys_bln" disabled class="form-control" style="background-color: #CCCCCC" value="<? $bulan=get_bulan($sys_tgl); echo $bulan; ?>">  
							<option value="01" <? if (substr($sys_tgl,5,2)=='01') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Januari</option>
							<option value="02" <? if (substr($sys_tgl,5,2)=='02') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Februari</option>
							<option value="03" <? if (substr($sys_tgl,5,2)=='03') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Maret</option>
							<option value="04" <? if (substr($sys_tgl,5,2)=='04') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >April</option>
							<option value="05" <? if (substr($sys_tgl,5,2)=='05') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Mei</option>
							<option value="06" <? if (substr($sys_tgl,5,2)=='06') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Juni</option>
							<option value="07" <? if (substr($sys_tgl,5,2)=='07') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Juli</option>
							<option value="08" <? if (substr($sys_tgl,5,2)=='08') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Agustus</option>
							<option value="09" <? if (substr($sys_tgl,5,2)=='09') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >September</option>
							<option value="10" <? if (substr($sys_tgl,5,2)=='10') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Oktober</option>
							<option value="11" <? if (substr($sys_tgl,5,2)=='11') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Nopember</option>
							<option value="12" <? if (substr($sys_tgl,5,2)=='12') { echo 'selected' ;} if ($ganti_pt=='N'){ echo "disabled"; } ?> >Desember</option>
						</select>	
					</div>
					<div class="col-md-1">
						<input name="txt_sys_tahun" type="text" disabled class="form-control" style="background-color: #CCCCCC" value="<? $tahun=substr($aktif_tgl,0,4); echo $tahun; ?>" size="3" maxlength="4" >
					</div>
				</div>
				<div class="row btn-sm">
					<label class="col-md-2 text-right">Periode Aktif :</label>
					<div class="col-md-2">
						<select  name="cmb_aktif_bln" disabled class="form-control" style="background-color: #CCCCCC" value="<? $bulan=get_bulan($aktif_tgl); echo $bulan; ?>" >  
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
					</div>
					<div class="col-md-1">
						<input name="txt_sys_tahun" type="text" disabled class="form-control" style="background-color: #CCCCCC" value="<? $tahun=substr($aktif_tgl,0,4); echo $tahun; ?>" size="3" maxlength="4" >
					</div>
				</div>
				<div class="row btn-sm">
					<label class="col-md-2 text-right">Periode Proses :</label>
					<div class="col-md-2">
						<select  name="cmb_pil_bln" class="form-control" value="<? $bulan=get_bulan($aktif_tgl); echo $bulan; ?>" <? if ($ganti_pt=='N'){ echo "disabled style='background-color: #CCCCCC' "; } ?> >  
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
					</div>
					<div class="col-md-1">
						<input name="txt_pil_tahun" type="text" class="form-control" value="<? $tahun=substr($aktif_tgl,0,4); echo $tahun; ?>" size="3" maxlength="4" <? if ($ganti_pt=='N'){ echo "disabled style='background-color: #CCCCCC'"; } ?>>    
					</div>
				</div>
				<div class="row btn-sm">
					<label class="col-md-2 text-right">Tutup Bulan :</label>					
					<div class="col-md-2">	
						<input type="checkbox" name="chk_tutup" value="YA">
					</div>					
				</div>
				<div class="row btn-sm">
					<div class="col-md-2 col-md-offset-2">
						<input type="submit" value="PROCESS" name="btn_proses_tutup" class="btn btn-danger" onClick="return confirm('Proses Posting Periode Tersebut ?')">
					</div>					
				</div>
				<input type="hidden" value="1" name="nil_proses">
			</form>
		</div>
		<div class="panel-footer">
			<div id="nomor-proses"></div>
			<div class="progress progress-striped active">
			  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
				<span><div id="tampil_selesai">0% Complete...</div></span>
			  </div>
			</div>
			<div id="img_proses">
			</div>	
		</div>
	</div>
	

	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="../../js/jquery.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>   
	<script src="../../js/jquery.easyui.min.js"></script>
	<script src="../../js/lib_fungsi.js"></script>	
	<script src="tutup_aksi.js"></script>
</body>
</html>
<!-- session -->
<?
	if($_POST[btn_proses_tutup]){
		
		$pil_tahun	=$_POST[txt_pil_tahun];
		$pil_bulan	=$_POST[cmb_pil_bln];
		
		$per_sys	=$sys_tgl;
		$per_aktif	=$aktif_tgl;
		$per_pilih	=$_POST[txt_pil_tahun]."-".$_POST[cmb_pil_bln]."-01";
		$tutup		=$_POST[chk_tutup];
		
		$tot_proses = 5 ;
		$no_proses  = 1;
		$nm_proses  = "";
		
		$per_sys_bulan=substr($per_sys,5,2);
		$xBulan=$per_sys_bulan;
		
		//echo "Periode bulan system $per_sys_bulan";
		
		//echo $bln;
		
		//exit;
		
		/*
		nTahun=LEFT(nPer,4)
		nBulan=RIGHT(nPer,2)
		xBulan=RIGHT(nPerSis,2)

		IF LEFT(nPerSis,4)<>nTahun
			xBulan='12'
		ENDIF

		 1. proses cek akun baru
		 2. proses saldo awal
		 3. proses data transaksi
		 4. proses saldo akhir
		 5. proses posting nilai semua level data (level 1 sd 5)		 
		*/
		
		
		/*echo "Periode system : $per_sys";
		echo "<br>Periode aktif  : $per_aktif";
		echo "<br>Periode proses : $per_pilih";
		echo "<br>Tutup Bulan    : $tutup";
		*/
		
		if ($per_pilih > $per_sys ) {
			echo "<script language=javascript>
				$('.panel-footer').html(\"<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true' name='tombol-x'>X</button><strong> <span class='glyphicon glyphicon-info-sign'></span> Perhatian, </strong> Periode proses yang dipilih tidak boleh lebih dari periode system ! </div>\");					
				</script>";
		} else {
			
			echo "<script> $('#nomor-proses').html('Proses : $no_proses / $tot_proses'); </script>";
			
			// Proses ke 1			
			// Proses Cari akun baru tahun yg diproses
			$nm_proses  = "Proses Akun Baru";
		
			$qry1="SELECT DISTINCT a.acc,a.nmp FROM ".$table_akun." AS a LEFT JOIN ".$table_glm." AS b ON a.acc=b.acc WHERE b.per='".$pil_tahun."'";
			$query1 = mysql_query($qry1, $conn) or die("Error Select New Account ".mysql_error());		
			$jml_rec=mysql_num_rows($query1);

			if ($jml_rec==0) { $jml_rec=1 ; }

			//echo "<script> alert(".$jml_rec."); </script>";
			
			$query2 = mysql_query($qry1, $conn) or die("Error Select New Account ".mysql_error());		
			
			$no=0;
			while ($data = mysql_fetch_array($query2)) {
				$no ++; 	
				$nilai=round(($no/$jml_rec)*100);
				
				$cAcc=$data[acc];
				
				echo "<script> proses_on('$nm_proses','$nilai%'); </script>";
				
				$qry="INSERT INTO ".$table_glm." (acc,per) VALUES ('".$cAcc."','".$pil_tahun."') ";				
				$query = mysql_query($qry, $conn) or die("Error Insert New Account ".mysql_error());					
			}
			if ($no==0) { echo "<script> proses_on('$nm_proses','100%'); </script>"; }
			
			// Proses ke 2
			// Proses Saldo Awal 
			$no_proses ++;
			$nm_proses="Proses Saldo Awal";
			
			echo "<script> $('#nomor-proses').html('Proses : $no_proses / $tot_proses'); </script>";
			
			$query1 = mysql_query("SELECT * FROM ".$table_akun, $conn) or die("Error Select Show Acc ".mysql_error());		
			$rec_tot = mysql_query("SELECT * FROM ".$table_akun, $conn) or die("Error Select Show Acc ".mysql_error());		
			$jml_rec=mysql_num_rows($rec_tot);
				
			$no=0;
			while ($data = mysql_fetch_array($query1)) {
				$no ++; 	
				$nilai=round(($no/$jml_rec)*100);
				$nil_progress=$nilai+"%";
				
				$cAcc=$data[acc];
				$cTh=$data[th_sld];
				$cBl=$data[bl_sld];
				$cJml=$data[jml_sld];
				
				echo "<script> proses_on('$nm_proses','$nilai%'); </script>";
				
				if ( !empty($cTh) && !empty($cBl) && !empty($cJml) ) {
					$qry="UPDATE ".$table_glm." SET saw".$pil_bulan."=".$cJml." WHERE acc='".$cAcc."' AND per='".$pil_tahun."'  ";				
					$query = mysql_query($qry, $conn) or die("Error Update Saldo Awal ".mysql_error());		
				}			
			}	
			
			// Proses ke 3
			// Proses Data Transaksi
			
			$no_proses ++;
			$nm_proses="Proses Data Transaksi";
			
			echo "<script> $('#nomor-proses').html('Proses : $no_proses / $tot_proses'); </script>";
			
			$mKas1='kasd'+$pil_bulan;
			$mKas2='kasc'+$pil_bulan;
			$mBnk1='bnkd'+$pil_bulan;
			$mBnk2='bnkc'+$pil_bulan;
			$mMem1='memd'+$pil_bulan;
			$mMem2='memc'+$pil_bulan;
			
			// reset nilai sebelum diisi dengan nilai dari transaksi
			$qry="UPDATE ".$table_glm." SET $mKas1=0, $mKas2=0, $mBnk1=0, $mBnk2=0, $mMem1=0, $mMem2=0 WHERE per='$pil_tahun' ";  
			echo "$qry";
			$query1 = mysql_query($qry, $conn) or die("Error Update All Transaction ".mysql_error());		
			
			$query2 = mysql_query("SELECT * FROM ".$table_glm." WHERE per='$pil_tahun' ORDER BY acc", $conn) or die("Error Select Periode GLM ".mysql_error());		
			$rec_tot = mysql_query("SELECT * FROM ".$table_glm." WHERE per='$pil_tahun' ORDER BY acc", $conn) or die("Error Select Periode GLM ".mysql_error());		
			$jml_rec=mysql_num_rows($rec_tot);
				
			$no=0;
			while ($data = mysql_fetch_array($query2)) {
				$no ++; 	
				$nilai=round(($no/$jml_rec)*100);
				$nil_progress=$nilai+"%";
				
				$cAcc=$data[acc];
				
				$cSaw=$data['saw$pil_bulan'];
				$cSak=$data['sak$pil_bulan'];
				
				/*
				$cKasD=$data['kasd$pil_bulan'];
				$cKasC=$data['kasc$pil_bulan'];
				$cBnkD=$data['bnkd$pil_bulan'];
				$cBnkC=$data['bnkc$pil_bulan'];
				$cMemD=$data['memd$pil_bulan'];
				$cMemC=$data['memc$pil_bulan'];
				*/

				$xSaw=$cSaw;
				$xSak=$cSak;
				
				echo "<script> proses_on('$nm_proses','$nilai%'); </script>";

				// Cari Akun di tabel transaksi
				$query3 = mysql_query("SELECT acc,deb,krd FROM ".$table_glt." WHERE LEFT(tgp,7)='$pil_tahun-$pil_bulan' AND acc='$cAcc' ORDER BY acc", $conn) or die("Error Select Acc GLT ".mysql_error());		
				$query4 = mysql_query("SELECT acc,tnd FROM ".$table_akun." WHERE acc='$cAcc' ", $conn) or die("Error Select Acc ".mysql_error());		
				$dt4 = mysql_fetch_array($query4);
				
				// Jenis Akun (D/K)
				$cDK=$dt4[tnd];
				
				$xKasD=0;
				$xKasC=0;
				$xBnkD=0;
				$xBnkC=0;
				$xMemD=0;
				$xMemC=0;
				
				while ($dt3 = mysql_fetch_array($query3)) {
					
					$cType=$dt3[typ];
					$deb=$dt3[deb];
					$krd=$dt3[krd];
					
					switch ($cType) {
						case 'K':
							$xKasD=$xKasD+$deb;
							$xKasC=$xKasC+$krd;
							break;
						case 'B':
							$xBnkD=$xBnkD+$deb;
							$xBnkC=$xBnkC+$krd;
							break;
						case 'M':
							$xMemD=$xMemD+$deb;
							$xMemC=$xMemC+$krd;			
							break;
					}	
				}
				// -------------------SAMPAI DISINI DULU PER 26 MEI 2015 ---------------------------------
				$cBulan=intval($pil_bulan);
				$tBulan=intval($pil_bulan);
				
				while ($cBulan <= $xBulan) {
					if ($tBulan==$cBulan) {
						// Update GLM
						$cKasD='kasd'.$pil_bulan;
						$cKasC='kasc'.$pil_bulan;
						$cBnkD='bnkd'.$pil_bulan;
						$cBnkC='bnkc'.$pil_bulan;
						$cMemD='memd'.$pil_bulan;
						$cMemC='memc'.$pil_bulan;
						$cSak='sak'.$pil_bulan;

						if ($cDK=='D') {
							$qry="UPDATE ".$table_glm." SET $cKasD=$xKasD, $cKasC=$xKasC, $cBnkD=$xBnkD,$cBnkC=$xBnkC, $cKasD=$xKasD, $cMemC=$xMemC,$cSak=($xSaw+$xKasD-$xKasC+$xBnkD-$xBnkC+$xMemD-$xMemC) WHERE acc='$cAcc' AND per='$pil_tahun' ";  
						} else {
							$qry="UPDATE ".$table_glm." SET $cKasD=$xKasD, $cKasC=$xKasC, $cBnkD=$xBnkD,$cBnkC=$xBnkC, $cKasD=$xKasD, $cMemC=$xMemC,$xSak=($xSaw-$xKasD+$xKasC-$xBnkD+$xBnkC-$xMemD+$xMemC) WHERE acc='$cAcc' AND per='$pil_tahun' ";  
						}
						$upd_glm = mysql_query($qry, $conn) or die("Error $qry ".mysql_error());		
					} 
					else 
					{
						$vBulan=substr('00'.$cBulan,-2);
						
						$cSaw='saw'.$vBulan ;
						
						$qry="UPDATE ".$table_glm." SET $cSaw=$xSak WHERE acc='$cAcc' AND per='$pil_tahun' ";  
						$upd_glm = mysql_query($qry, $conn) or die("Error $qry ".mysql_error());		
						
						if ($cDK=='D') {
							$xSak=$xSak+KasD&vBulan-KasC&vBulan+BnkD&vBulan-BnkC&vBulan+MemD&vBulan-MemC&vBulan;
						}
						else {
							$xSak=$xSak-KasD&vBulan+KasC&vBulan-BnkD&vBulan+BnkC&vBulan-MemD&vBulan+MemC&vBulan;
						}
						$qry="UPDATE ".$table_glm." SET $cKasD=$xKasD, $cKasC=$xKasC, $cBnkD=$xBnkD,$cBnkC=$xBnkC, $cKasD=$xKasD, $cMemC=$xMemC,$cSak=($xSaw+$xKasD-$xKasC+$xBnkD-$xBnkC+$xMemD-$xMemC) WHERE acc='$cAcc' AND per='$pil_tahun' ";  
						
						//REPLACE $Saw&vBulan	WITH $xSak;
						//REPLACE $Sawp&vBulan	WITH $xSakP;
						
						IF ($cDK=='D') {
							$xSak=$xSak+KasD&vBulan-KasC&vBulan+BnkD&vBulan-BnkC&vBulan+MemD&vBulan-MemC&vBulan;
							$xSakP=$xSakP + nil_pjk1&vBulan - nil_pjk2&vBulan;
						} ELSE {
							$xSak=$xSak-KasD&vBulan+KasC&vBulan-BnkD&vBulan+BnkC&vBulan-MemD&vBulan+MemC&vBulan;
							$xSakP=$xSakP - nil_pjk1&vBulan + nil_pjk2&vBulan;
						} 	
						
						//REPLACE Sak&vBulan	WITH xSak	
						//REPLACE SakP&vBulan	WITH xSakP*/
					}
					$cBulan ++;
				}
				
				/*
				SELECT caseglm
				cBulan=VAL(nBulan)
				tBulan=VAL(nBulan)
				DO WHILE cBulan <= VAL(xBulan) && update saldo awal dan saldo akhir bulan berikutnya		
					SELECT caseglm		
					IF tBulan=cBulan
						REPLACE KasD&nBulan	WITH xKasD
						REPLACE KasC&nBulan	WITH xKasC
						REPLACE BnkD&nBulan	WITH xBnkD
						REPLACE BnkC&nBulan	WITH xBnkC
						REPLACE MemD&nBulan	WITH xMemD
						REPLACE MemC&nBulan	WITH xMemC

						REPLACE nil_pjk1&nBulan WITH xPjkD	&& Koreksi Negatif
						REPLACE nil_pjk2&nBulan WITH xPjkK	&& Koreksi Positif

						IF cDK='D'
							REPLACE Sak&nBulan	WITH xSaw+xKasD-xKasC+xBnkD-xBnkC+xMemD-xMemC
							xSak=xSaw+xKasD-xKasC+xBnkD-xBnkC+xMemD-xMemC

							REPLACE SakP&nBulan	WITH xSawP+xPjkD-xPjkK
							xSakP=xSawP+xPjkD-xPjkK
						ELSE 
							REPLACE Sak&nBulan	WITH xSaw-xKasD+xKasC-xBnkD+xBnkC-xMemD+xMemC
							xSak=xSaw-xKasD+xKasC-xBnkD+xBnkC-xMemD+xMemC

							REPLACE SakP&nBulan	WITH xSawP-xPjkD+xPjkK
							xSakP=xSawP-xPjkD+xPjkK
						ENDIF 	

					ELSE
						vBulan=RIGHT('00'+ALLTRIM(STR(cBulan)),2)
						REPLACE Saw&vBulan	WITH xSak
						REPLACE Sawp&vBulan	WITH xSakP
						
						IF cDK='D'
							xSak=xSak+KasD&vBulan-KasC&vBulan+BnkD&vBulan-BnkC&vBulan+MemD&vBulan-MemC&vBulan
							xSakP=xSakP + nil_pjk1&vBulan - nil_pjk2&vBulan
						ELSE
							xSak=xSak-KasD&vBulan+KasC&vBulan-BnkD&vBulan+BnkC&vBulan-MemD&vBulan+MemC&vBulan
							xSakP=xSakP - nil_pjk1&vBulan + nil_pjk2&vBulan
						ENDIF 	
						
						REPLACE Sak&vBulan	WITH xSak	
						REPLACE SakP&vBulan	WITH xSakP
						
					ENDIF 		
					cBulan=cBulan+1
				ENDDO 
				*/
			
				$qry="UPDATE ".$table_glm." SET saw".$pil_bulan."=".$cJml." WHERE acc='".$cAcc."' AND per='".$pil_tahun."'  ";				
				$query = mysql_query($qry, $conn) or die("Error Update Saldo Awal ".mysql_error());	
			}
			
			// Proses ke 4
			// Proses Saldo Akhir
			
			$no_proses ++;
			$nm_proses="Proses Saldo Akhir";
			
			echo "<script> $('#nomor-proses').html('Proses : $no_proses / $tot_proses'); </script>";
			
			$query1 = mysql_query("SELECT * FROM ".$table_akun, $conn) or die("Error Select Show Acc ".mysql_error());		
			$rec_tot = mysql_query("SELECT * FROM ".$table_akun, $conn) or die("Error Select Show Acc ".mysql_error());		
			$jml_rec=mysql_num_rows($rec_tot);
				
			$no=0;
			while ($data = mysql_fetch_array($query1)) {
				$no ++; 	
				$nilai=round(($no/$jml_rec)*100);
				$nil_progress=$nilai+"%";
				
				$cAcc=$data[acc];
				$cTh=$data[th_sld];
				$cBl=$data[bl_sld];
				$cJml=$data[jml_sld];
				
				echo "<script> proses_on('$nm_proses','$nilai%'); </script>";
				
				if ( !empty($cTh) && !empty($cBl) && !empty($cJml) ) {
					$qry="UPDATE ".$table_glm." SET saw".$pil_bulan."=".$cJml." WHERE acc='".$cAcc."' AND per='".$pil_tahun."'  ";				
					$query = mysql_query($qry, $conn) or die("Error Update Saldo Awal ".mysql_error());		
				}
			}

			// Proses ke 5
			// Proses Posting Nilai Saldo Level 1 sd 5
			
			$no_proses ++;
			$nm_proses="Posting Saldo Level 1 sd 5";
			
			echo "<script> $('#nomor-proses').html('Proses : $no_proses / $tot_proses'); </script>";
			
			$query1 = mysql_query("SELECT * FROM ".$table_akun, $conn) or die("Error Select Show Acc ".mysql_error());		
			$rec_tot = mysql_query("SELECT * FROM ".$table_akun, $conn) or die("Error Select Show Acc ".mysql_error());		
			$jml_rec=mysql_num_rows($rec_tot);
				
			$no=0;
			while ($data = mysql_fetch_array($query1)) {
				$no ++; 	
				$nilai=round(($no/$jml_rec)*100);
				$nil_progress=$nilai+"%";
				
				$cAcc=$data[acc];
				$cTh=$data[th_sld];
				$cBl=$data[bl_sld];
				$cJml=$data[jml_sld];
				
				echo "<script> proses_on('$nm_proses','$nilai%'); </script>";
				
				if ( !empty($cTh) && !empty($cBl) && !empty($cJml) ) {
					$qry="UPDATE ".$table_glm." SET saw".$pil_bulan."=".$cJml." WHERE acc='".$cAcc."' AND per='".$pil_tahun."'  ";				
					$query = mysql_query($qry, $conn) or die("Error Update Saldo Awal ".mysql_error());		
				}
			}

			echo "<script> $('.progress').removeClass('active'); </script>";
			echo "<script> alert('Proses Tutup Bulan Selesai'); </script>";							
		}
	}
}
else { header("location:/glt/no_akses.htm"); }
?>
