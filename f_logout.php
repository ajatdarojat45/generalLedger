<?
session_start();
if (isset($_SESSION['app_glt']))
{	

	include "modules/inc/inc_akses.php";
	
	//--change status login
	$update_status_user = mysql_query("UPDATE mst_login SET mlog_login = '0' WHERE mlog_username = '$_SESSION[app_glt]' ", $conn) or die("Error Query update status user");
	
	//echo "UPDATE mst_login SET mlog_login = '0' WHERE mlog_username = '$_SESSION[app_glt]'";
	mysql_close($conn);
	
	session_destroy();
	session_unset(); 
	
}

include "index.php";

echo "<script language=javascript>
	window.close();
</script>";


?>