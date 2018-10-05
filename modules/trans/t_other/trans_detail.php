<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {	

	echo "<div class='row'>
			<div class='col-xs-1'>
				<label for='id_seq'>SEQ :</label>
				<div class='form-inline'>
					<input type='text' class='form-control input-sm' id='d_seq' name='txt_d_seq' placeholder='SEQ ?' disabled='disabled' value='$_GET[kode]' size='5'/>
				</div>
			</div>
			<div class='col-xs-8'>
				<label for='no_akun'>Akun Detail :</label>
				<div class='form-inline'>
				<input type='text' class='form-control input-sm' id='d_no_akun' name='txt_d_no_akun' placeholder='Kode Akun ?' value='$_GET[acc]' readonly /> 
				<input type='text' class='form-control input-sm' id='d_nm_akun' name='txt_d_nm_akun' size='80' placeholder='Nama Akun ?' value='$_GET[nmp]' readonly /> 
				<button class='btn btn-success btn-sm' type='button' id='cari_d_nm_akun' onclick=\"cari_akun($('#d_nm_akun').val(),2)\" data-toggle='tooltip' data-placement='bottom' title='Find'><span class='glyphicon glyphicon-search'></span></button>
				</div>
			</div>
			<div class='col-xs-3'>
				<div class=\"radio-inline\"><br>
					<input type=\"radio\" name=\"jenis_dk\" id=\"chk_dk1\" value='D' checked disabled='disabled'></input>
					<label for=\"chk_dk1\">Debet</label>
				</div>
				<div class=\"radio-inline\"><br>
					<input type=\"radio\" name=\"jenis_dk\" id=\"chk_dk2\" value='K' disabled='disabled'></input >
					<label for=\"chk_dk2\">Kredit</label>
				</div>
		   	</div>
		</div>";
	
	echo "<div class='row'>
			<div class='col-xs-1'>
			</div>
			<div class='col-xs-8'>
				<label for='d_no_sup'>Supplier :</label>
				<div class='form-inline'>
					<input type='text' class='form-control input-sm' id='d_no_sup' name='txt_d_no_sup' placeholder='Kode Supplier ?' value='$_GET[kdsup]' readonly />
					<input type='text' class='form-control input-sm' id='d_nm_sup' name='txt_d_nm_sup' placeholder='Nama Supplier ?' size='80' value='$_GET[nmsup]' readonly /> 
					<button class='btn btn-success btn-sm' type='button' id='cari_d_nm_sup' onclick=\"cari_sup($('#d_nm_sup').val(),2)\" data-toggle='tooltip' data-placement='bottom' title='Find'><span class='glyphicon glyphicon-search' ></span></button> 
					<button type='button' class='btn btn-success btn-sm' id=\"btn-reset-sup\">RESET SUPPLIER</button>
				</div>
			</div>
			<div class='col-xs-3'>
				<label for='d_nm_jml'>Jumlah :</label>
				<div class='input-group'>
					<span class='input-group-addon'><b>Rp</b></span>
					<input type='text' class='form-control input-sm text-right' id=\"d_nm_jml\" name='txt_d_jml' placeholder='Nilai Jumlah ?' value='$_GET[v_jumlah]' /></div></div></div>";
	
	
	echo "<div class='row'>
			<div class='col-xs-1'>
			</div>
			<div class='col-xs-8'>
				<label for='d_nm_ket'>Keterangan :</label>
				<input type='text' class='form-control input-sm' id='d_nm_ket' name='txt_d_nm_ket' placeholder='Keterangan ?' value=\"$_GET[new_ket]\"/>
			</div>
		</div>";
		
}

else { header("location:/glt/no_akses.htm"); }

?>