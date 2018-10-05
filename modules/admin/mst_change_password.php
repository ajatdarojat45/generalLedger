<?php

session_start(); 
if (isset($_SESSION['app_glt']))
{	$tipe_alert="0";

	include "../inc/inc_akses.php"; 

if($_GET[btn_save_changepassword]=='true'){
	
	$ck_old = mysql_query("SELECT mlog_password FROM mst_login WHERE mlog_username = '$_SESSION[app_glt]' ", $conn) or die("Error Query Check Old Password");
	$ft_old = mysql_fetch_array($ck_old);
	
	//$ck_dt_1 = mysql_query("SELECT mst_login.mlog_name FROM mst_login WHERE mst_login.mlog_username = '$_SESSION[app_glt]'  ",$conn) or die("Error Query 1");
	//$ft_dt_1 = mysql_fetch_array($ck_dt_1);

	$n_passwrd = $_SESSION[app_glt]."24".$_POST[txt_oldpassword]."086666";
	$nue_passwrd = md5(md5("wawan".$n_passwrd));
							

	if($ft_old[0] == $nue_passwrd){
		if($_POST[txt_newpassword] == $_POST[txt_renewpassword]){
			$n_passwrd = "";
			$nue_passwrd = "";
			
			$n_passwrd = $_SESSION[app_glt]."24".$_POST[txt_newpassword]."086666";
			$nue_passwrd = md5(md5("wawan".$n_passwrd));
			
			$upd_new = mysql_query("UPDATE mst_login SET mlog_password = '$nue_passwrd' WHERE mlog_username = '$_SESSION[app_glt]' ", $conn) or die("Error Query Update Password");
			
			$tipe_alert="1";
			/* echo "<script language=javascript> alert (' Success : Your Password Has Been Change ! ');</script> "; */
           }
		else{ $tipe_alert="2";			
			/* echo "<script language=javascript> alert (' Error : Retype Password Not Match ! '); </script>";*/
		}
	}
	else{ $tipe_alert="3";
			
		/* echo "<script language=javascript> alert (' Error : Old Password is Wrong ! '); </script>";*/
	}
	
}

?>
<!DOCTYPE html>
<HTML>
<HEAD>
<meta name="viewport" content="width=device-width, initial-scale=1">
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.min.css">
<!-- Bootstrap core CSS -->
<link href="../../bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="../../style/style_login.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->


<![if lt IE 9]>
  <script src="../../bootstrap/html5shiv.js"></script>
  <script src="../../bootstrap/respond.min.js"></script>
<![endif]>


</HEAD>

<BODY>
<!--<div class="container">-->
<table border="0" width="300px" align="center">
	<tr><td>User account :</td></tr>
	<tr>
	<td>
		<div class="input-group input-group-sm">
        	<span class="input-group-addon"><span class="glyphicon glyphicon-user" style="color:#0000FF;"></span></span><input value=" <? echo $_SESSION[app_glt]; ?>" id="btn01" name="txt_username" type="text" class="form-control" placeholder="user id ?" readonly required autofocus><span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span>			
		</div>
	</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
	<td>
		<div class="input-group input-group-sm">
        	<span class="input-group-addon"><span class="glyphicon glyphicon-lock" style="color:#0000FF;"></span></span><input id="btn02" name="txt_oldpassword" type="password" class="form-control" placeholder="Current Password ?" value="<? $_GET[txt_oldpassword]; ?>" required><span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span>
		</div>
	</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>Change Password :</td></tr>
	<tr>
	<td>
		<div class="input-group input-group-sm">
        	<span class="input-group-addon"><span class="glyphicon glyphicon-lock" style="color:#FF0000;"></span></span><input  id="btn03" name="txt_newpassword" type="password" class="form-control" placeholder="New Password ?" value="<? $_GET[txt_oldpassword]; ?>" required><span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span>
		</div>
	</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
	<td>
		<div class="input-group input-group-sm">		
        	<span class="input-group-addon"><span class="glyphicon glyphicon-lock" style="color:#FF0000;"></span></span><input  id="btn04" name="txt_renewpassword" type="password" class="form-control" placeholder="Retype New Password ?" value="<? $_GET[txt_oldpassword]; ?>" required><span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span>
		</div>
	</td>
	</tr>
	<tr>
	<td>
	<tr><td><input type="hidden" id="var_hasil" name="test_var" value="<? if ($tipe_alert=="1") {echo "hasil_1" ; } else if ($tipe_alert=="2") {	echo "hasil_2" ; }else if ($tipe_alert=="3") { echo "hasil_3" ; } else { echo "kosong" ; } ?>"> </td></tr>
	<tr>
	<td>
<?
	if ($tipe_alert=="1") {
		echo "<div class='button btn-sm' ><div class='alert alert-success alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a><strong>$_SESSION[app_glt]</strong>, Your Password Has Been Change !!!</div></div> ";
	} 
	if ($tipe_alert=="2") {
		echo "<div class='button btn-sm' ><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a><strong>$_SESSION[app_glt]</strong>, Retype New Password Not Match !</div></div><script language=javascript> $('#btn03').focus() ;	</script>";
		
	}else 
	if ($tipe_alert=="3") {
		echo "<div class='button btn-sm' ><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a><strong>$_SESSION[app_glt]</strong>, Your Current Password is wrong !</div></div><script language=javascript> $('#btn02').focus() ;	</script>";
	} 	
?>    </td>
	</tr>
</table>

<p id="tempat-pesan"></p>  
<!--</div>-->
<!-- session -->
<?php
}
else
{
	echo"<title>:: PT Tempo Inti Media Tbk ::</title>
				<link href=\"../../style\style.css\" rel=stylesheet>";
	echo "<center>";
	echo "<h3>Acess Denied</h3>";
	echo "Please <a href=../../index.php target=$_self>[Login]</a> First<br>";
	echo "</center>";

}
?>

    <script src="../../js/jquery-1.11.0.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js"></script>  
	<script language=javascript>	
		
	function validasi()
	{
		txt_oldpassword_jv = window.document.forms["frm_change_password"].txt_oldpassword;
		txt_newpassword_jv = window.document.forms["frm_change_password"].txt_newpassword;
		txt_renewpassword_jv = window.document.forms["frm_change_password"].txt_renewpassword;
	
		if(txt_oldpassword_jv.value=="")
		{
			//alert("Error : Old Password is empty !! ")
			//pesan('ISI','JUDUL');
			$('.modal-body #btn02').focus();
			txt_oldpassword_jv.focus();
			return false;
		}
		else if(txt_newpassword_jv.value=="")
		{
			//alert("Error : New Password is empty !! ")
			$('.modal-body #btn03').focus();
			txt_newpassword_jv.focus();
			return false;
		}
		else if(txt_renewpassword_jv.value=="")
		{
			//alert("Error : Re-type New Password is empty !! ")
			$('.modal-body #btn04').focus();
			txt_renewpassword_jv.focus();
			return false;
		}
		else
			return true;
			
		$('#btn02').focus();
	}
	</script>

</BODY>
</HTML>