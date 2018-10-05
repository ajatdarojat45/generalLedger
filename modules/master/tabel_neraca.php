<?php
session_start();
if (isset($_SESSION['app_glt']))
{
	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php";
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	
	//  Cek Hak Akses Tombol Add, Edit dan Delete
	include "../inc/inc_aed.php";
	//echo "Add = $tmbl_add - Edit = $tmbl_edit - Delete = $tmbl_del";   
	
	if ($_GET[k_noset_del]) {
		$query_hapus=mysql_query("UPDATE mst_setnrk SET trx_status='0', pemakai='$user_id', tgl_input=now() WHERE acc='$_GET[k_noset_del]' AND mcom_id='$company_id'", $conn) or die(mysql_error());
		//$data_hasil = mysql_fetch_array($query_data);		
		if ($query_hapus) {
			echo "<div class='font_header'>( $_GET[k_noset_del] - $_GET[k_nmset_del] ) Berhasil dihapus</div>";
		}
		else {
			echo "<div class='font_header'>( $_GET[k_noset_del] - $_GET[k_nmset_del] ) Gagal dihapus</div>";				
		}
	}
?>
<HTML>
<HEAD>
<TITLE>GL TEMPO</TITLE>
<link rel="stylesheet" href="../../bootstrap-3/css/bootstrap.min.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</HEAD>
<BODY>
<form name="frm_neraca" method="POST">
<?
			//echo "Company Id: -$company_id-";
			//-----SHOW--------------
			$dataPerPage = 10;			
			
			if(isset($_GET['page']))
			{
				$noPage = $_GET['page'];
				$no_urut=$_GET['no_urut'];
			} 
			else {$noPage = 1;
				$no_urut = 1;
			}
				 
			$offset = ($noPage - 1) * $dataPerPage;
?>
<table border="0" width="99%">
	
	<tr>
	  <td height="30" colspan="7"><span class="font_label_form">Tabel Setup Neraca Konsolidasi</span></td>
    </tr>
	<tr>
		<td width="4%" height="32"><? if ($tmbl_add==1) { echo "<a href='tabel_neraca_edit.php?k_acc=ADD&id_menu=$_GET[id_menu]&page=$noPage&no_urut=$no_urut' class='tooltip'><img src='../../img/add_button.png'></img><span> Tambah setup baru</span></a>"; } ?></td>        		
	    <td width="4%" height="32"><a href="<? echo "tabel_print_neraca.php?company_id=$company_id&id_menu=$_GET[id_menu]"; ?>" class="tooltip"><img src="/gltempo/img/print_button.png"></img> <span> Print daftar setup</span></a></td>        
	    <td width="4%" height="32"><a href="<? $nomor=$no_next-$dataPerPage; echo "tabel_cari_neraca.php?company_id=$company_id&id_menu=$_GET[id_menu]&no_urut=$no_urut&page=$noPage"; ?>" class="tooltip"><img src="../../img/search_button.png"></img> <span> Cari data setup</span></a></td>
	  <td colspan="2" height="32">&nbsp;</td>		
   	  <td width="85%" align="right"><?
		  //--Find Count Data
		$count_categ = "";
		$count_categ = $count_categ."SELECT COUNT(*) ";
		$count_categ = $count_categ."FROM mst_setnrk WHERE trx_status='1' AND mcom_id='$company_id' ORDER BY noset ";
		
		$qcount_categ = mysql_query($count_categ, $conn) or die("Error Query count_categ");
		$ftcount_categ = mysql_fetch_array($qcount_categ);
		
		$jumData = $ftcount_categ[0];
		//--End Find Count Data
		
		//-- include "../../include/paging_page.php";
		
		
		// menentukan jumlah halaman yang muncul berdasarkan jumlah semua data
		
		$jumPage = ceil($jumData/$dataPerPage);
		
		// menampilkan link previous
		$no_next=0;
		$no_prev=((($noPage-1)*$dataPerPage)-($dataPerPage-1));
		
		if ($noPage > 1) echo  "<a href='".$_SERVER['PHP_SELF']."?page=".($noPage-1)."&id_menu=$_GET[id_menu]&no_urut=".$no_prev."' style='font-size:12px' >&nbsp;PREV&nbsp;</a>&nbsp;";
		
		// memunculkan nomor halaman dan linknya
		for($page = 1; $page <= $jumPage; $page++)
		{
				 if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) 
				 {   
					if (($showPage == 1) && ($page != 2))  echo "..."; 
					if (($showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "...";
					if ($page == $noPage) {echo "<b> ".$page."&nbsp;"."</b> "; $no_next=((($page+1)*$dataPerPage)-($dataPerPage-1)); }
					else echo " <a href='".$_SERVER['PHP_SELF']."?page=".$page."&id_menu=$_GET[id_menu]&no_urut=".(($page*$dataPerPage)-($dataPerPage-1))."' style='font-size:12px'>".$page."&nbsp;</a> ";
					$showPage = $page;          
				 }
		}
		
		// menampilkan link next
		
		if ($noPage < $jumPage) echo "<a href='".$_SERVER['PHP_SELF']."?page=".($noPage+1)."&id_menu=$_GET[id_menu]&no_urut=".$no_next."' style='font-size:12px'>&nbsp;NEXT&nbsp;</a>";
	
	   ?></td>
    </tr>
</table>
<table border="0" width="99%" bgcolor="#FFFFFF">
	<tr>
		<th width="5%" height="30" align="center" class="field_head">No.Urut</th>
		<th width="9%" height="30" align="center" class="field_head">Kode Setting</th>
		<th width="58%" align="center" class="field_head">Nama Setting</th>
		<th width="12%" colspan="2" align="center" class="field_head">Editing</th>
	</tr>
		<?			
		$query_data_neraca = mysql_query("SELECT * FROM mst_setnrk WHERE trx_status='1' AND mcom_id='$company_id' ORDER BY noset ASC LIMIT $offset, $dataPerPage", $conn) or die("Error Select Setup Neraca Table ".mysql_error());		
		$jml_rec_data=mysql_num_rows($query_data_neraca);		
		if ($jml_rec_data) {			
			while ($query_hasil = mysql_fetch_array($query_data_neraca)){
				$nomor=$no_next-$dataPerPage;
				if ($tmbl_edit==2) {
					$view_tmbl_edit="<a href='tabel_neraca_edit.php?k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$noPage&no_urut=$nomor'><img src='../../img/edit.png' width='15' height='18' border='0'></a>";
				} else {
					$view_tmbl_edit="";
				}
				if ($tmbl_del==3) {					
					$view_tmbl_del="<a href='tabel_neraca.php?k_noset_del=$query_hasil[1]&k_nmset_del=$query_hasil[2]&page=$noPage&no_urut=$nomor'><img src='../../img/delete.png' width='15' height='18' border='0' onClick=\"return confirm('Hapus Setup Neraca $query_hasil[1]-$query_hasil[2] ?')\"></a>";					
				} else {
					$view_tmbl_del="";
				}				
				
				echo "<tr class='field_data'> 
				<td width='5%' height='18' align='right'>$no_urut.</td>
				<td width='13%' height='18' align='center'>$query_hasil[1]</td>
				<td width='60%'>$query_hasil[2]</td>
				<td width='4%' align='center'>$view_tmbl_edit</td>
				<td width='5%' align='center'>$view_tmbl_del</td></tr>";
			$no_urut ++;
			}
			echo "<tr><td colspan='5' class='field_head'></td></tr>";
		}
		else{
			echo "<tr class='field_data'><td colspan='5'>Empty Data Record.</td></tr>";
		}
		?>
</table>
<table width="99%"><tr><td align="right" valign="middle">
<?
 //--Find Count Data
		$count_categ = "";
		$count_categ = $count_categ."SELECT COUNT(*) ";
		$count_categ = $count_categ."FROM mst_setnrk WHERE trx_status='1' AND mcom_id='$company_id' ORDER BY noset ";
		
		$qcount_categ = mysql_query($count_categ, $conn) or die("Error Query count_categ");
		$ftcount_categ = mysql_fetch_array($qcount_categ);
		
		$jumData = $ftcount_categ[0];
		//--End Find Count Data
		
		//-- include "../../include/paging_page.php";
		
		
		// menentukan jumlah halaman yang muncul berdasarkan jumlah semua data
		
		$jumPage = ceil($jumData/$dataPerPage);
		
		// menampilkan link previous
		$no_next=0;
		$no_prev=((($noPage-1)*$dataPerPage)-($dataPerPage-1));
		
		if ($noPage > 1) echo  "<a href='".$_SERVER['PHP_SELF']."?page=".($noPage-1)."&id_menu=$_GET[id_menu]&no_urut=".$no_prev."' style='font-size:12px'>&nbsp;PREV&nbsp;</a>&nbsp;";
		
		// memunculkan nomor halaman dan linknya
		for($page = 1; $page <= $jumPage; $page++)
		{
				 if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) 
				 {   
					if (($showPage == 1) && ($page != 2))  echo "..."; 
					if (($showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "...";
					if ($page == $noPage) {echo " <b>".$page."&nbsp;"."</b> "; $no_next=((($page+1)*$dataPerPage)-($dataPerPage-1)); }
					else echo " <a href='".$_SERVER['PHP_SELF']."?page=".$page."&id_menu=$_GET[id_menu]&no_urut=".(($page*$dataPerPage)-($dataPerPage-1))."' style='font-size:12px'>".$page."&nbsp;</a> ";
					$showPage = $page;          
				 }
		}		
		// menampilkan link next		
		if ($noPage < $jumPage) echo "<a href='".$_SERVER['PHP_SELF']."?page=".($noPage+1)."&id_menu=$_GET[id_menu]&no_urut=".$no_next."' style='font-size:12px'>&nbsp;NEXT&nbsp;</a>";
?> 
</td></tr></table>
</form>
</BODY>
</HTML>
<!-- session -->
<?	
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

