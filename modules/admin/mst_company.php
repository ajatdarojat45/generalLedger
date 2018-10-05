<?php
session_start();
if (isset($_SESSION['app_glt']))
{
	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php";
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	include "../inc/inc_aed.php";
	
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>GL TEMPO</title>

	<!-- Bootstrap core CSS -->
    <link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet"></link>
    <link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet"></link>	

	<![if lt IE 9]>
	  <script src="../../bootstrap/html5shiv.js"></script>
	  <script src="../../bootstrap/respond.min.js"></script>
	<![endif]>
	
</head>
<BODY>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><span class="glyphicon glyphicon-copyright-mark"></span> SETUP COMPANY</h3>
  </div>
  <div class="panel-body">

	<!-- TOMBOL ADD -->
	<p>
	<a href='#' class='btn btn-success btn-md' id="tombol-add" data-toggle="tooltip" data-placement="bottom" title="Buat perusahaan baru" onClick="tambah_pt()">
	<span class='glyphicon glyphicon-plus'></span></a>    	
    </p>  
	<table class="table table-bordered table-hover table-condensed">
		<tr >
			<th colspan="2" width="1%" class="info">Editing</th>
			<th width="1%" class="info">#</th>
			<th width="1%" class="info">Kode</th>
			<th width="8%" class="info">Nama Perusahaan</th>
			<th width="10%" class="info">Alamat Perusahaan</th>
			<th width="2%" class="info">Kode Pos</th>
			<th width="5%" class="info">Telpon</th>
			<th width="5%" class="info">Fax</th>
			<th width="5%" class="info">NPWP</th>
			<th width="3%" class="info">Active Status</th>						
		</tr>
		<?			
		$query_data_akun = mysql_query("SELECT * FROM mst_company ORDER BY mcom_id ASC ", $conn) or die("Error Select company Table ".mysql_error());		
		$jml_rec_data=mysql_num_rows($query_data_akun);
		$no_urut=1;		
		if ($jml_rec_data) {			
			while ($query_hasil = mysql_fetch_array($query_data_akun)){
				
				if ($tmbl_edit==2) {
					$view_tmbl_edit="<a href='#' id=\"edit$no\"  onclick=\"ubah_pt('$query_hasil[mcom_id]')\"  data-toggle=\"tooltip\" data-placement='bottom' title='Ubah perusahaan' ><span class=\"glyphicon glyphicon-edit\" ></span></a>" ;
						
				} else {
					$view_tmbl_edit="";
				}
				if ($tmbl_del==3) {					
					$view_tmbl_del="<a href='#' id=\"del$no\"  onclick=\"delete_pt('$query_hasil[mcom_id]')\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Nonaktifkan perusahaan\" ><span class=\"glyphicon glyphicon-trash\"></span></a>";
						
				} else {
					$view_tmbl_del="";
				}
								
			echo "<tr class='field_data'> 
				<td>$view_tmbl_edit</td>
				<td>$view_tmbl_del</td>
				<td>$no_urut.</td>
				<td>$query_hasil[mcom_id]</td>
				<td>$query_hasil[mcom_company_name]</td>
				<td>$query_hasil[mcom_address]</td>
				<td>$query_hasil[mcom_zipcode]</td>
				<td>$query_hasil[mcom_phone]</td>
				<td>$query_hasil[mcom_fax]</td>
				<td>$query_hasil[mcom_npwp]</td>
				<td>$query_hasil[mcom_status]</td></tr>";
			$no_urut ++;
			}
			echo "<tr ><td colspan='5' class='field_head'></td></tr>";
		}
		else{
			echo "<tr class='field_data'><td colspan='5'>Empty Data Record.</td></tr>";
		}
		?>
	</table>	
  	<p>
	<a href='#' class='btn btn-success btn-md' id="tombol-add" data-toggle="tooltip" data-placement="bottom" title="Buat perusahaan baru" onClick="tambah_pt()">
	<span class='glyphicon glyphicon-plus'></span></a>    	
    </p>
  </div>
</div>

<div id="dialog-pt" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
  		<div class="modal-content">
			<form id="frmmodul" name="frm_modul" method="post">
			<input type="hidden" name="id_menu" value="<? echo $_GET[id_menu]; ?>">
			<div class="modal-header alert-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" name='tombol-x'>X</button>
				<h3 class="modal-title" id="myModalLabel">FORM LOGOUT OR CHANGE PASSWORD</h3>
			</div>			
			<div class="modal-body">
			</div>
			<div class="modal-footer alert-warning">
            </div>
			</form>
		</div>
	</div>
</div>
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../../js/jquery-1.11.0.min.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>
	<script src="aksi_pt.js"></script>
</BODY>
</HTML>
<!-- session -->
<?
	if($_POST[btn_add]){
	}
} else { header("location:/glt/no_akses.htm"); }
?>

