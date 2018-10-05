<?php
 	session_start(); 
	
	$tipe_alert="";
	
	include("modules/inc/inc_akses.php"); 

	include("modules/inc/inc_log.php");
	
	// For maintain only or other exclusive process
	include("modules/inc/inc_kunci_site.php");
	
	/*echo "<script> alert('$kunci_sistem'); </script>";*/
	
	if ($kunci_sistem==1){ header("location:utama.htm"); }
	
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/favicon.ico"></link>

    <title>GL TEMPO</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap-3/css/bootstrap.css" rel="stylesheet">
	<link href="style/style_utama.css" rel="stylesheet">
	<link href="themes/sunny/easyui.css" rel="stylesheet" >
	<link href="themes/icon.css" rel="stylesheet" >
    <link href="style/style_login.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <![if lt IE 9]>
      <!--<script src="bootstrap-3/js/html5shiv.js"></script>
      <script src="bootstrap-3/js/respond.min.js"></script>-->
    <![endif]>

  </head>

  <body style="background-image: url(img/SadlerGibb-Background-Darkened.jpg)">
	<form class="form-group" name="form_login" method="post">
		<div class="container">
			<div class="col-md-4 col-md-offset-4">
				<table align="center" style="width:100%;margin-top:50px;border-left:1px">
				<tr><td>
					<h1 class="form-group-heading text-center" style="color:#d6d6d6;text-shadow:#000000 2px 2px 2px "><strong>GENERAL LEDGER</strong></h1>
				</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr height="50px">
					<td>
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span><input name="tx_username" id="btn01" tabindex="1" type="text" class="form-control input-lg" placeholder="userid ?" value="<?php echo $_POST[tx_username]; ?>" required autofocus />
					</div>
					</td>
				</tr>
				<tr height="50px">
					<td>
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span><input name="tx_password" tabindex="2" type="password" class="form-control input-lg" placeholder="password ?" required>
					</div>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>	
				<tr>
					<td>
					<input type="submit" name="txt_login" class="btn btn-success btn-block" value="LOGIN"> 
					</td>
				</tr>
				<tr height="50px"><td>
					<?php
						if ($tipe_alert=="1") {
							echo "<div class='button btn-sm' id='test01' ><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a><strong>$_POST[tx_username]</strong>, Invalid Password !</div></div>";
						}
						
						if ($tipe_alert=="2") {
							echo "<div class='button btn-sm'><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a><strong>$_POST[tx_username]</strong>, Your Account Has Been Disabled By Your Administrator !</div></div>";
						}
						
						if ($tipe_alert=="3") {
							echo "<div class='button btn-sm'><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a><strong>$_POST[tx_username]</strong>, Not Registered User ID. </div></div>";
						} 
						if ($tipe_alert=="4") {
							echo "<div class='button btn-sm'><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a><strong>$_POST[tx_username]</strong>, Already logged in from other location... </div></div>";
						} 

					?>
				</td></tr>
				</table>
			</div>
		</div>
	</form>
	<div class="container">
		<div class="col-md-4 col-md-offset-4">
			<footer class="text-center" style="color:#999999"><?php echo "$_POST[c_address]<br>"; ?>General Ledger System<br> Copyright @ <b>Tempo Media Group</b> &copy; 2015 <br><a href="http://creativecommons.org/licenses/by/3.0/" target="_blank">CC BY 3.0</a> </footer>	
		</div>
	</div>
	
    <div id="dialog-pesan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class='glyphicon glyphicon-info-sign'></span></button>
                    <h3 class="modal-title" id="myModalLabel">JUDUL PESAN</h3>
                </div>
            
                <div class="modal-body"></div>
            
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="bootstrap-3/js/bootstrap.min.js"></script>
    
	<script language=javascript>
	
	$('#btn01').focus();
		
	function validasi_form()
	{
		tx_usernamejv = window.document.forms["form_login"].tx_username;
		tx_passwordjv = window.document.forms["form_login"].tx_password;
	
		if(tx_usernamejv.value==""){
			//alert("WARNING : Username cannot empty !");
			//pesan('User ID tidak boleh kosong !','Perhatian');
			tx_usernamejv.hasfocus();
			return false;}
		else if(tx_passwordjv.value==""){
			//alert("WARNING : Password cannot empty !");
			//pesan('Password tidak boleh kosong !','Perhatian');
			tx_passwordjv.hasfocus();
			return false; }
		else{ return true; }
	}
	</script>
    
</body></html>