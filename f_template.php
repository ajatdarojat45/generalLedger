<? session_start(); 
	if (isset($_SESSION['app_glt'])) {
		include("modules/inc/inc_procedure.php"); 
		include("modules/inc/inc_akses.php");
		include("modules/inc/inc_aed.php");
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="http://getbootstrap.com/assets/ico/favicon.ico">

<title>GL TEMPO</title>

<!-- Bootstrap core CSS -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- Just for debugging purposes. Don't actually copy this line! -->
<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
</head>
<body>

<script src="bootstrap/js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>  
<![if lt IE 9]>
  <script src="bootstrap/html5shiv.js"></script>
  <script src="bootstrap/respond.min.js"></script>
<![endif]
</body>
</html>
<?
} else {
	include "f_noakses.php";
}

?>