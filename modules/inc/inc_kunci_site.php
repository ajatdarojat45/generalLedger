<?php
	// Cek apakah program sedang dikunci
	// Jika ya, maka user akan mendapatkan halaman peringatan
	
	$kunci_sistem=0;
	
	$query_data=mysql_query("SELECT * FROM mst_kunci", $conn) or die(mysql_error());
	$data_hasil = mysql_fetch_array($query_data);
	$kunci_sistem=$data_hasil[kunci_system];

?>