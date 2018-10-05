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
	
	$tbl_trx_temp="trx_tmp_caseglt_".$company_id ;
	$tbl_trx="trx_casepjk_".$company_id ;
	 
	$tgl_trans=substr($aktif_tgl,0,7);
	
	if (isset($_POST[btn_save])) {
		$nbk=$_POST[txt_nobukti];			
		
		$nil_d=0; 
		$nil_k=0; 					
		
		$tgp=$_POST[txt_tglbukti];

		// convert tgp ke yyyy-mm-dd
		$tgl=substr($tgp,-4).'-'.substr($tgp,3,2).'-'.substr($tgp,0,2);			

		$tgl_trans=substr($aktif_tgl,0,7);
		$tgl_input=substr($tgl,0,7);
		
		if ($tgl_trans<>$tgl_input) {
			$isi_alert=3;
			
		} else if (empty($_POST[txt_no_akun]) || empty($_POST[txt_nm_akun])) {
			$isi_alert=4;			
		} else { 
			if ($nbk=="AUTO") {
				// ADD NEW JURNAL
				
				// Cek DK akun yg dipilih
				$qry_tmp = mysql_query("SELECT acc,tnd FROM $tbl_mst_akun WHERE acc='$_POST[txt_no_akun]'", $conn) or die("Error Select Akun ".mysql_error());					
				$dt_dk = mysql_fetch_array($qry_tmp);
				
				$nil_d=0; 
				$nil_k=0; 					
				
				if ($dt_dk[tnd]=="D") {
					// Koreksi akun beban dengan d_k=="D"
					if ($_POST[koreksi_dk]=="positif") { $nil_k=$_POST[txt_jml]; } else {  $nil_d=$_POST[txt_jml];  } 		
				} else { 
					// Koreksi akun pendapatan dengan d_k=="K"
					if ($_POST[koreksi_dk]=="positif") { $nil_k=$_POST[txt_jml]; } else {  $nil_d=$_POST[txt_jml];   } 		
				}					

				$new_nbk="PJK".substr($tgp,-4).substr($tgp,3,2).substr($tgp,0,2);
				
				$qry_tmp = mysql_query("SELECT max(right(nbk,4)) as nbk_max, nbk as nbk FROM $tbl_trx WHERE  left(nbk,11)='$new_nbk' AND mcom_id='$company_id'", $conn) or die("Error Select Temp Caseglt ".mysql_error());
				
				$data = mysql_fetch_array($qry_tmp);
				
				if ($data[nbk_max]<>"") {
					//jika nomor urut sudah ada					
					$no=(int)$data[nbk_max];
					$nobaru=$no+1;
					$no_trans="0000".$nobaru;
					$nbk=$new_nbk.substr($no_trans,-4);
				} else {
					//nomor urut baru dari 1
					$nobaru=1;					
					$no_trans="0000".$nobaru;
					$nbk=$new_nbk.substr($no_trans,-4);					
				}				
				
				
				$qry = "INSERT INTO $tbl_trx (mcom_id,trx_status,nbk,tgp,typ,acc,deb,krd,ket,pemakai,tgl_input) VALUES ($company_id,'1','$nbk','$tgl','P', '$_POST[txt_no_akun]',$nil_d,$nil_k,'$_POST[txt_nm_ket]','$user_id',now())";
				
				//echo $qry;
				
				$qry_tmp = mysql_query($qry, $conn) or die("Error Add New Jurnal".mysql_error());	
										
				$isi_alert=1;	
				
			} else {
				// Edit data trans
				
				if ($_POST[txt_dk]=="D") {
					// Koreksi akun beban dengan d_k=="D"
					if ($_POST[koreksi_dk]=="positif") { $nil_k=$_POST[txt_jml]; } else {  $nil_d=$_POST[txt_jml]; } 		
				} else { 
					// Koreksi akun pendapatan dengan d_k=="K"
					if ($_POST[koreksi_dk]=="positif") { $nil_k=$_POST[txt_jml]; } else {  $nil_d=$_POST[txt_jml]; } 		
				}	
								
				$qry = "UPDATE $tbl_trx SET tgp='$tgl', deb=$nil_d, krd=$nil_k, ket='$_POST[txt_nm_ket]', pemakai='$user_id', tgl_input=now() WHERE nbk='$nbk' ";
				
				//echo $qry;
				
				$qry_tmp = mysql_query($qry, $conn) or die("Error Update Jurnal ".mysql_error());	
			}
			$isi_alert=1;	
		}						
	}	
		
		
	if ($_POST[tombol_hapus]) {
		$qry_tmp = mysql_query("UPDATE $tbl_trx SET trx_status=0,pemakai='$user_id',tgl_input=now() WHERE nbk='$_POST[tombol_hapus]' AND mcom_id='$company_id'", $conn) or die("Error Select Temp Caseglt ".mysql_error());							
		$isi_alert=2;
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
	
<?
	$jenis_trans="Pajak";
	$tbl_mst_akun="mst_akun_".$company_id ;
	
	$tbl_trx_temp="trx_tmp_caseglt_".$company_id ;
	$tbl_trx="trx_casepjk_".$company_id ;
	 
	$tgl_trans=substr($aktif_tgl,0,7);
	
	$jumdata = "New";
	
	$tgp=$_POST[cdate];;

	//transform
	$txttotal="0.00";				
	
	//murni
	$total="0";
	$jumdata="";		
				
	$view_total="<input type='hidden' name=\"total\" id=\"t_total\" value=\"$total\"/> 
	<input type='hidden' name=\"total\" id=\"txt_total\" value=\"$txttotal\"/>";


?>

<form name="frmcf" id="frm_cf" method="post">

<div class="panel panel-primary">
	<div class="panel-heading">    		
		<div class="row">
			<div class="col-xs-4" >
			<p class="panel-title"><span class='glyphicon glyphicon-inbox'></span> JURNAL PAJAK <span class="badge alert-warning" id="tampil_badge"><? echo "$jumdata"; ?></span>
			</div>			
			<div class="col-xs-8 text-right" >
				<button id="btn_add" name="btn_add" type="button" class="btn btn-success" onClick="klik_add()" data-toggle="tooltip" data-placement="bottom" title="Add New" accesskey="a" ><span class="glyphicon glyphicon-plus"></span></button> 
				<button id="btn_save" name="btn_save" type="submit" class="btn btn-danger" accesskey="s" data-toggle="tooltip" data-placement="bottom" title="Save"><span class="glyphicon glyphicon-ok"></span></button>
				<button id="btn_cancel" name="btn_cancel" type="button" class="btn btn-success" onClick="klik_cancel()" accesskey="c" data-toggle="tooltip" data-placement="bottom" title="Cancel"><span class="glyphicon glyphicon-remove"></span></button>
				<!--<button id="btn_print" name="btn_print" type="button" class="btn btn-success" onClick="klik_print()" accesskey="p" data-toggle="tooltip" data-placement="bottom" title="Print"><span class="glyphicon glyphicon-print"></span></button>
				<button id="btn_find" name="btn_find" type="button" class="btn btn-success" onClick="klik_find()" accesskey="f" data-toggle="tooltip" data-placement="bottom" title="Find Transaction"><span class="glyphicon glyphicon-search"></span></button>-->
			</div>
		</div>
	</div>
	<!-- end heading -->

	<div id="area_cari_data">  <!-- untuk mencari data akun-->
	</div>		

	<div id="area_input_detail">
	</div>	
	
	<!-- start body -->	
	<div class="panel-body">			
	  	<div class="row">
		  	<div class="col-xs-12">
				<div id="table_detail"> 
				  <table class="table table-bordered table-hover table-condensed" align="center">
					<th width="2%" class="info">#</th>
					<th width="7%" class="info">Tgl.Bukti</th>
					<th width="10%" class="info">Nomor Bukti</th>
					<th width="10%" class="info">Kode Akun</th>
					<th width="20%" class="info">Nama Akun</th>
					<th width="20%" class="info">Keterangan</th>
					<th width="8%" class="info text-right">Jumlah</th>
					<th width="1%" class="info"> </th>
					<th colspan="2" width="2%" class="info text-center">Action</th>
					<?
			
					$qry_tmp = mysql_query("SELECT a.*,b.nmp as nmp,b.tnd as d_k FROM ".$tbl_trx." as a left join ".$tbl_mst_akun." as b on a.acc=b.acc WHERE left(a.tgp,7)='$tgl_trans' AND a.trx_status='1' ORDER BY nbk", $conn) or die("Error Select Temp Caseglt ".mysql_error());
					
					$no = 1;
					while ($data = mysql_fetch_array($qry_tmp)) {
						if ($tmbl_edit==2) {
							$jumlah=$data[deb]+$data[krd];
							
							// Koreksi akun beban
							// Akun dengan jenis akunnya D jika koreksi positif maka akan dikredit (mengurangi beban)
							
							// Koreksi akun pendapatan
							// Akun dengan jenis akunnya K jika koreksi positif maka akan dikredit (menambah pendapatan)

							$koreksi="[]";
							
							if ($data[d_k]=="D") {
								if ($data[krd]<>0) { $koreksi="[+]"; } else {  $koreksi="[-]";;  } 		
								
							} else { 
								// Koreksi akun pendapatan dengan d_k=="K"
								if ($data[krd]<>0) { $koreksi="[+]"; } else {  $koreksi="[-]";  } 		
							}
							
							if ($data[deb]==0) { $kor_dk="K" ;} else { $kor_dk="D" ;}

							$view_tmbl_edit="<button type='button' class='btn-link' name=\"btn_edit_detail\" id=\"edit_$data[seq]\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit data $data[seq]\" onclick=\"klik_edit('$data[nbk]','$data[tgp]','$data[acc]','$data[nmp]','$data[ket]','$data[deb]','$data[krd]','$jumlah','$data[d_k]','$kor_dk')\" ><span class=\"glyphicon glyphicon-edit\"/></span></button>";
							
						} else {
							$view_tmbl_edit="";
						}
						if ($tmbl_del==3) {
							$view_tmbl_del="<button type='button' class='btn-link' onclick=\"klik_delete('$data[nbk]')\" name=\"btn_delete_detail\" id=\"delete_$data[seq]\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete data $data[seq]\" ><span class=\"glyphicon glyphicon-trash\"/></span></button>";						
										
						} else {
							$view_tmbl_del="";
						}
						
						$jumlah=tampil_uang($data[deb]+$data[krd],true);
						$tgl=ubah_tgl(substr($data[tgp],0,10));  
						//echo $tgl; 
				
		
						echo "<tr class='text-primary'> 
							<td>$no.</td>
							<td >$tgl</td>
							<td>$data[nbk]</td>
							<td>$data[acc]</td>
							<td>$data[nmp]</td>
							<td>$data[ket]</td>
							<td class='text-right'>$jumlah</td>
							<td align='center'>$koreksi</td>
							<td>$view_tmbl_edit</td>
							<td>$view_tmbl_del</td>
							</tr>";
						$no ++;
					}
					?>
				  </table>
				</div>
		  	</div>
	   	</div>		
	</div>	
</div>
</form>

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
			<div class="modal-body">
			</div>
			<div class="modal-footer  alert-warning">
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
				<h4 class="modal-title" id="myModalLabel2">KONFIRMASI</h4>
			</div>			
			<div class="modal-body" id="mybody-konfirmasi"></div>
			<div class="modal-footer" id="myfooter-konfirmasi"><button type='button' class="btn-danger"  data-dismiss="modal">OK</button>
            </div>
		</form>
		</div>
	</div>
</div>

<div id="dialog-hapus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<form name="frmhapus" id="frmhapus" method="post">
		<div class="modal-content alert-warning">
			<input type="hidden" name="id_menu" value="<? echo $_GET[id_menu]; ?>">
			<div class="modal-header" id="myheader-hapus">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>				
			</div>			
			<div class="modal-body" id="mybody-hapus"></div>
			<div class="modal-footer" id="myfooter-hapus">
            </div>
		</div>
		</form>
	</div>
</div>

<div id="dialog-cari" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mydialog-cari" aria-hidden="true">
	<div class="modal-dialog modal-lg">
  		<div class="modal-content">
			<form id="frmcari" name="frm_cari" method="post">
			<input type="hidden" name="id_menu" value="<? echo $_GET[id_menu]; ?>">
			<div class="modal-header alert-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" name='tombol-x'>X</button>
				<div class="modal-title" id="mydialog-cari">FORM NAME</div>
			</div>			
			<div class="modal-body" id="mybody-cari">
			</div>
			<div class="modal-footer alert-warning" id="myfooter-cari">
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
	<script src="aksi_pajak.js"></script>
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

	if ($isi_alert==1) {
		echo "<script> $('#area_cari_data').html(\"<br><div class='container'><div class='alert alert-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button><b> Jurnal $nbk </b> berhasil disimpan</div></div>\"); </script>";			
	} else if ($isi_alert==2) {
		echo "<script> $('#area_cari_data').html(\"<br><div class='container'><div class='alert alert-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button><b> Jurnal $_POST[tombol_hapus] </b> berhasil dihapus</div></div>\"); </script>";				
	} else if ($isi_alert==3) {
		echo "<script> $('#area_cari_data').html(\"<br><div class='container'><div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button><b> Tanggal transaksi salah ! </b> </div></div>\"); </script>";				
	} else if ($isi_alert==4) {
		echo "<script> $('#area_cari_data').html(\"<br><div class='container'><div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button><b> Kode akun tidak boleh kosong</b></div></div>\"); </script>";				
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