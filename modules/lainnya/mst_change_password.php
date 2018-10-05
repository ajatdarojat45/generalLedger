<?php
session_start(); if (isset($_SESSION['app_glt']))
{
include "../inc/inc_akses.php"; include "../inc/inc_trans_menu.php"; ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);

if($_POST[btn_save_changepassword]){
	$ck_old = mysql_query("SELECT mlog_password FROM mst_login WHERE mlog_username = '$_SESSION[app_glt]' ", $conn) or die("Error Query Check Old Password");
	$ft_old = mysql_fetch_array($ck_old);
	
	//$ck_dt_1 = mysql_query("SELECT mst_login.mlog_name FROM mst_login WHERE mst_login.mlog_username = '$_SESSION[app_glt]'  ",$conn) or die("Error Query 1");
	//$ft_dt_1 = mysql_fetch_array($ck_dt_1);

	$n_passwrd = $_SESSION[app_glt]."24".$_POST[txt_oldpassword]."086666";
	$nue_passwrd = md5(md5("adzhwa".$n_passwrd));

	if($ft_old[0] == $nue_passwrd){
		if($_POST[txt_newpassword] == $_POST[txt_renewpassword]){
			$n_passwrd = "";
			$nue_passwrd = "";
			
			$n_passwrd = $_SESSION[app_glt]."24".$_POST[txt_newpassword]."086666";
			$nue_passwrd = md5(md5("adzhwa".$n_passwrd));
			
			$upd_new = mysql_query("UPDATE mst_login SET mlog_password = '$nue_passwrd' WHERE mlog_username = '$_SESSION[app_glt]' ", $conn) or die("Error Query Update Password");
			
			echo "<script language=javascript>
				alert (' Success : Your Password Has Been Change ! ');
			</script>";
		}
		else{
			echo "<script language=javascript>
				alert (' Error : Retype Password Not Match ! ');
			</script>";
		}
	}
	else{
		echo "<script language=javascript>
			alert (' Error : Old Password is Wrong ! ');
		</script>";
	}
	
}

?><HTML>
<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.min.css">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language=javascript>	
function validasi()
{
	txt_oldpassword_jv = window.document.forms["frm_change_password"].txt_oldpassword;
	txt_newpassword_jv = window.document.forms["frm_change_password"].txt_newpassword;
	txt_renewpassword_jv = window.document.forms["frm_change_password"].txt_renewpassword;

	if(txt_oldpassword_jv.value=="")
	{
		alert("Error : Old Password is empty !! ")
		txt_oldpassword_jv.focus();
		return false;
	}
	else if(txt_newpassword_jv.value=="")
	{
		alert("Error : New Password is empty !! ")
		txt_newpassword_jv.focus();
		return false;
	}
	else if(txt_renewpassword_jv.value=="")
	{
		alert("Error : Re-type New Password is empty !! ")
		txt_renewpassword_jv.focus();
		return false;
	}
	else
		return true;

}
</script>
</HEAD>

<BODY>
<form name="frm_change_password" method="POST">
<table border="0" width="100%">
	<tr class="font_label_form">
		<td colspan=3>Form Change Password e-System</td>
	</tr>
	<tr>
		<td colspan=3><hr></td>
	</tr>
	<tr class="font_field_form">
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
	<tr class="font_field_form">
		<td width="17%" height="34"> User Id </td>
		<td width="2%"><font color="#FF0000">* </font> : </td>
		<td width="81%"><input type="text" name="txt_username" size="30" class="text" disabled value="<?php echo $_SESSION[app_glt]; ?>"></td>
	</tr>
	<tr class="font_field_form">
		<td height="30"> Old Password </td>
		<td><font color="#FF0000">* </font> : </td>
		<td><input type="password" name="txt_oldpassword" size="30" class="text"></td>
	</tr>
	<tr class="font_field_form">
		<td height="29"> New Password </td>
		<td><font color="#FF0000">* </font> : </td>
		<td><input type="password" name="txt_newpassword" size="30" class="text"></td>
	</tr>
	<tr class="font_field_form">
		<td height="28"> Retype New Password </td>
		<td><font color="#FF0000">* </font> : </td>
		<td><input type="password" name="txt_renewpassword" size="30" class="text"></td>
	</tr>
	<tr class="warning_form">
	  <td colspan=2>&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
	<tr class="warning_form">
	  <td colspan=3><hr></td>
    </tr>
	<tr class="warning_form">
		<td colspan=2>&nbsp;</td>
		<td>Note : (<font color="#FF0000">*</font>) Can not be Empty</td>
	</tr>
	<tr>
		<td colspan=2>&nbsp;</td>
		<td><input type='submit' value='   S A V E   ' name='btn_save_changepassword' class='tombol_1'  onClick='return validasi()'></td>
	</tr>
</table>
</form>

<!-- session -->
<?php
}
else { header("location:/glt/no_akses.htm"); }
?>
</BODY>
</HTML>