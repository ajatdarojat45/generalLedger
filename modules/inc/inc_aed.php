<?php
	// Add, Edit, Delete  Authority Validation
	$tmbl_add=0;
	$tmbl_edit=0;
	$tmbl_del=0;
	
	$query_data=mysql_query("SELECT mgbut_button_id FROM mst_granting_button WHERE mgbut_username='$_SESSION[app_glt]' AND mgbut_menu_id='$_GET[id_menu]' AND mgbut_button_id='1'", $conn) or die(mysql_error());
	$data_hasil = mysql_fetch_array($query_data);
	$tmbl_add=$data_hasil[0];
	
	$query_data=mysql_query("SELECT mgbut_button_id FROM mst_granting_button WHERE mgbut_username='$_SESSION[app_glt]' AND mgbut_menu_id='$_GET[id_menu]' AND mgbut_button_id='2'", $conn) or die(mysql_error());
	$data_hasil = mysql_fetch_array($query_data);

	$tmbl_edit=$data_hasil[0];

	$query_data=mysql_query("SELECT mgbut_button_id FROM mst_granting_button WHERE mgbut_username='$_SESSION[app_glt]' AND mgbut_menu_id='$_GET[id_menu]' AND mgbut_button_id='3'", $conn) or die(mysql_error());
	$data_hasil = mysql_fetch_array($query_data);

	$tmbl_del=$data_hasil[0];

?>