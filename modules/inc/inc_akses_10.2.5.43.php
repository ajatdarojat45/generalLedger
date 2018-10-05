<?
	$host = "10.2.5.69";
	$user = "gltop";
	$pass = "3280534";
	$db = "gltempo";
	$user_id="";
	$user_name="";
	$company_id="";
	$company_name="";
	$aktif_tgl="";	// Active date 
	$sys_tgl="";	// System date
	$ganti_pt="";
	$judul_form="";
	$nama_sistem="General Ledger Tempo";
	
	
	$conn = mysql_connect($host, $user, $pass) or die("
			<center>
				<IMG SRC=\"../../images/invalid_user.gif\" WIDTH=\"500\" HEIGHT=\"300\" BORDER=0>
			</center>
		");		
	
	mysql_select_db($db, $conn)
		or die ("
			<center>
				<IMG SRC=\"../../images/invalid_database.gif\" WIDTH=\"500\" HEIGHT=\"300\" BORDER=0>
			</center>
		");

	//======General Variabel V.1====================
	$query_data=mysql_query("SELECT now()", $conn) or die(mysql_error());
	$data_hasil = mysql_fetch_array($query_data);
	
	$_POST[cdate] = substr($data_hasil[0],0,10);
	$_POST[cdatetime] = $data_hasil[0];
	$_POST[mainframe] = "frame_utama";
	//$_POST[c_address] = gethostbyaddr($REMOTE_ADDR);	
	$_POST[c_address] = $_SERVER['REMOTE_ADDR'];	
	
	$user_id=$_SESSION[app_glt];
	
	$query_data=mysql_query("SELECT mcom_id_last,mlog_name,aktif_tgl,mlog_info FROM mst_login WHERE mlog_username = '$user_id' ", $conn) or die(mysql_error());
	$data_hasil = mysql_fetch_array($query_data);
	
	$user_name=$data_hasil[1];
	$aktif_tgl=$data_hasil[2];	
	$user_log=$data_hasil[3];	
	
	$query_data_pt = mysql_query("SELECT mgc_username,mcom_id,mcom_periode FROM mst_granting_company WHERE mcom_id = '$data_hasil[0]' AND mgc_username='$user_id' ", $conn) or die(mysql_error());
	
	$query_data = mysql_query("SELECT mcom_id,mcom_company_name,sys_tgl,acc_kas,acc_bank,acc_bs FROM mst_company WHERE mcom_id = '$data_hasil[0]' ", $conn) or die(mysql_error());
	
	$data_hasil = mysql_fetch_array($query_data);

	$data_hasil_pt = mysql_fetch_array($query_data_pt);	
	
	$company_id="$data_hasil[0]";
	$company_name="$data_hasil[1]";
	
	$acc_pusat_kas=$data_hasil[3];
	$acc_pusat_bank=$data_hasil[4];
	$acc_pusat_bs=$data_hasil[5];
	
	$sys_tgl=$data_hasil[2];	
	$ganti_pt=$data_hasil_pt[2];	
	//$aktif_tgl=date_create("$data_hasil[2]");	
	//==============================================

	//--End System Date======
	
	if ($_POST[c_address]<>$user_log && !empty($user_id) ) {
		//User dipaksa logout karena ada user yg sama login dari lokasi yg berbeda
		
		echo "<script> alert('$user_id aktif dari lokasi lain, anda harus login ulang !'); </script>";
		
		session_destroy();
		session_unset(); 
		
		//header("location:../../glt/") ;	

		echo "<script language=javascript>window.close();</script>";
		
		exit;	
	}

?>