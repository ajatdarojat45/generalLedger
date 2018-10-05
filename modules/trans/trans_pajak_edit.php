<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {	
	include "../inc/inc_akses.php";
	include "../inc/func_modul.php";
	
	$pil_d="";
	$pil_k="";
	
	$tgl=ubah_tgl(substr($_GET[tgp],0,10));  
	
	// Koreksi akun beban
	// Akun dengan jenis akunnya D jika koreksi positif maka akan dikredit (mengurangi beban)
	
	// Koreksi akun pendapatan
	// Akun dengan jenis akunnya K jika koreksi positif maka akan dikredit (menambah pendapatan)
	//
	
	if (isset($_GET[d_k])) {
		if ($_GET[d_k]=="D") {
			// Koreksi akun beban dengan d_k=="D"
		
			if ($_GET[kor_dk]=="K") { $pil_d="checked"; } else {  $pil_k="checked";  } 		
			
		} else { 
			// Koreksi akun pendapatan dengan d_k=="K"
			if ($_GET[kor_dk]=="K") { $pil_d="checked"; } else {  $pil_k="checked";  } 		
		}
	} else {
		// default tambah transaksi baru adalah koreksi positif
		$pil_d="checked";  
		$tgl=ubah_tgl(substr($_POST[cdate],0,10));
	}	
	
	if ($_GET[nbk]<>"AUTO") { $dis_find="disabled='disabled'"; } else { $dis_find=""; }
	
	echo "<div class='row'>
			<div class=\"col-xs-2\">
			  <label for=\"txt_nobukti\">Nomor Bukti :</label>
			  <input class=\"form-control\" type=\"text\" name=\"txt_nobukti\" id=\"no_bukti\" placeholder=\"Nomor Bukti ?\" maxlength=\"15\" size=\"25\"  value=\"$_GET[nbk]\" readonly ></input>
			</div>
			<div class='col-xs-7'>
				<label for='no_akun'>Akun :</label>
				<div class='form-inline'>
				<input type='text' class='form-control' id='d_no_akun' name='txt_no_akun' placeholder='Kode Akun ?' value='$_GET[acc]' readonly /> 
				<input type='text' class='form-control' id='d_nm_akun' name='txt_nm_akun' size='80' placeholder='Nama Akun ?' value='$_GET[nmp]' readonly /> 
				<button class='btn btn-success btn-sm' type='button' id='cari_nm_akun' onclick=\"cari_akun($('#d_nm_akun').val(),2)\" data-toggle='tooltip' data-placement='bottom' title='Find' $dis_find ><span class='glyphicon glyphicon-search'></span></button>
				<input type='hidden' id='d_acc_dk' name='txt_dk' value='$_GET[d_k]'/> 
				</div>
			</div>
			<div class='col-xs-3'>
				<div class=\"radio-inline\"><br>
					<input type=\"radio\" name=\"koreksi_dk\" id=\"chk_dk1\" value='positif' $pil_d ></input>
					<label for=\"chk_dk1\">Koreksi Positif</label>
				</div>
				<div class=\"radio-inline\"><br>
					<input type=\"radio\" name=\"koreksi_dk\" id=\"chk_dk2\" value='negatif' $pil_k ></input >
					<label for=\"chk_dk2\">Koreksi Negatif</label>
				</div>
		   	</div>
		</div>";
		
	
	
	echo "<div class='row'>
			<div class='col-xs-2'>
				<label for=\"tgl_bukti\">Tgl. Bukti :</label>
					<div class='form-inline'>
					<input class=\"form-control\" type=\"text\" name=\"txt_tglbukti\" id=\"tgl_bukti\" placeholder=\"Tgl. Bukti ?\" size=\"11\" readonly value=\"$tgl\" ></input>
					<input type=\"hidden\" name=\"txt_tglaktif\" id=\"tgl_aktif\" value=\"$tgl\">
					<input type=\"hidden\" name=\"h_tgl_bukti\" id=\"h_tgl_bukti\" value=\"$tgl\"> 
					</div>
			</div>
			<div class='col-xs-7'>
				<label for='d_nm_ket'>Keterangan :</label>
				<div class='form-inline'>
					<input type='text' class='form-control' id='d_nm_ket' name='txt_nm_ket' size='120' maxlength='50' placeholder='Keterangan ?' value=\"$_GET[ket]\" required/>
				</div>
			</div>
			<div class='col-xs-3'>
				<label for='d_nm_jml'>Jumlah :</label>
				<div class='input-group'>
					<span class='input-group-addon'><b>Rp</b></span>
					<input type='text' class='form-control text-right' id=\"d_nm_jml\" name='txt_jml' maxlength='15' placeholder='Nilai Jumlah ?' value='$_GET[jumlah]' required/></div></div></div>";
					
	echo "<script> $(\"#tgl_bukti\").datepicker({dateFormat: \"dd-mm-yy\"}); </script>" ;
		
}

else { header("location:/glt/no_akses.htm"); }

?>

