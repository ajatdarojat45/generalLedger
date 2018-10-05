<?php
if($_POST[txt_login]){
	$app_glt = $_POST[tx_username];
	//$company_cash = $_POST[tx_company];

	//--check validitas form
	//--username
	$q_user = mysql_query("SELECT * FROM mst_login WHERE mlog_username = '$app_glt' ",$conn ) or die("Error Query q_user");
	$row_user = mysql_num_rows($q_user);
	$fetch_user = mysql_fetch_array($q_user);

	//--676550060 
	$n_passwrd = $app_glt."24".$_POST[tx_password]."086666";
	$nue_pass = md5(md5("wawan".$n_passwrd));

	if($row_user){
		//if($fetch_user[mlog_login] == "1" && mlog_info<>$_POST[c_address]){
		//	$tipe_alert="4";
		//} else {
		
			if($fetch_user[mlog_status] == "1"){
				//--Password
				$q_password = mysql_query("SELECT * FROM mst_login WHERE mlog_password = '$nue_pass' AND mlog_username = '$app_glt' ", $conn) or die("Error Query q_password");
				$row_password = mysql_num_rows($q_password);
				
				if($row_password) { 
					//session_register("app_glt");
					$_SESSION['app_glt'] = $app_glt;
	
					// session_register("company_cash");
					// $_SESSION['company_cash'] = $company_cash;
					// $_SESSION["app_glt"] = $config[app_glt];
	
					//--update log status
					$ud_log = mysql_query("UPDATE mst_login SET mlog_login = '1', mlog_stamp_date = '$_POST[cdatetime]', mlog_info='$_POST[c_address]' WHERE mlog_username = '$app_glt' ", $conn) or die("Error Query Update Log Login");
					
					//--insert log status
					$c_seq_log = mysql_query("SELECT MAX(tlog_seq) FROM trans_login ", $conn) or die("Error Query Sequent Log Trans");
					$f_seq_log = mysql_fetch_array($c_seq_log);
					$new_seq = $f_seq_log[0] + 1;
	
					$ins_log = "";
					$ins_log = $ins_log."INSERT INTO trans_login VALUES ( ";
					$ins_log = $ins_log."'$new_seq', '$app_glt', '$_POST[cdatetime]', '$_POST[c_address]' )";
					$q_ins_log = mysql_query($ins_log, $conn) or die("Error Query Insert Trans Detail");
	
					header ("location:../glt/");
					 
					exit;
				}else{	$tipe_alert="1" ;
				}
			}else{	$tipe_alert="2";}
		//}
	} else {$tipe_alert="3";}

}

?>
