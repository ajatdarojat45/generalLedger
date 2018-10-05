<?php
function ins_trans_menu($menu, $user_app){
	
	include "inc_akses.php";
	
	$sequent = mysql_query("SELECT MAX(tmenu_seq) FROM trans_menu ", $conn) or die("Error Query Sequent ");
	$ft_sequ = mysql_fetch_array($sequent);
	$nue_sequent = $ft_sequ[0] + 1;


	$insert_menu = "";
	$insert_menu = $insert_menu."INSERT INTO trans_menu VALUES ( ";
	$insert_menu = $insert_menu."'$nue_sequent', '$user_app', '$menu', '$_POST[cdatetime]' ) ";
	
	//echo $insert_menu."<br>";
	
	$query_menu = mysql_query($insert_menu, $conn) or die("Error Insert Menu -- ".mysql_error()); 

	}

?>