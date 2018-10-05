<?php
	session_start();  
	if ($_SESSION['app_glt']){ 
	 
		include 'f_utama.php' ; 
		
	}else {
		 
		include "f_login.php"; 
		
	} 
?>
