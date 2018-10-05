<?php 
session_start(); 

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {

	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php"; 
	include "../inc/func_modul.php";
	
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);
	

	$textout="";
	$tipe_alert=0;
	
	if($_GET[btn_save_parent]=='true'){
		if ($_GET[kd_aksi]=='DELETE'){
			$que_saved = mysql_query("DELETE FROM mst_menu_parent WHERE mmpar_parent_id='$_GET[id]' ", $conn) or die("Error Query Delete Parent Menu");

			$que_saved = mysql_query("DELETE FROM mst_menu_sub WHERE mmdet_parent_id='$_GET[id]' ", $conn) or die("Error Query Delete Sub Menu");

			$textout="Main Menu Has Been Deleted !";
		}
		
		if ($_GET[kd_aksi]=='ADDMENU'){
			$check_avaibility = mysql_query("SELECT * FROM mst_menu_parent WHERE mmpar_parent_menu = '$_POST[txt_parentmenu]'", $conn) or die("Error Query Check Avaibility");
			$row_avaibility = mysql_num_rows($check_avaibility);
			
			if($row_avaibility){
				$tipe_alert=1;
				$textout="Parent Menu Already Exist !";
			}
			else{
				if ($_POST[chk_status]=='1') { 
					$chk='1';
				} else { $chk='0';}
				
				
				$ins_parent = "";
				$ins_parent = $ins_parent."INSERT INTO mst_menu_parent VALUES ( ";
				$ins_parent = $ins_parent."'$_POST[id]', '$_POST[txt_parentmenu]', '$_POST[txt_desc]', '$chk', now(), '$_SESSION[app_glt]' ) ";
				$que_saved = mysql_query($ins_parent, $conn) or die("Error Query ins_parent");
				$textout="Main Menu Has Been Successfully Added !";
			}
		
		}
		
		if($_GET[kd_aksi]=='EDIT'){
			$check_avaibility = mysql_query("SELECT * FROM mst_menu_parent WHERE mmpar_parent_menu = '$_POST[txt_parentmenu]' AND mmpar_parent_id<>'$_GET[id]' ", $conn) or die("Error Query Check Avaibility");
			$row_avaibility = mysql_num_rows($check_avaibility);
			
			if($row_avaibility){
				$tipe_alert=1;
				$textout="Parent Menu Already Exist !";
			}
			else{
				if ($_POST[chk_status]=='1') { 
					$chk='1';
				} else { $chk='0';}
				
				$que_saved = mysql_query("UPDATE mst_menu_parent SET mmpar_parent_menu = '$_POST[txt_parentmenu]', mmpar_desc='$_POST[txt_desc]', mmpar_status='$chk', mmpar_stamp_date=now(), mmpar_stamp_user='$_SESSION[app_glt]' WHERE mmpar_parent_id='$_GET[id]' ", $conn) or die("Error Query Update Parent Menu");
				$textout="Main Menu Has Been Edited !";
			}
		}
		
		if($_GET[kd_aksi]=='EDITSUB'){
			$check_avaibility = mysql_query("SELECT * FROM mst_menu_sub WHERE mmdet_menu_name = '$_POST[txt_subname]' AND mmdet_menu_id<>'$_GET[subid]' ", $conn) or die("Error Query Check Avaibility");
			$row_avaibility = mysql_num_rows($check_avaibility);
			
			if($row_avaibility){
				$tipe_alert=1;
				$textout="Sub Menu Name Already Exist !";
			}
			else{
				if ($_POST[chk_substatus]=='1') { 
					$chk='1';
				} else { $chk='0';}
				$upd_submen = "";
				$upd_submen = $upd_submen."UPDATE mst_menu_sub SET mmdet_status='$chk', mmdet_menu_name='$_POST[txt_subname]',mmdet_target_frame='$_POST[txt_subframe]', mmdet_desc='$_POST[txt_subdesc]',mmdet_url='$_POST[txt_suburl]', mmdet_stamp_date=now(), mmdet_stamp_user='$_SESSION[app_glt]' ";
				$upd_submen = $upd_submen."WHERE mmdet_menu_id = '$_GET[subid]' ";
				$que_saved= mysql_query($upd_submen, $conn) or die("Error Query Update Submenu");
				$textout="Sub Menu Successfully Edited !";
			}
		}
		
		if($_GET[kd_aksi]=='ADDSUB'){
			$check_avaibility = mysql_query("SELECT * FROM mst_menu_sub WHERE mmdet_menu_name = '$_POST[txt_subname]' AND mmdet_menu_id<>'$_GET[subid]' ", $conn) or die("Error Query Check Avaibility");
			$row_avaibility = mysql_num_rows($check_avaibility);
			
			if($row_avaibility){
				$tipe_alert=1;
				$textout="Sub Menu Name Already Exist !";
			}
			else{
				if ($_POST[chk_substatus]=='1') { 
					$chk='1';
				} else { $chk='0';}	
				
				$upd_submen = "";
				$upd_submen = $upd_submen."INSERT INTO mst_menu_sub (mmdet_menu_id,mmdet_parent_id,mmdet_menu_name,mmdet_url,mmdet_target_frame,mmdet_desc,mmdet_status,mmdet_stamp_date,mmdet_stamp_user) ";
				$upd_submen = $upd_submen."VALUES ('$_GET[subid]','$_GET[id]','$_POST[txt_subname]','$_POST[txt_suburl]','$_POST[txt_subframe]','$_POST[txt_subdesc]','$chk',now(),'$_SESSION[app_glt]') ";
				$que_saved= mysql_query($upd_submen, $conn) or die("Error Query Add Submenu");
				$textout="Sub Menu Successfully Added !";
			}
		}
	}

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
<?php

