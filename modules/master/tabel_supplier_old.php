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
	include "../inc/inc_aed.php";
	
	$table_sup="mst_sup_".$company_id;
	
	if ($_POST[k_sup_del]) {
		$query_hapus=mysql_query("UPDATE $table_sup SET sup_status='0', pemakai='$user_id', tgl_input=now() WHERE kd_sup='$_POST[k_sup_del]' AND mcom_id='$company_id'", $conn) or die(mysql_error());
		if ($query_hapus) {
			echo "<script> ($_POST[k_sup_del]) Berhasil dihapus";
		}
		else {
			echo "( $_POST[k_sup_del] ) Gagal dihapus";				
		}
	}
	
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
<form name="frm_supplier" method="POST">
	<input type="hidden" id="id-menu" value="<? echo $_GET[id_menu]; ?>">			
    <input type="hidden" id="id-add" value="<? echo $tmbl_add; ?>">	
    <input type="hidden" id="id-edit" value="<? echo $tmbl_edit; ?>">	
    <input type="hidden" id="id-delete" value="<? echo $tmbl_del; ?>">	
    <input type="hidden" id="btn-aksi" value="<? echo "ADD"; ?>">
    	    
    <div class="panel panel-primary">
        <div class="panel-heading">    
            <div class="row">
                <div class="col-xs-4"><p class="panel-title"><span class='glyphicon glyphicon-book'></span> MASTER SUPPLIER <span class="badge alert-warning" id="badge_kode"><? echo "$kode"; ?></span><span class="badge alert-warning" id="badge_nama"><? echo "$nama"; ?></span></p>
                </div>            
                <div class="col-xs-8 text-right hidden-print" >
                    <button id="btn_add" name="btn_add" type="button" class="btn btn-success" onClick="tambah_supplier('ADD','<? echo $_GET[id_menu];?>')" data-toggle="tooltip" data-placement="bottom" title="Add New" accesskey="a" disabled='disabled'><span class="glyphicon glyphicon-plus"></span></button> 
                                
                    <button id="btn_print" name="btn_print" type="button" class="btn btn-success" onClick="klik_print()" accesskey="p" data-toggle="tooltip" data-placement="bottom" title="Print"  disabled='disabled'><span class="glyphicon glyphicon-print"></span></button>
                    <button id="btn_find" name="btn_find" type="button" class="btn btn-success" onClick="klik_find()" accesskey="f" data-toggle="tooltip" data-placement="bottom" title="Find"  disabled='disabled'><span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div id="area-pesan">
            </div>
            <div id="area-input">
            </div>

<table class="table table-condensed table-bordered table-hover">
	<tr >
		<th width="2%" colspan="2" align="center" class="info hidden-print">Editing</th>
		<th width="2%" height="30" class="info">#</th>
		<th width="5%" height="30" class="info text-center">Kode Supplier</th>
		<th width="58%" align="center" class="info">Nama Supplier</th>
	</tr>
		<?	
		$table_sup="mst_sup_".$company_id;
		
		$query= mysql_query("SELECT * FROM $table_sup WHERE sup_status='1' ORDER BY kd_sup ASC ", $conn) or die("Error Select Supplier Table ".mysql_error());		
		$jml_rec_data=mysql_num_rows($query);		
		if ($jml_rec_data>0) {			
			$no_urut=1;
			while ($query_hasil = mysql_fetch_array($query)){
				if ($tmbl_edit==2) {
					//$view_tmbl_edit="<a href='tabel_supplier_edit.php?k_sup=$query_hasil[0]&id_menu=$_GET[id_menu]&page=$noPage&no_urut=$nomor'><img src='../../img/edit.png' width='15' height='18' border='0'></a>";
					$view_tmbl_edit="<button type='button' class='btn-link invisible-print' name=\"btn_edit_detail\" id=\"edit_$nomor\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit data $query_hasil[kd_sup]\" onclick=\"ubah_supplier('$query_hasil[kd_sup]')\"><span class=\"glyphicon glyphicon-edit\"/></span></button>";
				} else {
					$view_tmbl_edit="";
				}
				if ($tmbl_del==3) {					
					//$view_tmbl_del="<a href='tabel_supplier.php?k_sup_del=$query_hasil[0]&n_sup_del=$query_hasil[1]&page=$noPage&no_urut=$nomor'><img src='../../img/delete.png' width='15' height='18' border='0' onClick=\"return confirm('Hapus Akun $query_hasil[0]-$query_hasil[1] ?')\"></a>";					
					$view_tmbl_del="<button type='button' class='btn-link' onclick=\"klik_d_delete('$query_hasil[kd_sup]')\" name=\"btn_delete_detail\" id=\"delete_$query_hasil[kd_sup]\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete data $query_hasil[kd_sup]\" ><span class=\"glyphicon glyphicon-trash\"/></span></button>";
				} else {
					$view_tmbl_del="";
				}
								
			echo "<tr> 
				<td class='hidden-print' >$view_tmbl_edit</td>
				<td class='hidden-print'>$view_tmbl_del</td>
				<td>$no_urut.</td>
				<td align='center'>$query_hasil[kd_sup]</td>
				<td width='60%'>$query_hasil[nm_sup]</td></tr>";
			$no_urut ++;
			}
		}
		else{
			echo "<tr><td colspan='5'>Empty Data Record.</td></tr>";
		}
		?>
</table>
</form>
	</div>
</div>

<!-- awal untuk modal dialog -->
<div id="dialog-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
       	<form name="frmmodul" id="frmmodul" method="post">
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
	<script src="aksi_supplier.js"></script>
    
</BODY>
</HTML>
<!-- session -->
<?
	if($_POST[btn_add]){
	}
}
else
{
	echo"<title>Manage Care</title>
				<link href=\"../../style\style.css\" rel=stylesheet>";
	echo "<center>";
	echo "<h3>Acess Denied</h3>";
	echo "Please <a href=../../index.php target=$_self>[Login]</a> First<br>";
	echo "</center>";

}

?>