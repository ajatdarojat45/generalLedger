<?php
session_start();
if (isset($_SESSION['app_glt']))
{
	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php";
	include "../inc/func_modul.php";
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	
	//  Cek Hak Akses Tombol Add, Edit dan Delete
	include "../inc/inc_aed.php";
	//echo "Add = $tmbl_add - Edit = $tmbl_edit - Delete = $tmbl_del";   
	
	if ($_GET[k_noset_del]) {
		$query_hapus=mysql_query("UPDATE mst_setnrk SET trx_status='0', pemakai='$user_id', tgl_input=now() WHERE noset_id='$_GET[k_noset_del]' AND mcom_id='$company_id'", $conn) or die(mysql_error());
		//$data_hasil = mysql_fetch_array($query_data);		
		if ($query_hapus) {
			echo "<div class='font_header'>( $_GET[k_noset_del] - $_GET[k_nmset_del] ) Berhasil dihapus</div>";
		}
		else {
			echo "<div class='font_header'>( $_GET[k_noset_del] - $_GET[k_nmset_del] ) Gagal dihapus</div>";				
		}
	}
	
	$qry_tmp_del = mysql_query("DELETE FROM tmp_mst_set_nrk WHERE user_id='$user_id'", $conn) or die("Error Delete Temp Neraca ".mysql_error());
	$qry_tmp_del = mysql_query("DELETE FROM tmp_mst_set_nrkt WHERE user_id='$user_id'", $conn) or die("Error Delete Temp Neraca Detail ".mysql_error());
	
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
    
<form name="frm_neraca" method="POST">
    <input type="hidden" id="id-menu" value="<? echo $_GET[id_menu]; ?>">			
    <input type="hidden" id="id-add" value="<? echo $tmbl_add; ?>">	
    <input type="hidden" id="id-edit" value="<? echo $tmbl_edit; ?>">	
    <input type="hidden" id="id-delete" value="<? echo $tmbl_del; ?>">	
    <input type="hidden" id="btn-aksi" value="<? echo "ADD"; ?>">	
    
    <div class="panel panel-primary">
        <div class="panel-heading">    
            <div class="row">
                <div class="col-xs-4"><p class="panel-title"><span class='glyphicon glyphicon-book'></span> TABEL HPP<span class="badge alert-warning" id="badge_kode"><? echo "$kode"; ?></span><span class="badge alert-warning" id="badge_nama"><? echo "$nama"; ?></span></p>
                </div>            
                <div class="col-xs-8 text-right hidden-print" >
                    <button id="btn_add" name="btn_add" type="button" class="btn btn-success" onClick="tambah_neraca('ADD','<? echo $_GET[id_menu];?>')" data-toggle="tooltip" data-placement="bottom" title="Add New" accesskey="a"  disabled='disabled'><span class="glyphicon glyphicon-plus"></span></button> 
                                
                    <button id="btn_print" name="btn_print" type="button" class="btn btn-success" onClick="klik_print()" accesskey="p" data-toggle="tooltip" data-placement="bottom" title="Print"  disabled='disabled'><span class="glyphicon glyphicon-print"></span></button>
                    <button id="btn_find" name="btn_find" type="button" class="btn btn-success" onClick="klik_find()" accesskey="f" data-toggle="tooltip" data-placement="bottom" title="Find"  disabled='disabled'><span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-condensed table-bordered table-hover">
            <tr>
                <th width="2%" colspan="2" class="info" align="center">Editing</th>
                <th width="2%" height="30" class="info">#</th>
                <th width="9%" align="center" class="info">Kode Setting</th>
                <th width="58%" align="center" class="info">Nama Setting</th>
            </tr>
                <?			
                $query_data_neraca = mysql_query("SELECT * FROM mst_setnrk WHERE trx_status='1' AND mcom_id='$company_id' ORDER BY noset_id ASC ", $conn) or die("Error Select Setup Neraca Table ".mysql_error());		
                $jml_rec_data=mysql_num_rows($query_data_neraca);		
                if ($jml_rec_data) {			
                    $no_urut=0;
                    while ($query_hasil = mysql_fetch_array($query_data_neraca)){
                        if ($tmbl_edit==2) {
                            //$view_tmbl_edit="<a href='tabel_neraca_kons_edit.php?k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$noPage&no_urut=$nomor&kd_temp=YA'><img src='../../img/edit.png' width='15' height='18' border='0'></a>";
							$view_tmbl_edit="<button type='button' class='btn-link' name=\"btn_edit_detail\" id=\"edit_$nomor\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit data $data[seq]\" onclick=\"ubah_neraca('$query_hasil[0]','$_GET[id_menu]')\"><span class=\"glyphicon glyphicon-edit\"/></span></button>";
                        } else {
                            $view_tmbl_edit="";
                        }
                        if ($tmbl_del==3) {					
                            //$view_tmbl_del="<a href='?k_noset_del=$query_hasil[1]&k_nmset_del=$query_hasil[2]&page=$noPage&no_urut=$nomor'><img src='../../img/delete.png' width='15' height='18' border='0' onClick=\"return confirm('Hapus Setup Neraca $query_hasil[1]-$query_hasil[2] ?')\"></a>";					
							
							$view_tmbl_del="<button type='button' class='btn-link' onclick=\"klik_d_delete('$query_hasil[1]')\" name=\"btn_delete_detail\" id=\"delete_$$query_hasil[1]\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete data $query_hasil[0]\" ><span class=\"glyphicon glyphicon-trash\"/></span></button>";
							
                        } else {
                            $view_tmbl_del="";
                        }				
                        
                        echo "<tr> 
                        <td align='center'>$view_tmbl_edit</td>
                        <td align='center'>$view_tmbl_del</td>
                        <td >$no_urut.</td>
                        <td align='center'>$query_hasil[1]</td>
                        <td >$query_hasil[2]</td></tr>";
                    $no_urut ++;
                    }
                    echo "<tr><td colspan='5'></td></tr>";
                }
                else{
                    echo "<tr><td colspan='5'>Empty Data Record.</td></tr>";
                }
                ?></table>
        </div>
    </div>
</form>

<!-- awal untuk modal dialog -->
<div id="dialog-akun" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
       	<form name="frmmodul" method="post">
		<div class="modal-content">
			<div class="modal-header alert-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
				<h3 class="modal-title">FORM ADD NEW OR EDIT </h3>
			</div>
			<div class="modal-body"></div>
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
			<div class="modal-footer" id="myfooter-konfirmasi"><button type='button' class="btn-danger"  data-dismiss="modal">OK</button>
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

