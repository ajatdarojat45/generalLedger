<?php
session_start();
//echo "TRANSAKSI KAS<br>";

if (isset($_SESSION['app_glt'])) {	
	//include "../inc/inc_akses.php";
	include "../inc/func_modul.php";
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

	<!-- Bootstrap core CSS -->

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
				?>
				</li>
			</ul>
		</div>
	</div>	
	
	<div id="lo" class="easyui-layout" style="width:100%;height:540px;">
		<div data-options="region:'center',title:'JURNAL KAS',split:true,href:'trans_kas_tabel.php'" style="padding:5px 5px 5px 5px;border:0px"></div>
	</div>
	
	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	
	<script src="../../js/jquery.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>   
	<script src="../../js/jquery.easyui.min.js"></script>
	<script src="../../js/lib_fungsi.js"></script>	
	<script src="aksi_kas.js"></script>
		
</body>
</html>

<?
			
}
else { header("location:/glt/no_akses.htm"); }

?>