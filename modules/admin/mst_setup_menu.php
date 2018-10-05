<?php 
session_start();

if (isset($_SESSION['app_glt'])) {
	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php"; 
	
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);
	
?>
<!DOCTYPE html>
<HTML>
<HEAD>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GL TEMPO</title>

<!-- Bootstrap core CSS -->
    <link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet"></link>
    <link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet"></link>
    <link href="../../style/style_utama.css" rel="stylesheet"></link>
</HEAD>
<BODY>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><span class="glyphicon glyphicon-tasks" ></span> SETUP MENU SYSTEM</h3>
  </div>
  <div class="panel-body">
	<!-- TOMBOL ADD -->
	<p><a href='#' class='btn btn-success btn-md' id="tombol-add" data-toggle="tooltip" data-placement="bottom" title="Buat menu baru" onClick="tambah_menu()"><span class='glyphicon glyphicon-plus'></span></a>
    </p>

  
	<div class="table">
		<table class="table table-bordered table-hover table-condensed">
			<tr>
				<th colspan="5" width="1%" class="info">Editing</th>
				<th width="1%"  class="info">#</th>
				<th width="5%" class="info">Menu ID</th>
				<th width="10%" class="info">Main Menu Name</th>
				<th width="20%" class="info">Description</th>
				<th width="2%" class="info">Active Status</th>
				
			</tr>
				<?
				$s_parentmenu = "";
				$s_parentmenu = $s_parentmenu."SELECT mst_menu_parent.mmpar_parent_id, mst_menu_parent.mmpar_parent_menu, mst_menu_parent.mmpar_desc, mst_menu_parent.mmpar_status ";
				$s_parentmenu = $s_parentmenu."FROM mst_menu_parent ORDER BY mst_menu_parent.mmpar_parent_id";
				$q_parentmenu = mysql_query($s_parentmenu, $conn) or die("Error Query s_parentmenu");
				
				$i  = 0;
				$no = 0;
				WHILE($fetch_parentmenu = mysql_fetch_array($q_parentmenu)){
					if ($i==0){			// color table per baris
						echo "<tr>";
						$i++;
					}else{
						echo "<tr>";
						$i--; }
					$no++;
					
					
					$view_tmbl_edit="<a href='#' id=\"menu$no\"  onclick=\"ubah_menu('$fetch_parentmenu[0]')\"  data-toggle=\"tooltip\" data-placement='bottom' title='Ubah menu' ><span class=\"glyphicon glyphicon-edit\" ></span></a>" ;					
					$view_tmbl_delete="<a href='#' id=\"menu$no\"  onclick=\"hapus_menu('$fetch_parentmenu[0]','$fetch_parentmenu[1]')\"  data-toggle=\"tooltip\" data-placement='bottom' title='Hapus menu' ><span class=\"glyphicon glyphicon-trash\" ></span></a>" ;	
					$view_tmbl_editsub="<a href='#' id=\"editsub$no\"  onclick=\"ubah_submenu('$fetch_parentmenu[0]')\"  data-toggle=\"tooltip\" data-placement='bottom' title='Ubah sub menu' ><span class=\"glyphicon glyphicon-pencil\"></span></span></a>" ;					
					$view_tmbl_addsub="<a href='#' id=\"addsub$no\"  onclick=\"tambah_submenu('$fetch_parentmenu[0]')\"  data-toggle=\"tooltip\" data-placement='bottom' title='Tambah sub menu' ><span class=\"glyphicon glyphicon-plus\" ></span></a>" ;
					$view_tmbl_listsub="<a href='#' id=\"listsub$no\"  onclick=\"list_submenu('$fetch_parentmenu[0]')\"  data-toggle=\"tooltip\" data-placement='bottom' title='Tampilkan daftar sub menu' ><span class=\"glyphicon glyphicon-tasks\" ></span></a>" ;
						
					echo "
						<td>$view_tmbl_edit</td>
						<td>$view_tmbl_delete</td>
						<td>$view_tmbl_editsub</td>
						<td>$view_tmbl_addsub</td>
						<td>$view_tmbl_listsub</td>
						<td>$no</td>
						<td>$fetch_parentmenu[0]</td>
						<td>$fetch_parentmenu[1]</td>
						<td>$fetch_parentmenu[2]</td>						
						<td>$fetch_parentmenu[3]</td>						
						</tr>";
				}
				?>
			
		</table>	
	</div> 
  	<p><a href='#' class='btn btn-success btn-md' id="tombol-add" data-toggle="tooltip" data-placement="bottom" title="Buat menu baru" onClick="tambah_menu()"><span class='glyphicon glyphicon-plus'></span></a>
    </p>
	<div id="tampil_sub">
	</div>
  </div>
</div>

<div id="dialog-modul" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
  		<div class="modal-content">
			<form id="frmmodul" name="frm_modul" method="post">
			<input type="hidden" name="id_menu" value="<? echo $_GET[id_menu]; ?>">
			<div class="modal-header  alert-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" name='tombol-x'>X</button>
				<h3 class="modal-title" id="myModalLabel">FORM NAME</h3>
			</div>			
			<div class="modal-body">
			</div>
			<div class="modal-footer  alert-warning">
            </div>
		    </form>
		</div>
	</div>
</div>

<div id="dialog-sub" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
  		<div class="modal-content">			
			<input type="hidden" name="id_menu" value="<? echo $_GET[id_menu]; ?>">
			<div class="modal-header alert-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" name='tombol-x'>X</button>
				<h3 class="modal-title" id="myModalLabel">FORM NAME</h3>
			</div>			
			<div class="modal-body">
			</div>
			<div class="modal-footer alert-warning">
            </div>		    
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
	<script src="aksi_menu.js"></script>
</BODY>
</HTML>
<!-- session -->
<?php

} else { header("location:/glt/no_akses.htm"); }
?>