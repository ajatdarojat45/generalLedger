<?php
session_start();

if (isset($_SESSION['app_glt']))
{
	print_r($_POST);
	print_r($_GET);

	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php";
	include "../inc/func_modul.php";
	
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	
	//  Cek Hak Akses Tombol Add, Edit dan Delete
	include "../inc/inc_aed.php";
	
	
	if ($_GET[k_acc_del]) {
		$query_hapus=mysql_query("UPDATE ".$table_akun." SET acc_status='0', pemakai='$user_id', tgl_input=now() WHERE acc='$_GET[k_acc_del]' AND mcom_id='$company_id'", $conn) or die(mysql_error());
		//$data_hasil = mysql_fetch_array($query_data);		
		if ($query_hapus) {
			echo "<div class='font_header'>( $_GET[k_acc_del] - $_GET[n_acc_del] ) Berhasil dihapus</div>";
		}
		else {
			echo "<div class='font_header'>( $_GET[k_acc_del] - $_GET[n_acc_del] ) Gagal dihapus</div>";				
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
<BODY>
	<input type="hidden" id="id-menu" value="<? echo $_GET[id_menu]; ?>">			
	<input type="hidden" id="id-add" value="<? echo $tmbl_add; ?>">	
	<input type="hidden" id="id-edit" value="<? echo $tmbl_edit; ?>">	
	<input type="hidden" id="id-delete" value="<? echo $tmbl_del; ?>">	

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

<form name="frm_akun" id="frm-akun" method="POST">
<!-- <div class="container">   -->
<div class="panel panel-primary">
  <div class="panel-heading">    
		<div class="row">
			<div class="col-xs-4"><p class="panel-title"><span class='glyphicon glyphicon-inbox'></span> <? echo $_GET[nm_menu];
			?> <span class="badge alert-warning" id="tampil_badge"><? echo "$jumdata"; ?></span> <span class="badge alert-warning" id="tampil_badge_cf"><? echo "$data_cf"; ?></span></p></div>
			<div class="col-xs-8 text-right hidden-print" >
				<button id="btn_add" name="btn_add" type="button" class="btn btn-success" onClick="tambah_akun('ADD')" data-toggle="tooltip" data-placement="bottom" title="Add New" accesskey="a" ><span class="glyphicon glyphicon-plus"></span></button> 
				<button id="btn_print" name="btn_print" type="button" class="btn btn-success" onClick="klik_print()" accesskey="p" data-toggle="tooltip" data-placement="bottom" title="Print"><span class="glyphicon glyphicon-print"></span></button>
				<button id="btn_find" name="btn_find" type="button" class="btn btn-success" onClick="klik_find()" accesskey="f" data-toggle="tooltip" data-placement="bottom" title="Find Transaction"><span class="glyphicon glyphicon-search"></span></button>			
			</div>
		</div>
	</div>
  <div class="panel-body">
	<? 
		$table_akun="mst_akun_".$company_id;
			//echo "Company Id: -$company_id-";
			//-----SHOW--------------
			$dataPerPage = 300;			
			
			if(isset($_GET['page']))
			{
				$noPage = $_GET['page'];
				$no_urut=$_GET['no_urut'];
			} 
			else {$noPage = 1;
				$no_urut = 1;
			}
				 
			$offset = ($noPage - 1) * $dataPerPage;
		  //--Find Count Data
		$count_categ = "";
		$count_categ = $count_categ."SELECT COUNT(*) ";
		$count_categ = $count_categ."FROM ".$table_akun." WHERE acc_status='1' AND mcom_id='$company_id' ORDER BY acc ";
		
		$qcount_categ = mysql_query($count_categ, $conn) or die("Error Query count_categ");
		$ftcount_categ = mysql_fetch_array($qcount_categ);
		
		$jumData = $ftcount_categ[0];
		//--End Find Count Data
		
		//-- include "../../include/paging_page.php";
		// menentukan jumlah halaman yang muncul berdasarkan jumlah semua data
		
		$jumPage = ceil($jumData/$dataPerPage);
		
		if ($jumPage < $noPage) {
			$noPage = 1;
			$no_urut= 1;
			$offset = ($noPage - 1) * $dataPerPage;
		}
		//echo "Jumlah:$jumPage";
		// menampilkan link previous
		$no_next=0;
		$no_prev=((($noPage-1)*$dataPerPage)-($dataPerPage-1));
		
		echo "<ul class=\"pagination pagination-sm\">";
		
		if ($noPage > 1) { echo  "<li><a href=\"?page=".($noPage-1)."&id_menu=$_GET[id_menu]&no_urut=$no_prev\">PREV</a></li>"; }
		
		if ($jumPage > 1) {		
			// memunculkan nomor halaman dan linknya
			for($page = 1; $page <= $jumPage; $page++)
			{		
				if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) 
				 {        
					if (($showPage == 1) && ($page != 1 ) ) echo "<li class='disabled'><a href='#'> ... </a></li>"; 
					if (($showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "<li class='disabled'><a href='#'> ... </a></li>" ;
					if ($page == $noPage) {echo "<li class=\"active\"><a href='#'> ".$page."&nbsp;"."</a></li> "; $no_next=((($page+1)*$dataPerPage)-($dataPerPage-1)); }
					else {echo " <li><a href=\"?page=$page&id_menu=$_GET[id_menu]&no_urut=".(($page*$dataPerPage)-($dataPerPage-1))."\">$page</a></li> ";}
					$showPage = $page;
				}
			}
		}
		// menampilkan link next
		
		if ($noPage < $jumPage) { echo " <li><a href=\"?page=".($noPage+1)."&id_menu=$_GET[id_menu]&no_urut=$no_next\">NEXT</a></li> "; }
		echo "</ul>";
		
		?>
		<div class="table">
		<table class="table table-bordered table-hover table-condensed" width="100%" >
			<tr>
				<th width="4" colspan="2" class="info">Editing</th>
				<th width="3%" height="30" class="info">#</th>
				<th width="12%" height="30" class="info">Kode Perkiraan</th>
				<th width="40%" class="info">Nama Perkiraan</th>
				<th width="3%" class="info">LVL</th>
				<th width="2%" class="info">D/K</th>
				<th width="3%" class="info">N/R</th>
				<th width="5%" class="info">HPP</th>
				<th width="5%" class="info">PUSAT</th>				
			</tr>
		<?			
		$query_data_akun = mysql_query("SELECT * FROM ".$table_akun." WHERE acc_status='1' AND mcom_id='$company_id' ORDER BY acc ASC LIMIT $offset, $dataPerPage", $conn) or die("Error Select Akun Table ".mysql_error());		
		$jml_rec_data=mysql_num_rows($query_data_akun);		
		if ($jml_rec_data) {			
			while ($query_hasil = mysql_fetch_array($query_data_akun)){
				$nomor=$no_next-$dataPerPage;
				if ($tmbl_edit==2) {
					$view_tmbl_edit="<a href='#' onclick=\"ubah_akun('$query_hasil[0]')\" id=\"$query_hasil[0]\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit data akun\"><span class=\"glyphicon glyphicon-edit\"/></a>";
					 //<img src=\"../../bootstrap/glyphicons/png/glyphicons_150_edit.png\"
				} else {
					$view_tmbl_edit="";
				}
				if ($tmbl_del==3) {					
					$view_tmbl_del="<a href='tabel_akun.php?k_acc_del=$query_hasil[0]&n_acc_del=$query_hasil[1]&page=$noPage&no_urut=$nomor' data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Hapus akun\" onClick=\"return confirm('Hapus Akun $query_hasil[0]-$query_hasil[1]')\"><span class=\"glyphicon glyphicon-trash\"/></a>";					
					//<img src=\"../../bootstrap/glyphicons/png/glyphicons_016_bin.png\"/>
				} else {
					$view_tmbl_del="";
				}
				
				
				echo "<tr class='field_data'> 
				<td width='2%'>$view_tmbl_edit</td>
				<td width='2%'>$view_tmbl_del</td>
				<td width='3%' height='18' align='left'>$no_urut.</td>
				<td width='9%' height='18' align='left'>$query_hasil[acc]</td>
				<td width='58%'>$query_hasil[nmp]</td>
				<td width='2%' align='center'>$query_hasil[12]</td>
				<td width='4%' align='center'>$query_hasil[3]</td>
				<td width='3%' align='center'>$query_hasil[2]</td>
				<td width='3%' align='center'>$query_hasil[8]</td>
				<td width='4%' align='center'>$query_hasil[11]</td>
				</tr>";
			$no_urut ++;
			}
			echo "<tr><td colspan='10' class='field_head'></td></tr>";
		}
		else{
			echo "<tr class='field_data'><td colspan='10'>Empty Data Record.</td></tr>";
		}
		?>
		</table>		
		</div>		
<?
 //--Find Count Data
		// menampilkan link previous
		$no_next=0;
		$no_prev=((($noPage-1)*$dataPerPage)-($dataPerPage-1));
		?>
		<ul class="pagination pagination-sm">
		<?
		if ($noPage > 1) { echo  "<li><a href=\"?page=".($noPage-1)."&id_menu=$_GET[id_menu]&no_urut=$no_prev\">PREV</a></li>"; }
		
		if ($jumPage > 1) {		
			// memunculkan nomor halaman dan linknya
			for($page = 1; $page <= $jumPage; $page++)
			{		
				if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) 
				 {        
					if (($showPage == 1) && ($page != 1)) echo "<li class='disabled'><a href='#'> ... </a></li>"; 
					if (($showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "<li class='disabled'><a href='#'> ... </a></li>" ;
					if ($page == $noPage) {echo "<li class=\"active\"><a href='#'> ".$page."&nbsp;"."</a></li> "; $no_next=((($page+1)*$dataPerPage)-($dataPerPage-1)); }
					else {echo " <li><a href=\"?page=$page&id_menu=$_GET[id_menu]&no_urut=".(($page*$dataPerPage)-($dataPerPage-1))."\">$page</a></li> ";}
					$showPage = $page;
				}
			}
		}
		// menampilkan link next
		
		if ($noPage < $jumPage) { echo " <li><a href=\"?page=".($noPage+1)."&id_menu=$_GET[id_menu]&no_urut=$no_next\">NEXT</a></li> "; }

		?>
		 </ul>	
  </div>
</div>
</form>

<!-- awal untuk modal dialog -->
<div id="dialog-akun" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
       	<form name="frmmodul" method="post">
		<div class="modal-content">
			<div class="modal-header alert-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
				<h3 class="modal-title">FORM ADD NEW OR EDIT </h3>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer alert-warning">
            </div>
		</div>
        </form>
	</div>
</div>

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../../js/jquery-1.11.0.min.js"></script>
    <script src="../../bootstrap-3/js/bootstrap.min.js"></script>
	<script src="aksi_akun.js"></script>

</BODY>
</HTML>
<!-- session -->
<?	

	//echo "MENU= $_GET[id_menu]  Add = $tmbl_add -  Edit = $tmbl_edit -  Delete = $tmbl_del  ";   

}
else
{
	include "/glt/f_noakses.php";

}
?>

