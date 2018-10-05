<? 
session_start(); 
//print_r($_POST);
//print_r($_GET);
if (isset($_SESSION['app_glt'])) {
		include("../inc/inc_procedure.php"); 
		include("../inc/inc_akses.php");
		include("../inc/inc_aed.php");

		//echo "$tmbl_add , $tmbl_edit, $tmbl_del ";
			
?>
<!DOCTYPE html>
<html lang="en"><head>

<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GL TEMPO</title>

<!-- Bootstrap core CSS -->
    <link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet"></link>
    <link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet"></link>
    <link href="../../style/style_utama.css" rel="stylesheet"></link>
</head>
<body>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><span class='glyphicon glyphicon-user'></span> USER ACCOUNT </h3>
  </div>
  <div class="panel-body">
	<!-- TOMBOL ADD -->
	<p><a href='#' class='btn btn-success btn-md' id="tombol-add" data-toggle="tooltip" data-placement="bottom" title="Buat user baru" onClick="tambah_user()"><span class='glyphicon glyphicon-plus'></span></a>
   </p>

  
	<div class="table">
		<table class="table table-bordered table-hover table-condensed">
			<tr>
				<th colspan="5" width="6%" class="info">Editing</th>
				<th width="1%"  class="info">#</th>
				<th width="10%"  class="info">User ID.</th>
				<th width="25%" class="info">Nama Lengkap</th>
				<th width="20%" class="info">Email</th>
				<th width="20%" class="info">Jabatan dan Bagian</th>			
				<th width="1%"  class="info">Active</th>				
			</tr>
				<?
				$s_username = "SELECT * FROM mst_login ORDER BY mst_login.mlog_username ";
				$q_user = mysql_query($s_username, $conn) or die("Error Query Username");
				$no=0;
				while ($ft_user = mysql_fetch_array($q_user)) {
					$no ++;
					
						$view_tmbl_edit="<a href='#' id=\"menu$no\"  onclick=\"ubah_user('$ft_user[mlog_username]')\"  data-toggle=\"tooltip\" data-placement='bottom' title='Ubah user' ><span class=\"glyphicon glyphicon-edit\" ></span></a>" ;
						
						$view_tmbl_pt="<a href='#' id=\"pt$no\"  onclick=\"company_user('$ft_user[mlog_username]')\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Granting Company\" ><span class=\"glyphicon glyphicon-collapse-up\"></span></a>";
						
						$view_tmbl_menu="<a href='#' id=\"menu$no\"  onclick=\"menu_user('$ft_user[mlog_username]')\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Granting Menu Authority\" ><span class=\"glyphicon glyphicon-collapse-down\"></span></a>";
					
						$view_tmbl_del="<a href='#' id=\"del$no\"  onclick=\"delete_user('$ft_user[mlog_username]')\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Hapus/nonaktifkan user\" ><span class=\"glyphicon glyphicon-trash\"></span></a>";
						
						$view_tmbl_reset="<a href='#' id=\"reset$no\"  onclick=\"reset_user('$ft_user[mlog_username]')\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Reset password user\" ><span class=\"glyphicon glyphicon-lock\"></span></a>";					
						
					if ($ft_user[mlog_status]=='0') { $view_tmbl_del="<a href='#' id=\"del$no\"  onclick=\"aktifkan_user('$ft_user[mlog_username]')\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Aktifkan user\" ><span class=\"glyphicon glyphicon-trash\"></span></a>";}
					
					echo "<tr><td>$view_tmbl_edit</td>
						<td>$view_tmbl_pt</td>
						<td>$view_tmbl_menu</td>
						<td>$view_tmbl_reset</td>
						<td>$view_tmbl_del</td>
						<td>$no</td>
						<td>$ft_user[mlog_username]</td>
						<td>$ft_user[mlog_name]</td>
						<td>$ft_user[mlog_email]</td>
						<td>$ft_user[mlog_keterangan]</td><td>$ft_user[mlog_status]</td></tr>";
				}
				?>
			
		</table>	
	</div> 
  	<p><a href='#' class='btn btn-success btn-md' id="tombol-add" data-toggle="tooltip" data-placement="bottom" title="Buat user baru" onClick="tambah_user()"><span class='glyphicon glyphicon-plus'></span></a>
    </p>
  </div>
</div>

<div id="dialog-user" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
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
	<![if lt IE 9]>
	  <script src="../../bootstrap/html5shiv.js"></script>
	  <script src="../../bootstrap/respond.min.js"></script>
	<![endif]>
	
    <script src="../../js/jquery-1.11.0.min.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>
	<script src="aksi_user.js"></script>
	
</body>
</html>
<?

} else { header("location:/glt/no_akses.htm"); }

?>