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
	
	$jenis_trans="Memorial";
	$tbl_view_trx="trans_jm".$company_id ; 
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tbl_mst_sup="mst_sup_".$company_id ;
	
	$tbl_trx_temp="trx_tmp_caseglt_".$company_id ;
	$tbl_trx="trx_caseglt_".$company_id ;
	 
	$tgl_trans=substr($aktif_tgl,0,7);
	
	$teks_salah="";
	
	if (isset($_POST[btn_save])) {
		// tombol save 
		if (!empty($_POST[txt_nobukti])) {
			
			$qry_tmp = mysql_query("SELECT SUM(deb) as total_debet, SUM(krd) as total_kredit FROM ".$tbl_trx_temp." WHERE user_id='$user_id' AND trx_status='1' ", $conn) or die("Error Select Total Transaction ".mysql_error());
			$data_total=mysql_fetch_array($qry_tmp);
			
			if ($data_total[total_debet]<>$data_total[total_kredit]) { 
				
                $teks_salah="<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button> <b>TOTAL JUMLAH</b> tidak balance ! </div>";
                
			} else {
		
				//data lama dinonaktifkan
				$qry_tmp_del=mysql_query("UPDATE ".$tbl_trx." SET trx_status='0' WHERE nbk='$_POST[txt_nobukti]' AND mcom_id='$company_id'", $conn) or die("Error Delete Temp Caseglt ");
				
				//data lama dinonaktifkan
				$qry_tmp = mysql_query("SELECT * FROM ".$tbl_trx_temp." WHERE  nbk='$_POST[txt_nobukti]' AND mcom_id='$company_id' AND user_id='$user_id'", $conn) or die("Error Select Temp Caseglt ".mysql_error());		
				
				while ($data_temp = mysql_fetch_array($qry_tmp)){
					$typ=$data_temp[typ];
					$nbk=$data_temp[nbk];
					$seq=$data_temp[seq];
					$acc=$data_temp[acc];
					$deb=$data_temp[deb];
					$krd=$data_temp[krd];
					
					$ket=str_replace("'","\'",$data_temp[ket]);
					$ket=str_replace('"',"\'",$ket);
		
					//$ket=$data_temp[ket];
					
					$kd_sup=$data_temp[kd_sup];
					$flg=$data_temp[flg];
												
					$tgp=substr($_POST[txt_tglbukti],6,4).'-'.substr($_POST[txt_tglbukti],3,2).'-'.substr($_POST[txt_tglbukti],0,2);
					
					$qry ="INSERT INTO $tbl_trx (mcom_id, trx_status, nbk, tgp, seq, typ, acc, deb, krd, ket, flg, kd_sup, pemakai, tgl_input) VALUES ('$company_id', '1', '$nbk', '$tgp', '$seq', '$typ', '$acc', '$deb', '$krd', '$ket', '$flg', '$kd_sup', '$user_id', now()) ";		
					$qry_ins = mysql_query($qry, $conn) or die("Error Update Transction ".mysql_error());						
				}
			}
		}
	}

	if (isset($_POST[tombol_hapus])) {
		// tombol delete 
		/*echo "<script> alert('hapus nih...$_POST[tombol_hapus]'); </script>";*/ 
		
		$nbk=$_POST[tombol_hapus];
		
		if (!empty($nbk)) {
			$qry_tmp_del=mysql_query("UPDATE $tbl_trx SET trx_status=0 WHERE nbk='$nbk' AND mcom_id='$company_id'", $conn) or die("Error Delete Temp Caseglt "); 
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
	<form name="frmjm" id="frm_jm" method="post">
<?
	$jenis_trans="Memorial";
	$tbl_view_trx="trans_jm".$company_id ; 
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tbl_mst_sup="mst_sup_".$company_id ;
	
	$tbl_trx_temp="trx_tmp_caseglt_".$company_id ;
	$tbl_trx="trx_caseglt_".$company_id ;
	 
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
	
	$data_cf="";
	
	$qry_tmp_del=mysql_query("DELETE FROM ".$tbl_trx_temp." WHERE user_id='$user_id'", $conn) or die("Error Delete Temp Caseglt ");

	if (isset($_POST[cari_memorial])){
		$qry_tmp_ins = mysql_query("INSERT INTO ".$tbl_trx_temp." (SELECT * FROM ".$tbl_trx." WHERE nbk='$_POST[cari_memorial]' AND trx_status='1' AND mcom_id='$company_id')", $conn) or die("Error Insert Temp Caseglt ".mysql_error());			
		$qry_tmp_upd = mysql_query("UPDATE ".$tbl_trx_temp." SET user_id='$user_id' WHERE nbk='$_POST[cari_memorial]'", $conn) or die("Error Insert Temp Caseglt ".mysql_error());					
		
		$qry_tmp = mysql_query("SELECT nbk,tgp,flg FROM ".$tbl_trx_temp."  WHERE user_id='$user_id'", $conn) or die("Error Select Temp Caseglt ".mysql_error());
		$data_top = mysql_fetch_array($qry_tmp);
		
		$nbk=$data_top[nbk];
		$tgp=$data_top[tgp];
		$flg=$data_top[flg];
		
		if ($flg==1) {
			$data_cf="From Cashflow";
		}

		$qry_tmp = mysql_query("SELECT SUM(deb) as total_debet, SUM(krd) as total_kredit FROM ".$tbl_trx_temp." WHERE user_id='$user_id' AND trx_status='1' ", $conn) or die("Error Select Total Transaction ".mysql_error());
		$data_total=mysql_fetch_array($qry_tmp);
	

		//transform
		$txtdebet=tampil_uang($data_total[total_debet],true);				
		$txtkredit=tampil_uang($data_total[total_kredit],true);				
		
		//murni
		$totdebet=$data_total[total_debet];
		$totkredit=$data_total[total_kredit];

		$jumdata = trim($_POST[cari_memorial]);
		
	}	

	if (isset($_POST[btn_save])){
		$qry_tmp_ins = mysql_query("INSERT INTO ".$tbl_trx_temp." (SELECT * FROM ".$tbl_trx." WHERE nbk='$_POST[txt_nobukti]' AND trx_status='1' AND mcom_id='$company_id')", $conn) or die("Error Insert Temp Caseglt ".mysql_error());			
		$qry_tmp_upd = mysql_query("UPDATE ".$tbl_trx_temp." SET user_id='$user_id' WHERE nbk='$_POST[txt_nobukti]'", $conn) or die("Error Insert Temp Caseglt ".mysql_error());					
		
		$qry_tmp = mysql_query("SELECT nbk,tgp,flg FROM ".$tbl_trx_temp."  WHERE user_id='$user_id'", $conn) or die("Error Select Temp Caseglt ".mysql_error());
		$data_top = mysql_fetch_array($qry_tmp);
		
		$nbk=$data_top[nbk];
		$tgp=$data_top[tgp];
		$flg=$data_top[flg];

		if ($flg==1) {
			$data_cf="From Cashflow";
		}
		
		$qry_tmp = mysql_query("SELECT SUM(deb) as total_debet, SUM(krd) as total_kredit FROM ".$tbl_trx_temp." WHERE user_id='$user_id' AND trx_status='1' ", $conn) or die("Error Select Total Transaction ".mysql_error());
		$data_total=mysql_fetch_array($qry_tmp);
	
		//transform
		$txtdebet=tampil_uang($data_total[total_debet],true);				
		$txtkredit=tampil_uang($data_total[total_kredit],true);				
		
		//murni
		$totdebet=$data_total[total_debet];
		$totkredit=$data_total[total_kredit];

		$jumdata = trim($_POST[txt_nobukti]);
		
	}	
		
	$view_total="<input type='hidden' name=\"total_debet\" id=\"t_debet\" value=\"$totdebet\"/> 
	<input type='hidden' name=\"total_kredit\" id=\"t_kredit\" value=\"$totkredit\"/>
	<input type='hidden' name=\"total_debet\" id=\"txt_debet\" value=\"$txtdebet\"/> 
	<input type='hidden' name=\"total_kredit\" id=\"txt_kredit\" value=\"$txtkredit\"/>";
?>

<div class="panel panel-primary">
	<div class="panel-heading">    
		<div class="row">
			<div class="col-xs-4"><p class="panel-title"><span class='glyphicon glyphicon-inbox'></span> JURNAL MEMORIAL <span class="badge alert-danger hidden-print" id="tampil_badge"><? echo "$jumdata"; ?></span> <span class="badge alert-danger" id="tampil_badge_cf"><? echo "$data_cf"; ?></span></p></div>
			<div class="col-xs-8 text-right hidden-print" >
				<button id="btn_add" name="btn_add" type="button" class="btn btn-success" onClick="klik_add()" data-toggle="tooltip" data-placement="bottom" title="Add New" accesskey="a" ><span class="glyphicon glyphicon-plus"></span></button> 
				<button id="btn_edit" name="btn_edit" type="button" class="btn btn-success" onClick="klik_edit()" accesskey="e" data-toggle="tooltip" data-placement="bottom" title="Edit" ><span class="glyphicon glyphicon-edit"></span></button>
				<button id="btn_delete" name="btn_delete" type="button" class="btn btn-success" onClick="klik_delete()" accesskey="d" data-toggle="tooltip" data-placement="bottom" title="Delete"><span class="glyphicon glyphicon-minus"></span></button>
				<button id="btn_save" name="btn_save" type="submit" class="btn btn-success" accesskey="s" data-toggle="tooltip" data-placement="bottom" title="Save"><span class="glyphicon glyphicon-ok"></span></button>
				<button id="btn_cancel" name="btn_cancel" type="button" class="btn btn-success" onClick="klik_cancel()" accesskey="c" data-toggle="tooltip" data-placement="bottom" title="Cancel"><span class="glyphicon glyphicon-remove"></span></button>
				<button id="btn_print" name="btn_print" type="button" class="btn btn-success" onClick="klik_print()" accesskey="p" data-toggle="tooltip" data-placement="bottom" title="Print"><span class="glyphicon glyphicon-print"></span></button>
				<button id="btn_find" name="btn_find" type="button" class="btn btn-success" onClick="klik_find()" accesskey="f" data-toggle="tooltip" data-placement="bottom" title="Find Transaction"><span class="glyphicon glyphicon-search"></span></button>			
			</div>
		</div>
	</div>
		<!-- CONTENT FORM -->
	<div class="panel-body">		
		<!-- AREA HEADER TRANSAKSI -->
	  <div class="row">
		  <div class="col-xs-6 col-md-3">
			  <div class="panel panel-primary alert-success">
				  <!-- AREA TOTAL JUMLAH -->
				  <div class="panel-body">					       
					<div class="row">
						<div class="col-xs-7">
						  <label for="txt_nobukti">Nomor Bukti :</label>
						  <input class="form-control input-sm" type="text" name="txt_nobukti" id="no_bukti" placeholder="Nomor Bukti ?" maxlength="15" size="25"  value="<?  echo $nbk; ?>" required></input>
						</div>
					 </div>
					<div class="row">
						<div class="col-xs-6"> 
							<label for="tgl_bukti">Tgl. Bukti :</label>
							<div class="input-group">							
							<input class="form-control input-sm" type="text" name="txt_tglbukti" id="tgl_bukti" placeholder="Tgl. Bukti ?" size="11" readonly value="<? $tgl=ubah_tgl(substr($tgp,0,10));  echo $tgl; ?>" ></input>
                            <input type="hidden" name="txt_tglaktif" id="tgl_aktif" value="<?  $tgl=ubah_tgl(substr($aktif_tgl,0,10));  echo $tgl; ?>">
                            <input type="hidden" name="h_tgl_bukti" id="h_tgl_bukti" value="<? $tgl=ubah_tgl(substr($tgp,0,10));  echo $tgl; ?>"> 
							</div>
						</div>
					</div> <!-- end row -->
				 </div> <!-- end panel body -->
			  </div>
		   </div>
	
			<!-- END AREA HEADER TRANSAKSI -->
		
		  <div class="col-xs-6 col-md-3">
			  <div class="panel panel-primary alert-success">
				  <div class="panel-heading text-center">
					TOTAL JUMLAH 					
				  </div>
				  <!-- AREA TOTAL JUMLAH -->
				  <div class="panel-body">
					<div class="input-group">
					<span class="input-group-addon"><label for="txt_debet">Debet</label></span><b><input class="form-control alert-info text-right" type="text" name="txt_debet" id="jml_debet" placeholder="Total Debet ?" readonly value="<?  echo $txtdebet; ?>" ></input></b>
					</div>					
					<div class="input-group"><span class="input-group-addon"><label for="txt_kredit">Kredit</label></span><b><input class="form-control alert-info text-right" type="text" name="txt_kredit" id="jml_kredit" placeholder="Total Kredit ?" readonly value="<?  echo $txtkredit; ?>" ></input></b></div>
				  </div>
				  <!-- END AREA TOTAL JUMLAH -->
			  </div>      
		  </div>
	   </div>
	   <!-- AREA UNTUK INPUT DETAIL JURNAL -->
		
		<div id="area_cari_data">  <!-- untuk mencari data akun / supplier -->
        <?
			echo $teks_salah;
		?>
		</div>
		
		<div id="area_input_detail">
		</div>
		
		<div class="row">
			<div class="col-xs-12">
				<!-- PANEL DETAIL -->
				<div class="panel panel-primary alert-success">
				  <div class="panel-heading">
					<div class="row">
						<div class="col-xs-6">
							<p>Tabel Detail Transaksi Jurnal <span class="badge alert-warning" id="tampil_badge_detail"></span> </p>
						</div>
						<div class="col-xs-6 hidden-print" style="font-style:italic;" align="right">						
							<button id="dbtn_add" type="button" class="btn btn-success" onClick="klik_d_add()" data-toggle="tooltip" data-placement="bottom" title="Add New Detail" accesskey="1" disabled="disabled"><span class="glyphicon glyphicon-plus"></span></button>
							<button id="dbtn_save" type="button" class="btn btn-success" onClick="klik_d_save()" data-toggle="tooltip" data-placement="bottom" title="Save" accesskey="2" disabled="disabled"><span class="glyphicon glyphicon-ok"></span></button>
							<button id="dbtn_cancel" type="button" class="btn btn-success" onClick="klik_d_cancel()" data-toggle="tooltip" data-placement="bottom" title="Cancel" accesskey="3" disabled="disabled"><span class="glyphicon glyphicon-remove"></span></button>
						</div>
					</div> <!--row -->            
				  </div>
				  
                  <!-- AREA DETAIL TRANSAKSI -->
				  <!--<div class="panel-body" id="table_detail">-->
                  
					<div id="table_detail"> 
					  <table class="table table-bordered table-hover table-condensed" align="center">
						<th colspan="2" width="2%" class="info hidden-print">Editing</th>
						<th width="2%" class="info">SEQ</th>
						<th width="10%" class="info">Kode Akun</th>
						<th width="30%" class="info">Nama Akun</th>
						<th width="30%" class="info">Keterangan</th>
						<th width="5%" class="info">Supplier</th>
						<th width="10%" class="info text-right">Debet</th>
						<th width="10%" class="info text-right">Kredit</th>
						<?

							$qry_tmp = mysql_query("SELECT a.*,b.nmp as nmp,c.nm_sup as nm_sup FROM ".$tbl_trx_temp." as a left join ".$tbl_mst_akun." as b on a.acc=b.acc left join ".$tbl_mst_sup." as c on a.kd_sup=c.kd_sup WHERE a.user_id='$user_id' AND a.trx_status='1' ORDER BY a.seq", $conn) or die("Error Select Show Detail ".mysql_error());
							$no=0;			
							while ($data = mysql_fetch_array($qry_tmp)) {
								$no ++;
								if ($tmbl_edit==2) {
									
									if ($data[deb]==0) { $dk="K" ;} else { $dk="D" ;}
									
									$deb=$data[deb];
									$krd=$data[krd];
									
									$ket=str_replace("'","\'",$data[ket]);
									$ket=str_replace('"',"\'",$ket);
									
									//$ket=$data[ket];
									
									//echo $ket;
																		
									$view_tmbl_edit="<button type='button' class='btn-link' name=\"btn_edit_detail\" id=\"edit_$data[seq]\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit data $data[seq]\" onclick=\"klik_d_edit('$data[seq]','$data[acc]','$data[nmp]','$ket','$data[kd_sup]','$data[nm_sup]','$deb','$krd','$dk')\" disabled='disabled'><span class=\"glyphicon glyphicon-edit\"/></span></button>";
									
								} else {
									$view_tmbl_edit="";
								}
								if ($tmbl_del==3) {
									$view_tmbl_del="<button type='button' class='btn-link' onclick=\"klik_d_delete('$data[seq]')\" name=\"btn_delete_detail\" id=\"delete_$data[seq]\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete data $data[seq]\" disabled='disabled'><span class=\"glyphicon glyphicon-trash\"/></span></button>";						
												
								} else {
									$view_tmbl_del="";
								}
								
								if ($data[11]==0){
									$nildebet=" - ";
									$view_debet="";
								} else {
									$nildebet=tampil_uang($data[11],true);
									$view_debet="<input type='hidden' name=\"jumlah_debet\" id=\"deb_$data[seq]\" value=\"$data[11]\"/>";
								}
								
								if ($data[12]==0){
									$nilkredit=" - ";
									$view_kredit="";
								} else {				
									$nilkredit=tampil_uang($data[12],true);
									$view_kredit="<input type='hidden' name=\"jumlah_kredit\" id=\"krd_$data[seq]\" value=\"$data[12]\"/>";
								}
								
								if ($data[seq]=='000'){
									echo "<tr class='field_data'>
										 <td class='hidden-print'></td>
										<td class='hidden-print'></td>
										<td>$data[seq]</td>
										<td id=\"acc_$data[seq]\">$data[acc]</td>
										<td id=\"nmp_$data[seq]\">$data[nmp]</td>
										<td id=\"ket_$data[seq]\">$data[ket]</td>
										<td id=\"kdsup_$data[seq]\">$data[kd_sup]</td>
										<td align=\"right\"> $view_debet $nildebet</td>
										<td align=\"right\"> $view_kredit $nilkredit</td>
										</tr>" ;
								} else {
									//$v_seq=intval($data[seq]);
									
									echo "<tr class=\"field_data\">
										 <td class='hidden-print'>$view_tmbl_edit</td>
										<td class='hidden-print'>$view_tmbl_del</td>
										<td><input type='hidden' name=\"nomor_seq\" id=\"seq_$data[seq]\" value=\"$data[seq]\"/>$data[seq]</td>
										<td id=\"acc_$data[seq]\">$data[acc]</td>
										<td id=\"nmp_$data[seq]\">$data[nmp]</td>
										<td id=\"ket_$data[seq]\">$data[ket]</td>
										<td id=\"kdsup_$data[seq]\">$data[kd_sup]</td>
										<td align=\"right\"> $view_debet $nildebet</td>
										<td align=\"right\"> $view_kredit $nilkredit</td>
										</tr><tr><td colspan='9'><div id='hapus-detail-$data[seq]'></div></td></tr>" ;
								}
							}
							if ($no==0) {  echo "<tr><td colspan='9'>Empty detail</td></tr>" ; }
						?>                
					  </table>
                      <? echo $view_total ; ?>
					<!--</div>-->
				  </div>
				  <!-- END AREA DETAIL TRANSAKSI -->
				</div>
			</div>
		</div>
		<!-- END OF CONTENT -->
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
				<h3 class="modal-title" id="myModalLabel2">KONFIRMASI</h3>
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
		<div class="modal-content alert-warning">
		<form name="frmhapus" id="frmhapus" method="post">
			<input type="hidden" name="id_menu" value="<? echo $_GET[id_menu]; ?>">
			<div class="modal-header" id="myheader-hapus">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>				
			</div>			
			<div class="modal-body" id="mybody-hapus"></div>
			<div class="modal-footer" id="myfooter-hapus">
            </div>
		</form>
		</div>
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
	<script src="aksi_memorial.js"></script>
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
		if ($_POST[cari_memorial]) {
		
		echo "<script> $('#tampil_badge').html('".$_POST[cari_memorial]."'); </script>"; 
		
		}
		if ($_POST[btn_save]) {
		
		echo "<script> $('#tampil_badge').html('".$_POST[txt_nobukti]."'); </script>"; 
		
		}	
			
}
else { header("location:/glt/no_akses.htm"); }
?>