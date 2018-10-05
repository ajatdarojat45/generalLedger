<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt']))
{	
	include "../inc/inc_trans_menu.php";
	include "../inc/func_modul.php";
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	
	$table_sup="mst_sup_".$company_id;
	
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
	
    <link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet">
    <link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet">
    <link href="../../style/style_utama.css" rel="stylesheet">
	<link href="../../themes/sunny/easyui.css" rel="stylesheet" >
	<link href="../../themes/icon.css" rel="stylesheet" >
	
</head>
<body onLoad="tombol_reset();">
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
            <ul class="nav navbar-nav navbar-right">
                <li>		
                <?
                    include "../inc/inc_top_head.php";
                ?>
                </li>
			</ul>
        </div>
    </div>
		
	<div id="lo" class="easyui-layout" style="width:100%;height:540px;">
		<div data-options="region:'center',title:'Master Data Supplier',split:true,href:'tabel_supplier_tabel.php'" style="padding:5px 5px 5px 5px;border:0px"></div>
	</div>

		
	<script src="../../js/jquery.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>   
	<script src="../../js/jquery.easyui.min.js"></script>
	<script src="../../js/lib_fungsi.js"></script>	
	<script src="aksi_supplier.js"></script>
    
</body>
</html>
<!-- session -->
<?
	if($_POST[btn_add]){
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