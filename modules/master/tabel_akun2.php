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
	
	$table_akun="mst_akun_".$company_id;
	
	if (isset($_POST[txt_kode])) { 
		$kode=$_POST[txt_kode]; 		
	} else { 
		if (isset($_GET[txt_kode])) { $kode=$_GET[txt_kode]; } else { $kode=""; }
	}
	
	if (isset($_POST[txt_nama])) { 
		$nama=$_POST[txt_nama]; 
	} else { 
		if (isset($_GET[txt_nama])) { $nama=$_GET[txt_nama]; } else { $nama=""; }
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
<form name="frm_akun" id="frm-akun" method="post">

<input type="hidden" id="id-menu" value="<? echo $_GET[id_menu]; ?>">			
<input type="hidden" id="id-add" value="<? echo $tmbl_add; ?>">	
<input type="hidden" id="id-edit" value="<? echo $tmbl_edit; ?>">	
<input type="hidden" id="id-delete" value="<? echo $tmbl_del; ?>">	
<input type="hidden" id="btn-aksi" value="<? echo "ADD"; ?>">	

<!-- <div class="container">   -->
<div class="panel panel-primary">
	<div class="panel-heading">    
		<div class="row">
			<div class="col-xs-4"><p class="panel-title"><span class='glyphicon glyphicon-book'></span> MASTER NOMOR PERKIRAAN <span class="badge alert-warning" id="badge_kode"><? echo "$kode"; ?></span><span class="badge alert-warning" id="badge_nama"><? echo "$nama"; ?></span></p>
            </div>            
			<div class="col-xs-8 text-right hidden-print" >
				<button id="btn_add" name="btn_add" type="button" class="btn btn-success" onClick="tambah_akun('ADD','<? echo $_GET[id_menu];?>')" data-toggle="tooltip" data-placement="bottom" title="Add New" accesskey="a"  disabled='disabled'><span class="glyphicon glyphicon-plus"></span></button> 
							
				<button id="btn_print" name="btn_print" type="button" class="btn btn-success" onClick="klik_print()" accesskey="p" data-toggle="tooltip" data-placement="bottom" title="Print"  disabled='disabled'><span class="glyphicon glyphicon-print"></span></button>
				<button id="btn_find" name="btn_find" type="button" class="btn btn-success" onClick="klik_find()" accesskey="f" data-toggle="tooltip" data-placement="bottom" title="Find"  disabled='disabled'><span class="glyphicon glyphicon-search"></span></button>
			</div>
		</div>
	</div>
  	<div class="panel-body">
		<div id="area-cari-data">
		</div>
		<div id="area-input">
		</div>
		<? 
		
		$table_akun="mst_akun_".$company_id;

		if ($_POST[k_acc_del]) {
			$query_hapus=mysql_query("UPDATE ".$table_akun." SET acc_status=0, pemakai='$user_id', tgl_input=now() WHERE acc='$_POST[k_acc_del]' AND mcom_id='$company_id'", $conn) or die(mysql_error());
			//$data_hasil = mysql_fetch_array($query_data);		
			if ($query_hapus) {
				echo "<div class='alert alert-success'><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">X</button> <b> ( $_POST[k_acc_del] ) </b> Berhasil dihapus </div>";
			}
			else {
				echo "<div class='alert alert-danger'> <b>( $_POST[k_acc_del]) </b> Gagal dihapus </div>";
			}
		}
		if (isset($_POST[btn_save])) {			
			if ($_POST[btn_save]=="ADD") {
				// Add New 
				if (empty($_POST[kd_acc]) or empty($_POST[kd_nmp])) {
					echo "<script language=javascript>
						$(\"#area-pesan-edit\").html(\"<div class='alert alert-danger'>  Error : Kode dan Nama akun tidak boleh kosong ! </div>\");
						</script>";					
				} else {
					$query_data = mysql_query("SELECT acc FROM $table_akun WHERE acc='$_POST[kd_acc]' AND mcom_id='$company_id' AND acc_status='1' ", $conn) or die("Error Validasi New Acc ".mysql_error());
					$row_already = mysql_num_rows($query_data);
					if ($row_already) {
					//--already exist
						echo "<script language=javascript>
							$(\"#area-pesan-edit\").html(\"<div class='alert alert-danger'>  Error : Kode akun tersebut sudah ada ! </div>\");
							</script>";
					} else {	
						$query_data = mysql_query("INSERT INTO $table_akun (acc_status,mcom_id,acc,nmp,level,tnd,jnp,hpp,pusat,ket,th_sld,bl_sld,jml_sld,pemakai,tgl_input) VALUES ('1','$company_id','$_POST[kd_acc]','$_POST[kd_nmp]','$_POST[LevelGroup1]' ,'$_POST[DKGroup1]' ,'$_POST[NRGroup1]' ,'$_POST[HPPGroup1]' ,'$_POST[PPGroup1]' ,'$_POST[kd_ket]' ,'$_POST[sal_thn]','$_POST[sal_bln]','$_POST[saldo_jml]','$user_id' ,now())", $conn) or die("Error Insert New Acc ".mysql_error());
			
						echo "<script language=javascript>
								$(\"#area-pesan-edit\").html(\"<div class='alert alert-success'> Penambahan Akun baru berhasil </div>\");
								</script>";
					}
				}
			
			} else {
				// Edit
					// Simpan perubahan data akun
					$query_data = mysql_query("UPDATE $table_akun SET nmp='$_POST[kd_nmp]', level='$_POST[LevelGroup1]', tnd='$_POST[DKGroup1]', jnp='$_POST[NRGroup1]', hpp='$_POST[HPPGroup1]', pusat='$_POST[PPGroup1]', ket='$_POST[kd_ket]', pemakai='$user_id', tgl_input=now() WHERE acc='$_POST[kd_acc]' AND acc_status='1' AND mcom_id='$company_id' ", $conn) or die("Error Update Acc ".mysql_error());				
					
					echo "<script>	$(\"#area-pesan-edit\").html(\"<div class='alert alert-success'> Ubah data akun berhasil </div>\");	</script>";
					
			}
		}
				
			

		//echo "Company Id: -$company_id-";
		//-----SHOW--------------
		
		
		
		if(isset($_GET['page']))
		{
			$noPage = $_GET['page'];
			$no_urut=$_GET['no_urut'];
		} 
		else {
			$noPage = 1;
			$no_urut = 1;
		}
		
		if (isset($_GET[per_page])){
			$dataPerPage = $_GET[per_page];			
			if ($dataPerPage==0 || $dataPerPage=="") { $dataPerPage=500; }
		} else {
			$dataPerPage = 500;			
		}
			
		if(isset($_GET[level]))	{
			$level=$_GET[level];
		} else { 
			$level="";
		}	

		
		if (empty($kode) && empty($nama) ){ 
			if ($level<>"") { 
			//-- Count Total Data
				$count_categ = "";
				$count_categ = $count_categ."SELECT COUNT(*) ";
				$count_categ = $count_categ."FROM ".$table_akun." WHERE acc_status='1' AND mcom_id='$company_id' AND level='$level' ORDER BY acc ";
			} else {
				$count_categ = "";
				$count_categ = $count_categ."SELECT COUNT(*) ";
				$count_categ = $count_categ."FROM ".$table_akun." WHERE acc_status='1' AND mcom_id='$company_id' ORDER BY acc ";
			} 
		} 
		
		if (!empty($kode)){
			if ($level<>"") { 
			//-- Count Total Data
				$count_categ = "";
				$count_categ = $count_categ."SELECT COUNT(*) ";
				$count_categ = $count_categ."FROM ".$table_akun." WHERE acc like '$kode%' AND acc_status='1' AND mcom_id='$company_id' AND level='$level' ORDER BY acc ";
			} else {
				$count_categ = "";
				$count_categ = $count_categ."SELECT COUNT(*) ";
				$count_categ = $count_categ."FROM ".$table_akun." WHERE acc like '$kode%' AND acc_status='1' AND mcom_id='$company_id' ORDER BY acc ";
			}
		} 
		
		if (!empty($nama)){
			if ($level<>"") { 
			//-- Count Total Data
				$count_categ = "";
				$count_categ = $count_categ."SELECT COUNT(*) ";
				$count_categ = $count_categ."FROM ".$table_akun." WHERE nmp like '%$nama%' AND acc_status='1' AND mcom_id='$company_id' AND level='$level' ORDER BY acc ";
			} else {
				$count_categ = "";
				$count_categ = $count_categ."SELECT COUNT(*) ";
				$count_categ = $count_categ."FROM ".$table_akun." WHERE nmp like '%$nama%' AND acc_status='1' AND mcom_id='$company_id' ORDER BY acc ";
			}
		}
		
		//echo $count_categ;

		$qcount_categ = mysql_query($count_categ, $conn) or die("Error Query count_categ");
		$ftcount_categ = mysql_fetch_array($qcount_categ);
		$jumData = $ftcount_categ[0];
		//--End Find Count Data
		
		$offset = ($noPage - 1) * $dataPerPage;
		
		//-- include "../../include/paging_page.php";
		// menentukan jumlah halaman yang muncul berdasarkan jumlah semua data
		
		$jumPage = ceil($jumData/$dataPerPage);
		
		if ($jumPage < $noPage) {
			$noPage = 1;
			$no_urut= 1;
			$offset = ($noPage - 1) * $dataPerPage;
		}
		// menampilkan link previous
		$no_next=0;
		$no_prev=((($noPage-1)*$dataPerPage)-($dataPerPage-1));
		?>
        
		<div class="row">
        	<div class="col-xs-6">
            	<div class="pagination pagination-sm hidden-print">
		<?
		if ($noPage > 1) { echo  "<li><a href=\"?page=".($noPage-1)."&id_menu=$_GET[id_menu]&no_urut=$no_prev&level=$level&per_page=$dataPerPage&txt_kode=$kode&txt_nama=$nama\">PREV</a></li>"; }
		
		if ($jumPage > 1) {		
			// memunculkan nomor halaman dan linknya
			for($page = 1; $page <= $jumPage; $page++)
			{		
				if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) 
				 {        
					if (($showPage == 1) && ($page != 1 ) ) echo "<li class='disabled'><a href='#'> ... </a></li>"; 
					if (($showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "<li class='disabled'><a href='#'> ... </a></li>" ;
					if ($page == $noPage) {echo "<li class=\"active\"><a href='#'> ".$page."&nbsp;"."</a></li> "; $no_next=((($page+1)*$dataPerPage)-($dataPerPage-1)); }
					else {echo " <li><a href=\"?page=$page&id_menu=$_GET[id_menu]&no_urut=".(($page*$dataPerPage)-($dataPerPage-1))."&level=$level&per_page=$dataPerPage&txt_kode=$kode&txt_nama=$nama\">$page</a></li> ";}
					$showPage = $page;
				}
			}
		}
		// menampilkan link next
		
		if ($noPage < $jumPage) { echo " <li><a href=\"?page=".($noPage+1)."&id_menu=$_GET[id_menu]&no_urut=$no_next&level=$level&per_page=$dataPerPage&txt_kode=$kode&txt_nama=$nama\">NEXT</a></li> "; }
		?>
				</div>
        	</div>
        	<div class="col-xs-2 pull-right">
            	<div class="input-group hidden-print">
                  <div class="input-group-btn">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">Filter Level <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <?
                            echo "<li><a href=\"?id_menu=$_GET[id_menu]&page=1&no_urut=1&level=&nm_lvl=ALL&per_page=$dataPerPage&txt_kode=$kode&txt_nama=$nama\">Show all level</a></li>";
                            echo "<li><a href=\"?id_menu=$_GET[id_menu]&page=1&no_urut=1&level=1&nm_lvl=Level&nbsp;1&per_page=$dataPerPage&txt_kode=$kode&txt_nama=$nama\">Level 1</a></li>";
                            echo "<li><a href=\"?id_menu=$_GET[id_menu]&page=1&no_urut=1&level=2&nm_lvl=Level&nbsp;2&per_page=$dataPerPage&txt_kode=$kode&txt_nama=$nama\">Level 2</a></li>";
                            echo "<li><a href=\"?id_menu=$_GET[id_menu]&page=1&no_urut=1&level=3&nm_lvl=Level&nbsp;3&per_page=$dataPerPage&txt_kode=$kode&txt_nama=$nama\">Level 3</a></li>";
                            echo "<li><a href=\"?id_menu=$_GET[id_menu]&page=1&no_urut=1&level=4&nm_lvl=Level&nbsp;4&per_page=$dataPerPage&txt_kode=$kode&txt_nama=$nama\">Level 4</a></li>";
                            echo "<li><a href=\"?id_menu=$_GET[id_menu]&page=1&no_urut=1&level=5&nm_lvl=Level&nbsp;5&per_page=$dataPerPage&txt_kode=$kode&txt_nama=$nama\">Level 5</a></li>";
                        ?>
                    </ul>
                  </div><!-- /btn-group -->
                  <input type="text" class="form-control text-right" size="5" value="<? echo $level; ?>" readonly>
                </div><!-- /input-group -->
            </div>
        	<div class="col-xs-2 pull-right">
            	<div class="input-group hidden-print">
                  <div class="input-group-btn">
                  		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"> # Record <span class="caret"></span> </button>  
						<ul class="dropdown-menu">
                      	<?
                            echo "<li><a href=\"?id_menu=$_GET[id_menu]&page=1&no_urut=1&level=$_GET[level]&per_page=10&txt_kode=$kode&txt_nama=$nama\">10</a></li>";
                            echo "<li><a href=\"?id_menu=$_GET[id_menu]&page=1&no_urut=1&level=$_GET[level]&per_page=50&txt_kode=$kode&txt_nama=$nama\">50</a></li>";
                            echo "<li><a href=\"?id_menu=$_GET[id_menu]&page=1&no_urut=1&level=$_GET[level]&per_page=100&txt_kode=$kode&txt_nama=$nama\">100</a></li>";
                            echo "<li><a href=\"?id_menu=$_GET[id_menu]&page=1&no_urut=1&level=$_GET[level]&per_page=250&txt_kode=$kode&txt_nama=$nama\">250</a></li>";
                            echo "<li><a href=\"?id_menu=$_GET[id_menu]&page=1&no_urut=1&level=$_GET[level]&per_page=500&txt_kode=$kode&txt_nama=$nama\">500</a></li>";
                            echo "<li><a href=\"?id_menu=$_GET[id_menu]&page=1&no_urut=1&level=$_GET[level]&per_page=1000&txt_kode=$kode&txt_nama=$nama\">1000</a></li>";
                        ?>
                    </ul>                  
                  </div>
                  <input type="text" class="form-control text-right" size="5" value="<? echo $dataPerPage; ?>" readonly>
                 </div>
            </div>
        </div>
        		
		<div class="table">
		<table class="table table-bordered table-condensed table-hover ">
			<tr>
				<th width="4" colspan="2" class="info hidden-print">Editing</th>
				<th width="3%" height="30" class="info">#</th>
				<th width="15%" height="30" class="info">Kode Perkiraan</th>
				<th width="40%" class="info">Nama Perkiraan</th>
				<th width="3%" class="info">LVL</th>
				<th width="2%" class="info">D/K</th>
				<th width="3%" class="info">N/R</th>
				<th width="5%" class="info">HPP</th>
				<th width="5%" class="info">PUSAT</th>				
			</tr>
		<?	
		
		if ( empty($kode) && empty($nama) ){ 
			if (!empty($level)) {
					$qry = "SELECT * FROM ".$table_akun." WHERE acc_status='1' AND mcom_id='$company_id' AND level='$_GET[level]' ORDER BY acc ASC LIMIT $offset, $dataPerPage";
			} else {
				$qry="SELECT * FROM ".$table_akun." WHERE acc_status='1' AND mcom_id='$company_id' ORDER BY acc ASC LIMIT $offset, $dataPerPage";
			}
		}
		if (!empty($kode)){		
			if ($level<>"") {
					$qry = "SELECT * FROM ".$table_akun." WHERE acc like '$kode%' AND acc_status='1' AND mcom_id='$company_id' AND level='$_GET[level]' ORDER BY acc ASC LIMIT $offset, $dataPerPage";
			} else {
				$qry="SELECT * FROM ".$table_akun." WHERE acc like '$kode%' AND acc_status='1' AND mcom_id='$company_id' ORDER BY acc ASC LIMIT $offset, $dataPerPage";
			}
		} 
		if (!empty($nama)) {
			if ($level<>"") {
					$qry = "SELECT * FROM ".$table_akun." WHERE nmp like '%$nama%' AND acc_status='1' AND mcom_id='$company_id' AND level='$_GET[level]' ORDER BY acc ASC LIMIT $offset, $dataPerPage";
			} else {
				$qry="SELECT * FROM ".$table_akun." WHERE nmp like '%$nama%' AND acc_status='1' AND mcom_id='$company_id' ORDER BY acc ASC LIMIT $offset, $dataPerPage";
			}
		}
		
		//echo $qry;
		
		$query_data_akun = mysql_query($qry, $conn) or die("Error Select Akun Table ".mysql_error());		
		
		$jml_rec_data=mysql_num_rows($query_data_akun);		
		if ($jml_rec_data) {			
			while ($query_hasil = mysql_fetch_array($query_data_akun)){
				$nomor=$no_next-$dataPerPage;
				if ($tmbl_edit==2) {
					//$view_tmbl_edit="<a href='#' onclick=\"ubah_akun('$query_hasil[0]','$_GET[id_menu]')\" id=\"$query_hasil[0]\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit data akun\"><span class=\"glyphicon glyphicon-edit\"/></a>";
					$view_tmbl_edit="<button type='button' class='btn-link' name=\"btn_edit_detail\" id=\"edit_$nomor\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit data $data[seq]\" onclick=\"ubah_akun('$query_hasil[0]','$_GET[id_menu]')\" target=\"#area-input\"><span class=\"glyphicon glyphicon-edit\"/></span></button>";
					 //<img src=\"../../bootstrap/glyphicons/png/glyphicons_150_edit.png\"
				} else {
					$view_tmbl_edit="";
				}
				if ($tmbl_del==3) {					
					//$view_tmbl_del="<a href='tabel_akun.php?k_acc_del=$query_hasil[0]&n_acc_del=$query_hasil[1]&page=$noPage&no_urut=$nomor&level=$level&per_page=$dataPerPage&id_menu=$_GET[id_menu]' data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Hapus akun\" onClick=\"return confirm('Hapus Akun $query_hasil[0]-$query_hasil[1]')\"><span class=\"glyphicon glyphicon-trash\"/></a>";
					$view_tmbl_del="<button type='button' class='btn-link' onclick=\"klik_d_delete('$query_hasil[0]')\" name=\"btn_delete_detail\" id=\"delete_$$query_hasil[0]\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete data $query_hasil[0]\" ><span class=\"glyphicon glyphicon-trash\"/></span></button>";						
					//<img src=\"../../bootstrap/glyphicons/png/glyphicons_016_bin.png\"/>
				} else {
					$view_tmbl_del="";
				}
				
				
				echo "<tr class='text-primary'> 
				<td width='2%' class='hidden-print'>$view_tmbl_edit</td>
				<td width='2%' class='hidden-print'>$view_tmbl_del</td>
				<td width='3%' align='left'>$no_urut.</td>
				<td width='9%' align='left'>$query_hasil[acc]</td>
				<td width='58%'>$query_hasil[nmp]</td>
				<td width='2%' align='center'>$query_hasil[12]</td>
				<td width='4%' align='center'>$query_hasil[3]</td>
				<td width='3%' align='center'>$query_hasil[2]</td>
				<td width='3%' align='center'>$query_hasil[8]</td>
				<td width='4%' align='center'>$query_hasil[11]</td>
				</tr>";
			$no_urut ++;
			}
			
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
		<ul class="pagination pagination-sm hidden-print">
		<?
		if ($noPage > 1) { echo  "<li><a href=\"?page=".($noPage-1)."&id_menu=$_GET[id_menu]&no_urut=$no_prev&level=$level&per_page=$dataPerPage&txt_kode=$kode&txt_nama=$nama\">PREV</a></li>"; }
		
		if ($jumPage > 1) {		
			// memunculkan nomor halaman dan linknya
			for($page = 1; $page <= $jumPage; $page++)
			{		
				if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) 
				 {        
					if (($showPage == 1) && ($page != 1)) echo "<li class='disabled'><a href='#'> ... </a></li>"; 
					if (($showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "<li class='disabled'><a href='#'> ... </a></li>" ;
					if ($page == $noPage) {echo "<li class=\"active\"><a href='#'> ".$page."&nbsp;"."</a></li> "; $no_next=((($page+1)*$dataPerPage)-($dataPerPage-1)); }
					else {echo " <li><a href=\"?page=$page&id_menu=$_GET[id_menu]&no_urut=".(($page*$dataPerPage)-($dataPerPage-1))."&level=$level&per_page=$dataPerPage&txt_kode=$kode&txt_nama=$nama\">$page</a></li> ";}
					$showPage = $page;
				}
			}
		}
		// menampilkan link next
		
		if ($noPage < $jumPage) { echo " <li><a href=\"?page=".($noPage+1)."&id_menu=$_GET[id_menu]&no_urut=$no_next&level=$level&per_page=$dataPerPage&txt_kode=$kode&txt_nama=$nama\">NEXT</a></li> "; }

		?>
		 </ul>	
	</div>
</div>
</form>

<?

?>

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
	<script src="aksi_akun.js"></script>
	
    <script src="../../js/jquery-ui-1.10.4.custom.js"></script>
    <script src="../../js/jquery.inputmask.js"></script>
<!--<script type="text/javascript">
	
	//$(document).ready(function(){
		//$("#kd-acc").inputmask("9-99-99-99-99");
		//$("#d_nm_jml").inputmask("999,999,999,999,999.99",{groupSeparator: ",", autoGroup: true});
	
		//$("#tgl_bukti").datepicker();
		
		
		// jika mau dipakai inputmask sudah ok nih
		//$("#no_bukti").inputmask("aa-***/**/**");
		//$("#d_nm_jml").inputmask("decimal", { radixPoint: "," });
	//});
	
</script>-->
</BODY>
</HTML>
<!-- session -->
<?	

	//echo "MENU= $_GET[id_menu]  Add = $tmbl_add -  Edit = $tmbl_edit -  Delete = $tmbl_del  ";   

}
else { header("location:/glt/no_akses.htm"); }

?>

