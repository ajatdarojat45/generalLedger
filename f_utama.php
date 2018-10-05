<?php session_start(); 
	if (isset($_SESSION['app_glt'])) {
	
	//isset($_SESSION['app_glt'])
		include("modules/inc/func_modul.php"); 
		include("modules/inc/inc_akses.php");
			
		if ($_GET[user_logout]==true) {
			$_GET[user_logout]=false;
	
			$update_status_user = mysql_query("UPDATE mst_login SET mlog_login='0',mlog_info='' WHERE mlog_username = '$_SESSION[app_glt]' ", $conn) or die("Error Query update status user");
	
			mysql_close($conn);
			
			//session_unregister ("app_glt");
			unset($_SESSION['app_glt']);
	
			//session_destroy();
			//session_unset(); 
			
			header("location:../glt/") ;	

			echo "<script language=javascript>window.close();</script>";
			
			exit;			
		}
		
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<!--<meta http-equiv="refresh" content="10">-->
    <link rel="shortcut icon" href="img/favicon.ico">

    <title>GL TEMPO</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap-3/css/bootstrap.css" rel="stylesheet">
	<link href="style/style_utama.css" rel="stylesheet">
	<link href="themes/sunny/easyui.css" rel="stylesheet" >
	<link href="themes/icon.css" rel="stylesheet" >
	
</head>

<body style="background-image: url(img/SadlerGibb-Background-Darkened.jpg)">

	<!-- Fixed navbar -->
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	  <div class="container">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a id="tombol-home" class="navbar-brand" href="../glt">GL TEMPO</a>	  
		</div>
		<div class="navbar-collapse collapse">
		  <ul class="nav navbar-nav">
			<li><a href="../glt"><span class="glyphicon glyphicon-home"></span></a></li>			
			<!--GENERATE MENU AUTHORITY-->		
			<?
				include "f_menu.php" ;
			?>   
			<!--END OF GENERATE MENU AUTHORITY--> 

			<!--<img src="img/favicon.ico" height="19" width="23">--> 			
		  </ul>	
		  <?
			//$jml_usr = mysql_query("SELECT COUNT(*) as jumlah FROM mst_login WHERE mlog_login <> '0' ", $conn) or die("Error Query update status user");
			//$data=mysql_fetch_array($jml_usr);
			//$jumlah_user=$data[jumlah];
		  ?>  
		  <ul class="nav navbar-nav navbar-right">
			<li class="dropdown"> 
				<a id="user_area" href="#" class="dropdown-toggle" data-toggle="dropdown" ><span class="glyphicon glyphicon-user"></span> User <b class="caret"></b></a>
				  <ul class="dropdown-menu" aria-labelledby="menu_user">
					<li class="dropdown-header">USER PROFILE</li>
					<li class="divider"></li>                
					<li><a href="#" ><? echo "$user_name"; ?></a></li>
					<li class="divider"></li>                
					<li><a href="#" onClick="mubahpass('<? echo $user_id; ?>')">Change Password</a></li>
					<li class="divider"></li>                
					<li><a href="#" onClick="mkeluar('<? echo $user_name ?>')"  data-toggle="tooltip" data-placement="bottom" title="Logout from system..."><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
				  </ul>			
			</li>
		  </ul>
		</div>
	  </div>
	</div>
	
	<!--- footer main page --->
	<div class="mp_content">
		<iframe src="f_main_page.html" id="frame_utama" name="frame_utama" width="100%" height="100%" frameborder="0" style="margin-top:5px;padding:5px"> </iframe>
		<div id="mp_footer">
			General Ledger System Copyright 2015 &copy; TEMPO MEDIA GROUP 
		</div>
		<!-- Dialog HELP ------------------------>
		<div id="dlg-help-utama" class="easyui-dialog" style="width:50%;height:300px"
				closed="true" buttons="#dlg-btn-help-utama" modal="false" collapsible="false" maximizable="true" resizable="true" draggable="true" title="HELP"> 
			<iframe src="help_utama.txt" id="frame_help_utama" name="frame_help_utama" width="100%" height="98%" frameborder="1"> </iframe>
		</div>
		<div id="dlg-btn-help-utama">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-help-utama').dialog('close')" style="width:90px">Close</a>
		</div>
		<div class="tombol-help">
			<a href="#" class="easyui-linkbutton" iconCls="icon-help" tooltip="true"  title="User Manual" onclick="$('#dlg-help-utama').dialog('open').dialog('setTitle','User Manual');$('#dlg-help-utama').dialog('center');" style="height:40px;width:40px"></a>
		</div>
	</div>

	<!-- awal untuk modal dialog -->
	<div id="dialog-utama" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
			<form name="frmutama" id="frmutama" method="post">
				<div class="modal-header alert-warning">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h3 class="modal-title" id="myModalLabel">FORM LOGOUT OR CHANGE PASSWORD</h3>
				</div>			
				<div class="modal-body"></div>
				<div class="modal-footer alert-warning">
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
	
	<!-- awal untuk modal dialog -->
	<div id="dialog-akun" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<form name="frmmodul" method="post">
			<div class="modal-content">
				<div class="modal-header alert-warning">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h3 class="modal-title">FORM ADD NEW OR EDIT </h3>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer alert-warning" id="myfooter">
				</div>
			</div>
			</form>
		</div>
	</div>

	

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="bootstrap-3/js/bootstrap.min.js"></script>
	<script src="js/jquery.easyui.min.js"></script>
    <script src="js/glt.js"></script>

</body>
</html>
<?
} else { header("location:/glt/no_akses.htm"); }
?>