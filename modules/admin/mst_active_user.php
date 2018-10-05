<?php
session_start(); 
//print_r($_POST);
//print_r($_GET);
if (isset($_SESSION['app_glt'])) {
	include "../inc/inc_akses.php";
	$jml_usr = mysql_query("SELECT COUNT(*) as jumlah FROM mst_login WHERE mlog_login ='1' AND mlog_status='1' ", $conn) or die("Error Query update status user");
	$data=mysql_fetch_array($jml_usr);
	$jumlah_user=$data[jumlah];
	
	$s_username = "";
	$s_username = $s_username."SELECT mst_login.mlog_username, mst_login.mlog_name, mst_login.mlog_keterangan, ";
	$s_username = $s_username."mst_login.mlog_stamp_date,mst_login.mlog_info ";
	$s_username = $s_username."FROM mst_login ";
	$s_username = $s_username."WHERE mst_login.mlog_login = '1' AND mst_login.mlog_status = '1' ";
	$s_username = $s_username."ORDER BY mst_login.mlog_username ";
	$q_username = mysql_query($s_username, $conn) or die("Error Query s_parentmenu");

?>

<!DOCTYPE html>
<HTML>
<HEAD>
<meta http-equiv="refresh" content="10">
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

<div class="panel panel-primary">
  <div class="panel-heading">
   <!-- <span class="badge"><? //echo $jumlah_user; ?></span> -->
    <h3 class="panel-title"><span class="glyphicon glyphicon-log-in"></span> USER ACTIVE LOGGED IN</h3>
  </div>
  <div class="panel-body">
  
	<table border=0 width="80%" bgcolor='#FFFFFF'>
		<tr>
			<th height="30" class="field_head">No</th>
			<th class="field_head">User Id </th>
			<th class="field_head">Full Name</th>
			<th class="field_head">Description</th>
			<th class="field_head">Last Login</th>
			<th class="field_head">Info</th>
			<th class="field_head">Editing</th>
		</tr>
	<?php
	$i  = 0;
	$no = 0;
	WHILE($fetch_username = mysql_fetch_array($q_username)){
		if ($i==0){			// color table per baris
			echo "<tr class='field_data'>";
			$i++;
		}
		else{
			echo "<tr class='field_data'>";
			$i--;
		}

		$no++;
		$view_tmbl_edit="<a href='mst_active_user_detail.php?id_us=$fetch_username[0]'><img src='../../img/edit.png'></img>";
		echo "
			<td>$no</td>
			<td>$fetch_username[0]</td>
			<td>$fetch_username[1]</td>
			<td>$fetch_username[2]</td>
			<td>$fetch_username[3]</td>
			<td>$fetch_username[4]</td>
			<td>$view_tmbl_edit</td>
		</tr>";
	}
	?>
	</table>
	</div>
</div>

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
<?
} else { header("location:/glt/no_akses.htm"); }
?>