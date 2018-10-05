<? session_start(); 
	if (isset($_SESSION['app_glt'])) {
	$prod = "waktu"; 
	include("../../procedure.php"); 
	include("../../inc/inc_akses.php");
	include("../../inc/func_sysdate.php");
	//include("../../inc/gl_top_head.php");
?>
<html>
<head>
<title>GL TEMPO</title>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.min.css">
</head>
<body>

<div id="mp_header">
	<div id="mp_header_isi" class="font_header"><a href="/gltempo" target="_top">HOME</a> | <a href="/gltempo/home_end.php">Logout</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Username : <? echo "$user_name"; ?>&nbsp;
	</div> 
</div>
<div id="mp_content">
	<div id="mp_content_kiri">
		<div id="mp_content_pt" ><? include("../../inc/gl_top_head.php"); ?></div>
		<div id="mp_content_menu"><? include "main_menu.php";?></div>
	</div>
	<div id="mp_content_kanan">
		<div id="mp_content_isi">
			<iframe name=maincash src="main_page_right.php" height="100%" width="100%" border=0 frameborder=0></iframe>
		</div>
	</div>
	
</div> <!-- END OF CONTENT -->
</body>
</html>
<?php
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

