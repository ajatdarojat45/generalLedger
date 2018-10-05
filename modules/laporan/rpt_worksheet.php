<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {
	include "../../plugins/fpdf/fpdf.php";	
	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php";
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	include "../inc/func_modul.php";
	include "../inc/inc_aed.php";
	
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tgl_trans=substr($aktif_tgl,0,7);
	
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
<!--<link href="../../plugins/fpdf/fpdf.css" rel="stylesheet">-->

<!--onLoad="klik_reset_button();"-->
<BODY>

<input type="hidden" id="id-menu" value="<? echo $_GET[id_menu]; ?>">			

<div class="navbar navbar-default navbar-form" role="navigation">
	<div class="navbar-header"><h4 class="text-danger"> WORKSHEET REPORTS </h4>
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

<form name="frm_setup_akun_jurnal" method="POST" >
<div class="row">
	<div class="col-md-12">
		<div class=" form-inline alert-warning ">
			<label for="rpt-sel">Laporan : </label>
			<select class="form-control text-success" name="rpt_sel" id="rpt-sel" onChange="klik_pilih_laporan()">
			<?
			$que="select * from mst_menu_sub_detail where mmdet_sub_status='1' and mmdet_menu_id='$_GET[id_menu]'";
			$qry_sel = mysql_query($que, $conn) or die("Error Select Sub Detail ".mysql_error());		
			
			while ($data = mysql_fetch_array($qry_sel)) {
				echo "<option value=\"$data[mmdet_sub_url]?judul=$data[mmdet_sub_name]&rpt=$data[mmdet_sub_url]\" >$data[mmdet_sub_name]</option>";
			}
			?>
			</select>
			<label for="rpt-bln">Periode : </label>
			<select class="form-control" name="rpt_bulan" id="rpt-bln">
				<option value="01" <? if (substr($aktif_tgl,5,2)=='01') { echo 'selected' ;}  ?> >Januari</option>
				<option value="02" <? if (substr($aktif_tgl,5,2)=='02') { echo 'selected' ;}  ?> >Februari</option>
				<option value="03" <? if (substr($aktif_tgl,5,2)=='03') { echo 'selected' ;}  ?> >Maret</option>
				<option value="04" <? if (substr($aktif_tgl,5,2)=='04') { echo 'selected' ;}  ?> >April</option>
				<option value="05" <? if (substr($aktif_tgl,5,2)=='05') { echo 'selected' ;}  ?> >Mei</option>
				<option value="06" <? if (substr($aktif_tgl,5,2)=='06') { echo 'selected' ;}  ?> >Juni</option>
				<option value="07" <? if (substr($aktif_tgl,5,2)=='07') { echo 'selected' ;}  ?> >Juli</option>
				<option value="08" <? if (substr($aktif_tgl,5,2)=='08') { echo 'selected' ;}  ?> >Agustus</option>
				<option value="09" <? if (substr($aktif_tgl,5,2)=='09') { echo 'selected' ;}  ?> >September</option>
				<option value="10" <? if (substr($aktif_tgl,5,2)=='10') { echo 'selected' ;}  ?> >Oktober</option>
				<option value="11" <? if (substr($aktif_tgl,5,2)=='11') { echo 'selected' ;}  ?> >Nopember</option>
				<option value="12" <? if (substr($aktif_tgl,5,2)=='12') { echo 'selected' ;}  ?> >Desember</option>
			</select>  
			<input class="form-control" name="rpt_tahun" id="rpt-thn" type="number" value="<? $tahun=substr($tgl_trans,0,4); echo $tahun; ?>" size="4" maxlength="4" min="2008" max="2050" step="1"/>      
			<!--<button type="button" class="btn btn-success btn-sm" name="btn_rpt" id="btn_rpt" onClick="klik_set()" value="">Setup Report</button>        
	    -->
		<button type="button" class="form-control btn btn-success btn-sm" name="btn_go" id="btn_go" onClick="klik_proses();" data-loading-text="Wait..." value="">VIEW</button>
		
		</div>
	</div>
</div>
</form>

			<div id="area_isian">  <!-- input parameter laporan -->
			</div>		
<!--<a href="rpt_pdf.php" class="btn btn-success btn-sm" target="frame_pdf">CREATE PDF REPORT</a>-->
<!--<a href="rpt_pdf_kosong.php" class="btn btn-success btn-sm" target="frame_pdf">RESET PDF REPORT</a>-->


<div class="mp_content_laporan">
     <iframe src="" id="frame_pdf" name="frame_pdf" width="100%" height="100%" frameborder="0"> </iframe>
</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	
	<script src="../../js/jquery.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>   
	<script src="../../js/jquery.easyui.min.js"></script>
	<script src="../../js/lib_fungsi.js"></script>	
	<script src="aksi_worksheet.js"></script>
</BODY>
</HTML>
<!-- session -->
<?	

//print_r($_POST);

}
else { header("location:/glt/no_akses.htm"); }

?>
