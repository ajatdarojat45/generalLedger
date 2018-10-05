<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])){
	
	include "../inc/inc_akses.php";
	
	//  Cek Hak Akses Tombol Add, Edit dan Delete
	include "../inc/inc_aed.php";

?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet">
    <link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet">
    <link href="../../style/style_utama.css" rel="stylesheet">

   <![if lt IE 9]>
      <script src="bootstrap/html5shiv.js"></script>
      <script src="bootstrap/respond.min.js"></script>
    <![endif]>
	
</HEAD>
<body id="editing_setup">
<div class="form-inline">
	<?
	if ($_GET[flg]=="D") { 
		
	?>
    <div class="row">
        <div class="col-md-1">
            <div class="input-group hidden-print">
              <div class="input-group-btn">
                <button type="button" class="btn btn-danger dropdown-toggle btn-sm" data-toggle="dropdown">Pilih PT <span class="caret"></span></button>
                <ul class="dropdown-menu">
                  <?
                    $que_pt = mysql_query("SELECT mcom_id,mcom_company_name FROM mst_company WHERE mcom_status='1' ORDER BY mcom_id ASC ", $conn) or die("Error Select Query Company");
                    while ($data_pt = mysql_fetch_array($que_pt)){
                        echo "<li><a href=\"tampil_akun.php?level=$_GET[level]&nm_lvl=$_GET[nm_lvl]&pt=$data_pt[mcom_id]&nm_pt=$data_pt[1]&noset=$_GET[noset]&nourut=$_GET[nourut]\" target='frame_pilih' onclick='klik_akun_pt($data_pt[mcom_id],$_GET[nourut])'>$data_pt[1]</a></li>";
                    }
                    ?>
                </ul>
              </div><!-- /btn-group -->
              <!--<input type="text" class="form-control text-right" size="5" value="<? //echo $_GET[nm_pt]; ?>" readonly>-->
            </div><!-- /input-group -->
        </div>
        <div class="col-md-1">    	
            <div class="input-group hidden-print">            
              <div class="input-group-btn">
                <button type="button" class="btn btn-danger dropdown-toggle btn-sm" data-toggle="dropdown">Filter Level <span class="caret"></span></button>
                <ul class="dropdown-menu">
                  <?
                        echo "<li><a href=\"tampil_akun.php?level=&nm_lvl=ALL&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&noset=$_GET[noset]&nourut=$_GET[nourut]\" target='frame_pilih'>Show all level</a></li>";                        
                        echo "<li><a href=\"tampil_akun.php?level=1&nm_lvl=Level&nbsp;1&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&noset=$_GET[noset]&nourut=$_GET[nourut]\" target='frame_pilih'>Level 1</a></li>";
                        echo "<li><a href=\"tampil_akun.php?level=2&nm_lvl=Level&nbsp;2&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&noset=$_GET[noset]&nourut=$_GET[nourut]\" target='frame_pilih'>Level 2</a></li>";
                        echo "<li><a href=\"tampil_akun.php?level=3&nm_lvl=Level&nbsp;3&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&noset=$_GET[noset]&nourut=$_GET[nourut]\" target='frame_pilih'>Level 3</a></li>";
                        echo "<li><a href=\"tampil_akun.php?level=4&nm_lvl=Level&nbsp;4&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&noset=$_GET[noset]&nourut=$_GET[nourut]\" target='frame_pilih'>Level 4</a></li>";
                        echo "<li><a href=\"tampil_akun.php?level=5&nm_lvl=Level&nbsp;5&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&noset=$_GET[noset]&nourut=$_GET[nourut]\" target='frame_pilih'>Level 5</a></li>";
                    ?>
                </ul>
              </div><!-- /btn-group -->
              <!--<input type="text" class="form-control text-right" size="5" value="<? //echo $_GET[nm_lvl]; ?>" readonly>-->
            </div><!-- /input-group -->
        </div>
    </div>
    <br>
	<?
	} else { ?> <b>Daftar Setup :</b> <? }
	
	$query_data = mysql_query("DELETE FROM tmp_mst_set_nrkt_setup WHERE user_id='$user_id'", $conn) or die("Error Del Temp Detail ".mysql_error());		
	$query_data = mysql_query("INSERT INTO tmp_mst_set_nrkt_setup (SELECT * FROM tmp_mst_set_nrkt WHERE noset_id='$_GET[noset]'  AND trx_status='1' AND user_id='$user_id' AND nourut_id='$_GET[nourut]') ", $conn) or die("Error Create Select Temp Neraca Kons Detail ".mysql_error());		

	if ($_GET[flg]=="D") { 	
		$frame1="<iframe src='tampil_akun.php?noset=$_GET[noset]&level=$_GET[level]&nm_lvl=$_GET[nm_lvl]&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]&nourut=$_GET[nourut]' id='frame_pilih' name='frame_pilih' width='100%' height='70%' frameborder='1' ></iframe>";
		$frame2="<iframe src='tampil_setup_akun.php?noset=$_GET[noset]&nourut=$_GET[nourut]&level=$_GET[level]&nm_lvl=$_GET[nm_lvl]&pt=$_GET[pt]&nm_pt=$_GET[nm_pt]' id='frame_hasil' name='frame_hasil' width='100%' height='80%' frameborder='1'></iframe>";
	} else {
		$frame1="<iframe src='tampil_head.php?noset=$_GET[noset]&nourut=$_GET[nourut]' id='frame_pilih' name='frame_pilih' width='100%' height='70%' frameborder='1' ></iframe>";
		$frame2="<iframe src='tampil_setup_head.php?noset=$_GET[noset]&nourut=$_GET[nourut]' id='frame_hasil' name='frame_hasil' width='100%' height='80%' frameborder='1'></iframe>";
	}
	
	echo $frame1 ;
	  
	//<br><br><b>Isi setup  : </b>
	?>
	
	<br><br><b>Isi setup  : </b>
	
	<?
		echo $frame2 ;
	?>	
</div>

</body>
</html>
<?
}
else
{
	echo"<title>Manage Care</title>
				<link href=\"../../style\style.css\" rel=stylesheet>";
	echo "<center>";
	echo "<h3>Acess Denied</h3>";
	echo "Please <a href=../../home_login.php target=$_self>[Login]</a> First<br>";
	echo "</center>";

}
?>
