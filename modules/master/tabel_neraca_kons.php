<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt']))
{
	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php";
	include "../inc/func_modul.php";
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	
	//  Cek Hak Akses Tombol Add, Edit dan Delete
	include "../inc/inc_aed.php";
	//echo "Add = $tmbl_add - Edit = $tmbl_edit - Delete = $tmbl_del";  
	
	
	$mst_setup="mst_setnrk";
	
	$setup="mst_set_nrk";
	$setup_d="mst_set_nrkt";
	
	$tmp_set="tmp_mst_set_nrk";
	$tmp_set_d="tmp_mst_set_nrkt";
	
	$bck_set="mst_set_nrk_bck";
	$bck_set_d="mst_set_nrkt_bck";
	 
	$alert="";
	if ($_POST[k_noset_del]) {
		$query_hapus=mysql_query("UPDATE $mst_setup SET trx_status='0', pemakai='$user_id', tgl_input=now() WHERE noset_id='$_POST[k_noset_del]' AND mcom_id='$company_id'", $conn) or die(mysql_error());
		//$data_hasil = mysql_fetch_array($query_data);		
		if ($query_hapus) {
			$alert="<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>x</button>Kode setup $_POST[k_noset_del] Berhasil dihapus</div>";
		}
		else {
			$alert="<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert'>x</button>Kode setup $_POST[k_noset_del] Gagal dihapus</div>";
		}
	}
	
	if ($_POST[btn_save_neraca]=="Tambah") {
		$que=mysql_query("SELECT * FROM $mst_setup WHERE nmset='$_POST[kd_nmset]' AND noset_id<>'$_POST[kd_noset]' AND mcom_id='$company_id'", $conn) or die("Error Select Neraca ".mysql_error());
		$row_already = mysql_num_rows($que);
		if ($row_already) {
			$alert="<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert'>x</button> Nama setup neraca sudah ada ! </div>";
		} else {
			$que=mysql_query("INSERT INTO $mst_setup (mcom_id,nmset,trx_status,pemakai,tgl_input) values ('$company_id','$_POST[kd_nmset]','1','$user_id',now())", $conn) or die("Error Insert Neraca ".mysql_error());
			$alert="<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>x</button> Penambahan setup baru berhasil disimpan. </div>";
		}
	}	

	if ($_POST[btn_save_neraca]=="Ubah") {
		$que=mysql_query("SELECT * FROM $mst_setup WHERE nmset='$_POST[kd_nmset]' AND noset_id<>'$_POST[kd_noset]' AND mcom_id='$company_id'", $conn) or die("Error Select Neraca ".mysql_error());
		$row_already = mysql_num_rows($que);
		if ($row_already) {
			$alert="<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert'>x</button> Nama setup neraca sudah ada ! </div>";
		} else {
			$que=mysql_query("UPDATE $mst_setup SET nmset='$_POST[kd_nmset]', pemakai='$user_id', tgl_input=now() WHERE mcom_id='$company_id' AND noset_id='$_POST[kd_noset]'", $conn) or die("Error Update Neraca ".mysql_error());
			$alert="<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>x</button> Ubah setup baru berhasil disimpan. </div>";
		}		
	}	

	if ($_POST[btn_save_setup]=="Ubah") {
		// Simpan di tabel backup dahulu
		$que_nrk_bck=mysql_query("INSERT INTO $bck_set SELECT * FROM $tmp_set WHERE noset_id='$_POST[kd_noset]' AND mcom_id='$company_id' AND user_id='$user_id'", $conn) or die("Error Select Temp Neraca ".mysql_error());
		
		$que_nrkt_bck=mysql_query("INSERT INTO $bck_set_d SELECT * FROM $tmp_set_d WHERE noset_id='$_POST[kd_noset]' AND mcom_id='$company_id' AND user_id='$user_id'", $conn) or die("Error Select Temp Neraca ".mysql_error());
		
		$que_nrk_del=mysql_query("DELETE FROM $setup WHERE noset_id='$_POST[kd_noset]' AND mcom_id='$company_id'", $conn) or die("Error Delete NRK Neraca ".mysql_error());
		$que_nrkt_del=mysql_query("DELETE FROM $setup_d WHERE noset_id='$_POST[kd_noset]' AND mcom_id='$company_id'", $conn) or die("Error Delete NRKT Neraca ".mysql_error());

		$que_nrk=mysql_query("INSERT INTO $setup SELECT * FROM $tmp_set WHERE noset_id='$_POST[kd_noset]' AND mcom_id='$company_id' AND user_id='$user_id'", $conn) or die("Error Select Temp Neraca ".mysql_error());
		$que_nrkt=mysql_query("INSERT INTO $setup_d SELECT * FROM $tmp_set_d WHERE noset_id='$_POST[kd_noset]' AND mcom_id='$company_id' AND user_id='$user_id'", $conn) or die("Error Select Temp Neraca ".mysql_error());
		$alert="<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>x</button> Ubah setup berhasil disimpan. </div>";
		
	}	

	$qry_tmp_del = mysql_query("DELETE FROM $tmp_set WHERE user_id='$user_id'", $conn) or die("Error Delete Temp Neraca ".mysql_error());
	$qry_tmp_del = mysql_query("DELETE FROM $tmp_set_d WHERE user_id='$user_id'", $conn) or die("Error Delete Temp Neraca Detail ".mysql_error());
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
<BODY onLoad="tombol_reset();">
    <!------- FORM HEADER NAVIGATION ----------->
    <div class="navbar navbar-default navbar-form" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>		
                <?
                    include "../inc/inc_top_head.php";
                ?>
                </li>
          </ul>
        </div>
    </div>
    
<form name="frm_neraca" id="frm_neraca" method="POST">
    <input type="hidden" id="id-menu" value="<? echo $_GET[id_menu]; ?>">			
    <input type="hidden" id="id-add" value="<? echo $tmbl_add; ?>">	
    <input type="hidden" id="id-edit" value="<? echo $tmbl_edit; ?>">	
    <input type="hidden" id="id-delete" value="<? echo $tmbl_del; ?>">	
    <input type="hidden" id="btn-aksi" value="<? echo "ADD"; ?>">	
    
    <div class="panel panel-primary">
        <div class="panel-heading">    
            <div class="row">
                <div class="col-xs-4"><p class="panel-title"><span class='glyphicon glyphicon-book'></span> TABEL NERACA KONSOLIDASI <span class="badge alert-warning" id="badge_kode"><? echo "$kode"; ?></span><span class="badge alert-warning" id="badge_nama"><? echo "$nama"; ?></span></p>
                </div>            
                <div class="col-xs-8 text-right hidden-print" >

                    <button id="btn_add" name="btn_add" type="button" class="btn btn-success" onClick="tambah_neraca('ADD','<? echo $_GET[id_menu];?>')" data-toggle="tooltip" data-placement="bottom" title="Add New" accesskey="a"  disabled='disabled'><span class="glyphicon glyphicon-plus"></span></button> 
                                
                    <button id="btn_print" name="btn_print" type="button" class="btn btn-success" onClick="klik_print()" accesskey="p" data-toggle="tooltip" data-placement="bottom" title="Print"  disabled='disabled'><span class="glyphicon glyphicon-print"></span></button>

                </div>
            </div>
        </div>
        <div class="panel-body">
        	<div id="area-cari-data"><? echo $alert; ?>
            </div>
        	<div id="area-input">
            </div>
            <table class="table table-condensed table-bordered table-hover">
            <tr>
                <th width="1%" colspan="3" class="info hidden-print" align="center">Editing</th>
                <th width="2%" height="30" class="info">#</th>
                <th width="9%" align="center" class="info">Kode Setting</th>
                <th width="58%" align="center" class="info">Nama Setting</th>
            </tr>
                <?			
                $query_data_neraca = mysql_query("SELECT * FROM $mst_setup WHERE trx_status='1' AND mcom_id='$company_id' ORDER BY noset_id ASC ", $conn) or die("Error Select Setup Neraca Table ".mysql_error());		
                $jml_rec_data=mysql_num_rows($query_data_neraca);		
                if ($jml_rec_data) {			
                    $no_urut=1;
                    while ($query_hasil = mysql_fetch_array($query_data_neraca)){
                        if ($tmbl_edit==2) {
                            $view_tmbl_edit="<button type='button' class='btn-link' name=\"btn_edit_detail\" id=\"edit_$nomor\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit data $query_hasil[1]\" onclick=\"ubah_neraca('$query_hasil[1]','$query_hasil[2]')\"><span class=\"glyphicon glyphicon-edit\"/></span></button>";
							$view_tmbl_setup="<button type='button' class='btn-link' name=\"btn_edit_detail\" id=\"edit_$nomor\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit data setup $query_hasil[1]\" onclick=\"ubah_setup('$query_hasil[1]','$_GET[id_menu]')\"><span class=\"glyphicon glyphicon-cog\"></span></button>";
                        } else {
                            $view_tmbl_edit="";
                        }
                        if ($tmbl_del==3) {					
                            $view_tmbl_del="<button type='button' class='btn-link' onclick=\"hapus_neraca('$query_hasil[1]')\" name=\"btn_delete_detail\" id=\"delete_$$query_hasil[1]\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete data $query_hasil[0]\" ><span class=\"glyphicon glyphicon-trash\"/></span></button>";
							
                        } else {
                            $view_tmbl_del="";
                        }				
                        
                        echo "<tr> 
                        <td class='hidden-print' align='center'>$view_tmbl_edit</td>
                        <td class='hidden-print' align='center'>$view_tmbl_del</td>
                        <td class='hidden-print' align='center'>$view_tmbl_setup</td>
                        <td >$no_urut.</td>
                        <td align='center'>$query_hasil[1]</td>
                        <td >$query_hasil[2]</td></tr>";
                    $no_urut ++;
                    }
                    //echo "<tr><td colspan='5'></td></tr>";
                }
                else{
                    echo "<tr><td colspan='5'>Empty Data Record.</td></tr>";
                }
                ?></table>
        </div>
    </div>
</form>

<!-- awal untuk modal dialog -->
<div id="dialog-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
       	<form name="frmmodul" method="post">
		<div class="modal-content">
			<div class="modal-header alert-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
				<h3 class="modal-title" id="mytitle">FORM ADD NEW OR EDIT </h3>
			</div>
			<div class="modal-body" id="mybody"></div>
			<div class="modal-footer alert-warning" id="myfooter">
            </div>
		</div>
        </form>
	</div>
</div>

<div id="dialog-konfirmasi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content alert-warning">
		<form name="frmkonfirmasi" id="frmkonfirmasi" method="post">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
				<h3 class="modal-title" id="myModalLabel2">KONFIRMASI</h3>
			</div>			
			<div class="modal-body" id="mybody-konfirmasi"></div>
			<div class="modal-footer" id="myfooter-konfirmasi"><button type='button' class="btn-danger" data-dismiss="modal">OK</button>
            </div>
		</form>
		</div>
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
	<script src="aksi_neraca_k.js"></script>

</BODY>
</HTML>
<!-- session -->
<?	
}
else { header("location:/glt/no_akses.htm"); }
?>