if ($_GET[kd_aksi]=='ADDMENU' || $_GET[kd_aksi]=='EDIT' ) {

	$get_id = mysql_query("SELECT MAX(mmpar_parent_id) FROM mst_menu_parent ", $conn) or die("Error Query Get ID Parent Menu");
	$fet_id = mysql_fetch_array($get_id);
	$nui_id = $fet_id[0]+1;
	
	$sh_data = mysql_query("SELECT * FROM mst_menu_parent WHERE mmpar_parent_id = '$_GET[id]' ", $conn) or die("Error Query Show Data First");
	$ft_data = mysql_fetch_array($sh_data); 

?>
	<table border="0" align="center">
		<tr>
			Menu ID :
			<div class="input-group input-group-sm">			
			<div class="input-group-addon"><span class="glyphicon glyphicon-collapse-down"></span></div>
				<input id="btn01" type="text" name="txt_parentid" class="form-control" disabled value="<?php if($_GET[id]) echo $_GET[id]; else echo $nui_id; ?>" placeholder="Menu ID." data-toggle="tooltip" data-placement="bottom" title="Main Menu ID."><input type="hidden" name="id" value="<? if($_GET[id]) echo $_GET[id]; else echo $nui_id; ?>">
				<span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span>
			</div><br/>			
			Menu Name :
			<div class="input-group input-group-sm">			
			<div class="input-group-addon"><span class="glyphicon glyphicon-collapse-down"></span></div>
				<input name="txt_parentmenu" type="text" required class="form-control" id="btn02" placeholder="Menu Name ?" title="Main Menu Name" value="<?php echo $ft_data[1]; ?>" size="30" maxlength="50" data-toggle="tooltip" data-placement="bottom">
				<span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span>
			</div><br/>
			Menu Description :
			<div class="input-group input-group-sm">			
			<div class="input-group-addon"><span class="glyphicon glyphicon-collapse-down"></span></div>
				<input id="btn03" type="text" size="50" name="txt_desc" class="form-control" value="<?php echo $ft_data[2]; ?>" placeholder="Menu Description ?" data-toggle="tooltip" data-placement="bottom" title="Main Menu Description">
				<span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span>
			</div><br/>
			<div class="input-group input-group-sm">
			<?  if ($_GET[kd_aksi]=='ADDMENU') { 
					echo "<input id='btn04' type='checkbox' name='chk_status' value='1' data-toggle='tooltip' data-placement='bottom' title='Active Status' checked> Main Menu Active Status" ;	
				} else if ($_GET[kd_aksi]=='EDIT'){
					if ($ft_data[mmpar_status]=='1') {	
					echo "<input id='btn04' type='checkbox' name='chk_status' value='1' data-toggle='tooltip' data-placement='bottom' title='Active Status' checked> Main Menu Active Status" ;	
					} else {
					echo "<input id='btn04' type='checkbox' name='chk_status' value='1' data-toggle='tooltip' data-placement='bottom' title='Active Status'> Main Menu Active Status" ;	
					}
				}				
			?>
			</div>
		</tr>
	</table>
	<div id="tempat-pesan">
	<?
		if ($tipe_alert==1) {
			echo "<div class='button btn-sm' ><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a><strong>$_SESSION[app_glt]</strong>, $textout</div></div>";
		}
	?>
	</div>
<?
}

