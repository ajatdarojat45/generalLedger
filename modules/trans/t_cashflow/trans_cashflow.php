<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {	
	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php";
	
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	
	include "../inc/func_modul.php";
	include "../inc/inc_aed.php";
	
	$jenis_trans="Cashflow";
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tbl_mst_sup="mst_sup_".$company_id ;
	$tbl_mst_sis="mst_sistem" ;
	
	$tbl_trx_temp="trx_tmp_caseglt_".$company_id ;
	
	$tbl_trx="trx_caseglt_".$company_id ;

	// table data sistem asli (tidak bisa diubah oleh user akunting)
	$tbl_trx1="trx_case_tam_".$company_id ;

	// table data final jika ada perubahan isi jurnal oleh user akunting (posting ke trx_caseglt juga dari table ini)
	$tbl_trx2="trx_case_fin_".$company_id ;

	$tbl_trx3="trx_caseglt_".$company_id ;
	 
	$tgl_trans=substr($aktif_tgl,0,7);
	
	// posting / kirim data ke table caseglt
	if ($_POST[btn_ok]=="posting") {
		/*echo "<script> alert('go post'); </script>";*/
		
		for ($m=1; $m < $_POST[jml_rec]; $m++) {
			
			$var="post_".$m;
			
			// ambil nomor bukti yg akan diproses dari checkbox
			
			if (isset($_POST[$var])) { $nbk=$_POST[$var]; } else { $nbk="";}
			
			if (!empty($nbk)) {
				// proses posting data ke caseglt
				/* echo "<script> alert('$nbk'); </script>";*/
				$que_del = mysql_query("DELETE FROM $tbl_trx3 WHERE nbk='$nbk' ", $conn) or die("Error Query Delete Caseglt");				
				$qry="";
				$qry=$qry."INSERT INTO $tbl_trx3 (mcom_id,trx_status,nbk,tgp,seq,typ,acc,deb,krd,ket,flg,kd_sup,pemakai,tgl_input) " ;
				$qry=$qry."(SELECT mcom_id,trx_status,nbk,tgp,seq,typ,acc,deb,krd,ket,'1',kd_sup,'$user_id',now() FROM $tbl_trx2 WHERE nbk='$nbk' )" ;
				$que_ins = mysql_query($qry, $conn) or die("Error Query Insert Caseglt");					
				$que_upd = mysql_query("UPDATE $tbl_trx2 SET flg='1' WHERE nbk='$nbk' ", $conn) or die("Error Query Update flg Casefin");				
				$que_upd = mysql_query("UPDATE $tbl_trx1 SET flg='1' WHERE nbk='$nbk' ", $conn) or die("Error Query Update flg Casefin");				
				
			}
		}
		
	}
	
	// batal posting/hapus data yg sudah ada di table caseglt
	if ($_POST[btn_ok]=="unposting") {
		for ($m=1; $m < $_POST[jml_rec]; $m++) {
			
			$var="unpost_".$m;
			
			// ambil nomor bukti yg akan diproses dari checkbox
			
			if (isset($_POST[$var])) { $nbk=$_POST[$var]; } else { $nbk="";}
			
			if (!empty($nbk)) {
				// proses posting data ke caseglt
				/* echo "<script> alert('$nbk'); </script>";*/
                
				
				$que_del = mysql_query("DELETE FROM $tbl_trx3 WHERE nbk='$nbk' ", $conn) or die("Error Query Delete Caseglt");				
				$que_upd = mysql_query("UPDATE $tbl_trx2 SET flg='0' WHERE nbk='$nbk' ", $conn) or die("Error Query Update flg Casefin");
				$que_upd = mysql_query("UPDATE $tbl_trx1 SET flg='0' WHERE nbk='$nbk' ", $conn) or die("Error Query Update flg Casefin");				
				
			}
		}
	}

?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>GL TEMPO</title>

<!-- Bootstrap core CSS -->
<link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet">
<link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet">
<link href="../../style/style_utama.css" rel="stylesheet">
<link href="../../css/black-tie/jquery-ui-1.10.4.custom.css" rel="stylesheet">

</HEAD>
 <!--onLoad="tombol_reset();"-->
<BODY onLoad="tombol_reset();">
	<input type="hidden" id="id-menu" value="<? echo $_GET[id_menu]; ?>">			
	<input type="hidden" id="id-add" value="<? echo $tmbl_add; ?>">	
	<input type="hidden" id="id-edit" value="<? echo $tmbl_edit; ?>">	
	<input type="hidden" id="id-delete" value="<? echo $tmbl_del; ?>">	

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
				?>
				</li>
			</ul>
		</div>
	</div>
	<form name="frmcf" id="frm_cf" method="post">
    
<?
	$jenis_trans="Cashflow";
	$tbl_view_trx="trans_kas".$company_id ; 
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tbl_mst_sup="mst_sup_".$company_id ;
	$tbl_mst_sis="mst_sistem" ;
	
	$tbl_trx_temp="trx_tmp_caseglt_".$company_id ;
	$tbl_trx="trx_caseglt_".$company_id ;
	$tbl_trx1="trx_case_tam_".$company_id ;
	$tbl_trx2="trx_case_fin_".$company_id ;
 
	$tgl_trans=substr($aktif_tgl,0,7);
	
	$jumdata = "";
	
	$nbk="";
	
	$tgp=$_POST[cdate];;

	$kd_sup="";
	$nm_sup="";
	
	//transform
	$txtdebet="0.00";				
	$txtkredit="0.00";						
	
	//murni
	$totdebet="0";				
	$totkredit="0";						
	
	$tipe=substr($nbk,1,1);
	$data_cf="";
	
	$tgl_trans=substr($aktif_tgl,0,7);
	
	if(isset($_GET[kd_sis])) {
		$kd_sis=$_GET[kd_sis];
		$nm_sis=$_GET[nm_sis];		
	} else { 
		$kd_sis="";
		$nm_sis="";
	}	

	if(isset($_GET[jenis])) {
		$jenis=$_GET[jenis];
		$nm_jenis=$_GET[nm_jenis];
	} else { 
		$jenis="A";
		$nm_jenis="Semua";
	}	
	
?>

<div class="panel panel-primary">
	<div class="panel-heading">    
		<span class='glyphicon glyphicon-inbox'></span> JURNAL POSTING OTOMATIS SISTEM <span class="badge" id="badge_cf"></span>
	</div>
		<!-- CONTENT FORM -->
	<div class="panel-body">			
		<!-- AREA HEADER TRANSAKSI -->
	  <div class="row">
		  <div class="col-xs-12">
			  <div class="panel panel-primary alert-success">
				  <!-- AREA TOTAL JUMLAH -->
				<div class="panel-body">
				  <div class="row">
					  <div class="col-xs-2">
						<div class="input-group">
						  <div class="input-group-btn">
							<button id="btn_sistem" type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" data-placement='bottom' title='Sistem Jurnal'>Sistem <span class="caret"></span></button>
							<ul class="dropdown-menu">
							  <?
								$qry = "SELECT * FROM $tbl_mst_sis WHERE mcom_id='$company_id' AND sis_status='1' ORDER BY kd_sis";		
								$que_view = mysql_query($qry, $conn) or die("Error Query Select Sistem");
								
								while ($data = mysql_fetch_array($que_view)) {
									echo "<li><a href=\"?id_menu=$_GET[id_menu]&kd_sis=$data[kd_sis]&nm_sis=$data[nm_sis]&jenis=$jenis&nm_jenis=$nm_jenis\">[$data[kd_sis]]-$data[nm_sis]</a></li>";
								}
								?>
							</ul>
						  </div><!-- /btn-group -->
						  <input type="text" class="form-control text-right" size="40" value="<? echo $nm_sis; ?>" readonly>
						</div><!-- /input-group -->
					  </div>
					  <div class="col-xs-2">
						<div class="input-group">
						  <div class="input-group-btn">
							<button id="btn_jenis" type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" data-toggle="dropdown" data-placement='bottom' title='Jenis Transaksi'>Jenis <span class="caret"></span></button>
							<ul class="dropdown-menu">
							  <?
							echo "<li><a href=\"?id_menu=$_GET[id_menu]&kd_sis=$kd_sis&nm_sis=$nm_sis&jenis=A&nm_jenis=Semua\">[A]-Semua</a></li>";
							echo "<li><a href=\"?id_menu=$_GET[id_menu]&kd_sis=$kd_sis&nm_sis=$nm_sis&jenis=K&nm_jenis=Kas\">[K]-Kas</a></li>";
							echo "<li><a href=\"?id_menu=$_GET[id_menu]&kd_sis=$kd_sis&nm_sis=$nm_sis&jenis=B&nm_jenis=Bank\">[B]-Bank</a></li>";
							echo "<li><a href=\"?id_menu=$_GET[id_menu]&kd_sis=$kd_sis&nm_sis=$nm_sis&jenis=S&nm_jenis=BS\">[S]-BS</a></li>";
							echo "<li><a href=\"?id_menu=$_GET[id_menu]&kd_sis=$kd_sis&nm_sis=$nm_sis&jenis=M&nm_jenis=Memorial\">[M]-Memorial</a></li>";
								?>
							</ul>
						  </div><!-- /btn-group -->
						  <input type="text" class="form-control text-right" size="40" value="<? echo $nm_jenis; ?>" readonly>
						</div><!-- /input-group -->
					  </div>
					  <div class="col-xs-8 hidden-print text-right">
							<button id="btn_posting" type="button" class="btn btn-success" onClick="klik_posting()" data-toggle="tooltip" data-placement="bottom" title="Posting" accesskey="3"><span class='glyphicon glyphicon-check'></span> POSTING</button>
							<button id="btn_unposting" type="button" class="btn btn-success" onClick="klik_unposting()" data-toggle="tooltip" data-placement="bottom" title="Unposting" accesskey="3"><span class='glyphicon glyphicon-unchecked'></span> CANCEL POSTING</button>
							<button id="btn_process" name="btn_go_post" type="button" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Process" accesskey="4" disabled="disabled" value="" data-loading="Wait..." onClick="klik_proses()">PROCESS</button>
							<button name="btn_cancel" id="btn_cancel" type="button" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Cancel" accesskey="5" disabled="disabled" value="" onClick="klik_cancel_post()">CANCEL</button>
					  </div>
				  </div>
					<div id="area_proses" class="alert-warning">                        
                    </div>
				  <br>
				  <div class="row">
					  <div class="col-xs-13 col-md-12">
						<div id="table_detail"> 
						  <table class="table table-bordered table-hover table-condensed" align="center">
							<th width="2%" class="info">#</th>
							<th width="2%" class="info">Jenis</th>
							<th width="1%" class="info">Sistem</th>
							<th width="7%" class="info">Tgl.Bukti</th>
							<th width="10%" class="info">Nomor Bukti</th>
							<th width="25%" class="info">Keterangan</th>
							<th width="8%" class="info text-right">Total Debet</th>
							<th width="8%" class="info text-right">Total Kredit</th>
							<th width="2%" class="info text-center"><a href="#" onClick="klik_all()">Posting</a></th>
							<th colspan="2" width="2%" class="info text-center">Action</th>
							<?
							if ($kd_sis<>"") {
								if ($kd_sis==0) {
									// all sistem									
									if ($jenis=="A") {
										$qry = "SELECT typ,tgp,nbk,max(ket) as ket,sum(deb) as deb,sum(krd) as krd,kd_sis,flg FROM $tbl_trx2 WHERE mcom_id='$company_id' AND trx_status='1' AND left(tgp,7)='$tgl_trans' GROUP BY nbk ORDER BY tgp,nbk";
									} else {
										$qry = "SELECT typ,tgp,nbk,max(ket) as ket,sum(deb) as deb,sum(krd) as krd,kd_sis,flg FROM $tbl_trx2 WHERE mcom_id='$company_id' AND trx_status='1' AND left(tgp,7)='$tgl_trans' AND typ='$jenis' GROUP BY nbk ORDER BY tgp,nbk";
									}									
								} else {
									if ($jenis=="A") {
										$qry = "SELECT typ,tgp,nbk,max(ket) as ket,sum(deb) as deb,sum(krd) as krd,kd_sis,flg FROM $tbl_trx2 WHERE mcom_id='$company_id' AND trx_status='1' AND kd_sis='$kd_sis' AND left(tgp,7)='$tgl_trans' GROUP BY nbk ORDER BY tgp,nbk";					
										} else {
										$qry = "SELECT typ,tgp,nbk,max(ket) as ket,sum(deb) as deb,sum(krd) as krd,kd_sis,flg FROM $tbl_trx2 WHERE mcom_id='$company_id' AND trx_status='1' AND kd_sis='$kd_sis' AND left(tgp,7)='$tgl_trans' AND typ='$jenis'  GROUP BY nbk ORDER BY tgp,nbk";	
									}					
								}								

								$que_view = mysql_query($qry, $conn) or die("Error Query Select Data Sistem ");
								$no=1;
								while ($data = mysql_fetch_array($que_view)) {
									$tgp=ubah_tgl($data[tgp]);
									
									if ($data[flg]==0) {  $posting="-" ; } else { $posting="&radic;"; }
									
									$debet=tampil_uang($data[deb],true);	
									$kredit=tampil_uang($data[krd],true);	
									
									$tdebet=$tdebet+$data[deb];
									$tkredit=$tkredit+$data[krd];
									if ($debet<>$kredit) {
										$klas1='text-danger';
									} else {
										$klas1='text-primary';
									}
									echo "<tr class='$klas1'>
									<td align='right'>$no.</td>
									<td align='center'>$data[typ]</td>
									<td align='center'>$data[kd_sis]</td>
									<td>$tgp</td>
									<td><div id=\"nbk_$no\">$data[nbk]</div></td>
									<td>$data[ket]</td>
									<td align='right'>$debet</td>
									<td align='right'>$kredit</td>
									<td class='text-danger' align='center'><div id=\"area-posting-$no\">$posting</div></td>
									<td align='center'><a href='#' onClick=\"edit_detail('$data[nbk]')\" data-toggle='tooltip' data-placement='bottom' title='Edit Detail'><span class='glyphicon glyphicon-edit'></span></a></td>
									<td><button class='btn btn-success btn-xs' type='button' id='btn_view$no' onClick=\"view_detail('$data[nbk]','$tgp')\" data-toggle='tooltip' data-placement='bottom' title='View Detail'>view</button></td>
									</tr>";
									$no ++;
								}
								echo "<input type='hidden' id=\"jml_rec\" name='jml_rec' value='$no'>";
								$un_debet=0;
								$un_kredit=0;
								
								if ($tdebet > $tkredit) {
									$un_kredit=$tdebet - $tkredit ; }
								else { $un_debet=$tkredit - $tdebet ; }
								
								$un_debet=tampil_uang($un_debet,true);	
								$un_kredit=tampil_uang($un_kredit,true);								

								$tdebet=tampil_uang($tdebet,true);	
								$tkredit=tampil_uang($tkredit,true);
								
								// Jika total tidak balance warna teks merah
								if ($tdebet<>$tkredit) {$klas="class='text-danger'"; } else { $klas="class='text-primary'"; }
								
								echo "<tr $klas>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td align='right'>TOTAL</td>
										<td align='right'>$tdebet</td>
										<td align='right'>$tkredit</td>
										<td align='center'></td>
										<td align='center'></td>
										<td align='center'></td>
										</tr>";
										
								if ($un_debet<>$un_kredit){		
								echo "<tr class='text-danger'>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td align='right'>Unbalance</td>
										<td align='right'>$un_debet</td>
										<td align='right'>$un_kredit</td>
										<td align='center'></td>
										<td align='center'></td>
										<td align='center'></td>
										</tr>";
								}
							}
							?>
						  </table>
						</div>
					  </div>
				  </div> <!-- end row -->
				</div>
			  </div>		
		  </div>
	   </div>
	   <!-- AREA UNTUK INPUT DETAIL JURNAL -->
		
		<div id="area_cari_data">  <!-- untuk mencari data akun / supplier -->
		</div>
		
		<div id="area_input_detail">
		</div>		
   </div>
   </form>
</div>


<?

	//print_r ($_POST);
	//print_r ($_GET);

?>

<div id="dialog-modul" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
  		<div class="modal-content">
			<form id="frmmodul" name="frm_modul" method="post">
			<input type="hidden" name="id_menu" value="<? echo $_GET[id_menu]; ?>">
			<div class="modal-header  alert-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" name='tombol-x'>X</button>
				<div class="modal-title" id="myModalLabel1">FORM NAME</div>
			</div>			
			<div class="modal-body" id="mybody-dialog">
			</div>
			<div class="modal-footer  alert-warning" id="myfooter-dialog">
            </div>
		    </form>
		</div>
	</div>
</div>

<div id="dialog-konfirmasi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content alert-warning">
		<form name="frmkonfirmasi" id="frmkonfirmasi" method="post">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
				<h3 class="modal-title" id="myModalLabel2">KONFIRMASI</h3>
			</div>			
			<div class="modal-body" id="mybody-konfirmasi"></div>
			<div class="modal-footer" id="myfooter-konfirmasi"><button type='button' class="btn-danger"  data-dismiss="modal">OK</button>
            </div>
		</form>
		</div>
	</div>
</div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<![if lt IE 9]>
	  <script src="../../bootstrap/html5shiv.js"></script>
	  <script src="../../bootstrap/respond.min.js"></script>
	<![endif]>
	
	<script src="../../js/jquery-1.11.0.min.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>
	<script src="aksi_cashflow.js"></script>
    <script src="../../js/jquery-ui-1.10.4.custom.js"></script>
    <script src="../../js/jquery.inputmask.js"></script>
	<script type="text/javascript">

	$(document).ready(function(){
		$("#tgl_bukti").datepicker({dateFormat: "dd-mm-yy"});
		//$("#d_nm_jml").inputmask("999,999,999,999,999.99",{groupSeparator: ",", autoGroup: true});
	
		//$("#tgl_bukti").datepicker();
		
		
		// jika mau dipakai inputmask sudah ok nih
		//$("#no_bukti").inputmask("aa-***/**/**");
		//$("#d_nm_jml").inputmask("decimal", { radixPoint: "," });
	});

	</script>
		
</BODY>
</HTML>
<!-- session -->


<?
	/*echo "<script> alert('POSTED'); </script>";
	*/
		if ($_POST[cari_kas]) {
		
		echo "<script> $('#tampil_badge').html('".$_POST[cari_kas]."'); </script>"; 
		
		}
		if ($_POST[btn_save]) {
		
		echo "<script> $('#tampil_badge').html('".$_POST[txt_nobukti]."'); </script>"; 
		
		}	
			
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