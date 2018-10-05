function ubah_akun(kodeakun)
{		var url1="tabel_akun_edit.php?k_acc="+kodeakun+"&id_menu=$_GET[id_menu]";
		var url2="tabel_akun_edit.php?k_acc="+kodeakun+"&id_menu=$_GET[id_menu]";
	
		$('#dialog-akun').modal('show');
		$(".modal-title").html("UBAH AKUN"+kodeakun);			
		
		$.post(url1, {k_acc: kodeakun} ,function(data) {
			$(".modal-body").html(data).show();
		});
		$(".modal-footer").html("<button type='button' id='tombol-save' name='tombol-save' class='btn btn-danger'> SAVE </button> <button class='btn btn-success' name='tombol-close'> CANCEL </button>");
		
		$("#tombol-save").bind("click", function(event) {
			var vkdnmp= $('input:text[name=kd_nmp]').val();
		
			//"UPDATE mst_akun SET nmp='$_POST[kd_nmp]', level='$_POST[LevelGroup1]', tnd='$_POST[DKGroup1]', jnp='$_POST[NRGroup1]', hpp='$_POST[HPPGroup1]', pusat='$_POST[PPGroup1]', ket='$_POST[kd_ket]', pemakai='$user_id', tgl_input=now() WHERE acc='$_GET[k_acc]' AND acc_status='1' AND mcom_id='$company_id' ", $conn) or die("Error Update Acc ".mysql_error());
		
			$.post(url2, {k_acc: kodeakun, btn_save_akun: 'true',kd_nmp:vkdnmp});

			$('#dialog-akun').modal('hide');
		});

		$("#tombol-close").bind("click", function(event) {
			$('#dialog-akun').modal('hide');
		});
}

function tambah_akun(kodeakun)
{	var kode=kodeakun;
	var url="tabel_akun_edit.php?k_acc="+kodeakun+"&id_menu=$_GET[id_menu]";
	
	$('#dialog-akun').modal('show');
	$(".modal-title").html("BUAT AKUN BARU");			
	
	$.post(url, {k_acc: kodeakun} ,function(data) {
		$(".modal-body").html(data).show();
	});
	
	$(".modal-footer").html("<button type='button' name='tombol-save' id='tombol-save' class='btn btn-danger'> SAVE </button> <button class='btn btn-success' name='tombol-close' id='tombol-close'> CANCEL </button>");
	
	$("#tombol-save").bind("click", function(event) {
			alert('save new account....');
			//$('#dialog-akun').modal('hide');
		});

	$("#tombol-close").bind("click", function(event) {
			alert('close');
			$('#dialog-akun').modal('hide');
		});
	
}

function buka_halaman()
{
		
}


											  