if ($_GET[kd_aksi]=='EDITSUB' || $_GET[kd_aksi]=='ADDSUB' )  {
	$get_id = mysql_query("SELECT MAX(mmdet_menu_id) FROM mst_menu_sub ", $conn) or die("Error Query Get Max Sub Menu ID ");
	$fet_id = mysql_fetch_array($get_id);
	$nui_subid = $fet_id[0]+1;
?>
<!--  Edit Sub Menu Content -->
<? 
	$sh_data = mysql_query("SELECT * FROM mst_menu_parent WHERE mmpar_parent_id = '$_GET[id]' ", $conn) or die("Error Query Show Data First");
	$ft_data = mysql_fetch_array($sh_data); 
	
	echo "<button class='btn btn-primary btn-block btn-sm' type='button' >Main Menu : $ft_data[0] - $ft_data[1]</button>"; 

	$sh_data = mysql_query("SELECT * FROM mst_menu_sub WHERE mmdet_parent_id = '$_GET[id]' ORDER BY mmdet_sort_list ", $conn) or die("Error Query Show Data First"); 
	$ft_data = mysql_fetch_array($sh_data);
	
	$sub_id=$ft_data[mmdet_menu_id];
	$sub_name=$ft_data[mmdet_menu_name];
	$sub_desc=$ft_data[mmdet_desc];
	$sub_url=$ft_data[mmdet_url];
	$sub_frame=$ft_data[mmdet_target_frame];
	$sub_status=$ft_data[mmdet_status];
	
	if ($_GET[kd_aksi]=='EDITSUB') {	
?>

<br>Sub Menu :
<select name="sub_menu_list" id="sub_menu_list" onChange="pilih_sub()">
<?

$sh_data = mysql_query("SELECT * FROM mst_menu_sub WHERE mmdet_parent_id = '$_GET[id]' ORDER BY mmdet_sort_list ", $conn) or die("Error Query Show Data First"); 

while ($ft_data = mysql_fetch_array($sh_data)) {
	$isi="$ft_data[mmdet_menu_id]|$ft_data[mmdet_menu_name]|$ft_data[mmdet_url]|$ft_data[mmdet_target_frame]|$ft_data[mmdet_desc]|$ft_data[mmdet_status]";

	//echo $isi[1]." <br>";
	if ($_GET[subid]==$ft_data[mmdet_menu_id]) { 
		$sel="selected" ;
		$sub_id=$ft_data[mmdet_menu_id];
		$sub_name=$ft_data[mmdet_menu_name];
		$sub_desc=$ft_data[mmdet_desc];
		$sub_url=$ft_data[mmdet_url];
		$sub_frame=$ft_data[mmdet_target_frame];
		$sub_status=$ft_data[mmdet_status];

		} else { $sel="";}  
	
	echo "<option name='sub_$ft_data[mmdet_menu_id]' value='$isi' $sel >$ft_data[mmdet_menu_name]</option>";
}

?>

</select>
<?
}

?>
<div id="sub_menu">
	<br/>
	Menu ID :
	<div class="input-group input-group-sm">
	<input id="btn01" type="text" name="txt_subid" class="form-control input-sm" value="<?php if ($_GET[kd_aksi]=='EDITSUB') {echo $sub_id; } else { echo $nui_subid;}?>" placeholder="Menu ID.?" data-toggle="tooltip" data-placement="bottom" title="Main Menu ID" readonly ><span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span></div>
	<br/>	
	Menu Name :
	<div class="input-group input-group-sm">
	<input id="btn02" type="text" size="30" name="txt_subname" class="form-control input-sm" value="<?php if ($_GET[kd_aksi]=='EDITSUB') {echo $sub_name; }?>" placeholder="Menu Name ?" data-toggle="tooltip" data-placement="bottom" title="Main Menu Name" required><span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span></div><br/>
	Menu URL :
	<div class="input-group input-group-sm">
	<input id="btn03" type="text" size="50" name="txt_suburl" class="form-control input-sm" value="<?php if ($_GET[kd_aksi]=='EDITSUB') {echo $sub_url;} ?>" placeholder="URL ?" data-toggle="tooltip" data-placement="bottom" title="Main Menu Description" required><span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span></div><br/>
	Menu Target Frame :
	<div class="input-group input-group-sm">
	<input id="btn04" type="text" size="50" name="txt_subframe" class="form-control input-sm" value="<?php if ($_GET[kd_aksi]=='EDITSUB') {echo $sub_frame; }?>" placeholder="Target Frame ?" data-toggle="tooltip" data-placement="bottom" title="Main Menu Description" required><span class="input-group-addon"><span class="glyphicon glyphicon-ok" style="color:#FF0000;"></span></span></div> <br/>
	Menu Description :
	<input id="btn05" type="text" size="50" name="txt_subdesc" class="form-control input-sm" value="<?php if ($_GET[kd_aksi]=='EDITSUB') {echo $sub_desc; }?>" placeholder="Menu Description ?" data-toggle="tooltip" data-placement="bottom" title="Main Menu Description">		
	<br>
	<?  if ($_GET[kd_aksi]=='ADDSUB') { 
			echo "<input id='btn06' type='checkbox' name='chk_substatus' value='1' data-toggle='tooltip' data-placement='bottom' title='Active Status' checked> Menu Active Status" ;	
		} else if ($_GET[kd_aksi]=='EDITSUB'){
			if ($sub_status=='1') {	
			echo "<input id='btn06' type='checkbox' name='chk_substatus' value='1' data-toggle='tooltip' data-placement='bottom' title='Active Status' checked> Menu Active Status" ;	
			} else {
			echo "<input id='btn06' type='checkbox' name='chk_substatus' value='1' data-toggle='tooltip' data-placement='bottom' title='Active Status'> Menu Active Status" ;	
			}
		}				
	?>
</div>

<!--  End Edit Sub Menu Content -->
<?
}

