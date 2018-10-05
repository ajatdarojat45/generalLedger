<?php
	session_start();
	
	if (!isset($_SESSION['app_glt'])) {	
		exit ;
	}
	
	include "../inc/inc_akses.php";
	
	$tbl_mst_akun	="mst_akun_".$company_id ;
	$tbl_mst_sup	="mst_sup_".$company_id ;	
	$tbl_trx		="trx_caseglt_".$company_id ;	 
	$tgl_trans		=substr($aktif_tgl,0,7);
	
	$userid=$_SESSION["app_glt"];
	
	$tipe= isset($_GET['tipe']) ? $_GET['tipe'] : 'X';
	$filtertrans= isset($_POST['filtertrans']) ? $_POST['filtertrans'] : 'ALL';
	$showdel= isset($_POST['showdel']) ? $_POST['showdel'] : '1';
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'nbk';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
	$cari= isset($_POST['cari']) ? mysql_real_escape_string($_POST['cari']) : '';
	$offset = ($page-1)*$rows;
	
	
	$filtrec="";
	
	$filter1="";
	$filter2="";
	$filter3="";
	
	/*if ($showdel=="1") {
		$filter1="trx_status='".$showdel."' ";
	}*/
	
	$filter1="trx_status='1'";
	
	if ($tipe=="K" || $tipe=="B"){
		// Perkiraan Pusatnya
		$seq="000";
	} else {
		$seq="001";
	}
	//$filter2="left(tgp,7)='".$tgl_trans."' and seq='$seq'";
	$filter2="left(tgp,7)='".$tgl_trans."' and typ='".$tipe."' ";
	
	//if ($tipe!="ALL") {
		//$filter2="left(tgp,7)='".$tgl_trans."' and typ='".$tipe."' and seq='$seq' ";
		//$filter2="trx_status='1' and  left(tgp,7)='".$tgl_trans."' and typ='".$tipe."' ";
	//} 	
	
	if ($cari!="" ) {
		$filter3="CONCAT(left(tgp,10),nbk,ket) LIKE '%".$_POST['cari']."%' ";
	}
	
	if ($filter1!="" && $filter2!="" && $filter3!="" ) {
		$filtrec=" WHERE ".$filter1." AND ".$filter2." AND ".$filter3;
	} elseif ($filter1!="" && $filter2!="" ) {
		$filtrec=" WHERE ".$filter1." AND ".$filter2;
	} elseif ($filter2!="" && $filter3!="" ) {
		$filtrec=" WHERE ".$filter2." AND ".$filter3;
	} elseif ($filter1!="" && $filter3!="" ) {
		$filtrec=" WHERE ".$filter1." AND ".$filter3;
	} elseif ($filter1!="") {
		$filtrec=" WHERE ".$filter1;
	} elseif ($filter2!="" ) {
		$filtrec=" WHERE ".$filter2;
	} elseif ($filter3!="" ) {
		$filtrec=" WHERE ".$filter3;
	}
	
	// ...
	$qry="select COUNT(DISTINCT nbk) from ".$tbl_trx." ".$filtrec."";
	$rs = mysql_query($qry,$conn) or die ("Error $qry ".mysql_error());
	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	
	$qry1="select tgp,nbk,flg,sum(krd) as total,trx_status,min(acc) as acc,left(min(seq),3) as seq,substr(MIN(CONCAT(seq,ket)),4,100) as ket 
			from ".$tbl_trx." ".$filtrec." group by nbk order by $sort $order limit $offset, $rows ";
	
	//echo "$qry";
	
	$rs = mysql_query($qry1,$conn) or die ("Error $qry1 ".mysql_error());
	 
	$items = array();
	while($row = mysql_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;
	 
	echo json_encode($result);	
		
?>