if ($_GET[kd_aksi]=='LISTSUB')  {
	$sh_data = mysql_query("SELECT * FROM mst_menu_parent WHERE mmpar_parent_id = '$_GET[id]' ", $conn) or die("Error Query Show Data First");
	$ft_data = mysql_fetch_array($sh_data); 
	
	echo "<button class='btn btn-primary btn-block btn-sm' type='button' >Main Menu : $ft_data[0] - $ft_data[1]</button>"; 

	$sh_data = mysql_query("SELECT * FROM mst_menu_sub WHERE mmdet_parent_id = '$_GET[id]' ORDER BY mmdet_sort_list ", $conn) or die("Error Query Show Data First"); 
	echo "<table class='table' align='center'>
			<th>#</th>
			<th>Sub Menu ID.</th>
			<th>Menu Name</th>
			<th>Menu URL</th>
			<th>Menu Traget Frame</th>
			<th>Menu Description</th>
			<th>Menu Status</th>
			";
	$no=1;
	while ($ft_data = mysql_fetch_array($sh_data)) {
		echo "<tr><td>$no</td>
				<td>$ft_data[mmdet_menu_id]</td>
				<td>$ft_data[mmdet_menu_name]</td>
				<td>$ft_data[mmdet_url]</td>
				<td>$ft_data[mmdet_target_frame]</td>
				<td>$ft_data[mmdet_desc]</td>
				<td>$ft_data[mmdet_status]</td>
				</tr>";
		$no ++;
	}
	echo "</table>";
}
?>

	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<![if lt IE 9]>
	  <script src="../../bootstrap/html5shiv.js"></script>
	  <script src="../../bootstrap/respond.min.js"></script>
	<![endif]>
	
    <script src="../../js/jquery-1.11.0.min.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>
</BODY>
</HTML>
<!-- session -->
<?php
	if ($que_saved==1){
			echo "<div class='button btn-sm'><div class='alert alert-success alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a>$textout </div></div>";
			$que_saved=0;					
		}
	}
else
{
	echo"<title>Manage Care</title>
				<link href=\"../../style\style.css\" rel=stylesheet>";
	echo "<center>";
	echo "<h3>Acess Denied</h3>";
//	echo "Please <a href=../../home_login.php target=$_self>[Login]</a> First<br>";
	echo "Please <a href=../../index.php target=$_self>[Login]</a> First<br>";
	echo "</center>";

}
?>