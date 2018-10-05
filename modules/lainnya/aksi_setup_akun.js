function klik_reset(kode)
{	
	if (kode==1) { 
		document.getElementById("kd-kas").value="";
		document.getElementById("nmp-kas").value="";
	}
	if (kode==2) { 
		document.getElementById("kd-bank").value="";
		document.getElementById("nmp-bank").value="";
	}
	if (kode==3) { 
		document.getElementById("kd-bs").value="";
		document.getElementById("nmp-bs").value="";
	}
}

function klik_refresh()
{	
	location.reload();
}

function klik_reset_button()
{
	document.getElementById("btn-edit").disabled = false;
	
	//document.getElementsByName("btn_save_setup").disabled = true;
	var xx=document.getElementsByName("btn_save_setup");
	
	for (var i = 0; i < xx.length; i++) { 
		xx[i].disabled= true ;
	}
	document.getElementById("btn-cancel").disabled = true;
	
	document.getElementById("btn-kas").disabled = true;
	document.getElementById("btn-bank").disabled = true;
	document.getElementById("btn-bs").disabled = true;

	document.getElementById("btn-kas-r").disabled = true;
	document.getElementById("btn-bank-r").disabled = true;
	document.getElementById("btn-bs-r").disabled = true;
	document.getElementById("btn-refresh").disabled = false;

}

function klik_edit()
{
	document.getElementById("btn-edit").disabled = true;
	//document.getElementsByName(btn_save_setup).disabled = false;
	
	var xx=document.getElementsByName("btn_save_setup");
	
	for (var i = 0; i < xx.length; i++) { 
		xx[i].disabled= false ;
	}
	
	document.getElementById("btn-cancel").disabled = false;


	document.getElementById("btn-kas").disabled = false;
	document.getElementById("btn-bank").disabled = false;
	document.getElementById("btn-bs").disabled = false;

	document.getElementById("btn-kas-r").disabled = false;
	document.getElementById("btn-bank-r").disabled = false;
	document.getElementById("btn-bs-r").disabled = false;
	document.getElementById("btn-refresh").disabled = true;
}


/*function klik_save()
{	document.getElementById("btn-edit").disabled = false;
	//document.getElementsByName("btn_save_setup").disabled = true;
	
	var xx=document.getElementsByName("btn_save_setup");
	
	for (var i = 0; i < xx.length; i++) { 
		xx[i].disabled= true ;
	}
	
	document.getElementById("btn-cancel").disabled = true;
	
	document.getElementById("btn-kas").disabled = true;
	document.getElementById("btn-bank").disabled = true;
	document.getElementById("btn-bs").disabled = true;

	document.getElementById("btn-kas-r").disabled = true;
	document.getElementById("btn-bank-r").disabled = true;
	document.getElementById("btn-bs-r").disabled = true;

	document.getElementById("btn-refresh").disabled = false;
	
	var kdkas=$("#kd-kas").val();
	var nmpkas=$("#nmp-kas").val();
	var kdbank=$("#kd-bank").val();
	var nmpbank=$("#nmp-bank").val();
	var kdbs=$("#kd-bs").val();
	var nmpbs=$("#nmp-bs").val();
	
	alert(kdkas+" - "+kdbank+" - "+kdbs);
	
	var url="setup_akun.php?btn_save_setup=true";
		
	$.post(url,$("#frm_setup_akun").serialize(),function(data) {
		
		alert('save');
		
		//location.html(data).show();	
		
	});

	//tombol_reset();	
	//alert('SAVE DATA');
}*/

function klik_cancel()
{	
	document.getElementById("btn-edit").disabled = false;
	
	//document.getElementsByName("btn_save_setup").disabled = true;
	var xx=document.getElementsByName("btn_save_setup");
	
	for (var i = 0; i < xx.length; i++) { 
		xx[i].disabled= true ;
	}

	document.getElementById("btn-cancel").disabled = true;
	
	document.getElementById("btn-kas").disabled = true;
	document.getElementById("btn-bank").disabled = true;
	document.getElementById("btn-bs").disabled = true;

	document.getElementById("btn-kas-r").disabled = true;
	document.getElementById("btn-bank-r").disabled = true;
	document.getElementById("btn-bs-r").disabled = true;

	document.getElementById("btn-refresh").disabled = false;

	klik_refresh();
	
}

function cari_akun(cari_data,kode)
{	
	if (kode==1) {
		var ket="Cari Akun Kas";
		var url="setup_cari_akun.php?btn_go_find=true&kode="+kode;
	} else if (kode==2){
		var ket="Cari Akun Bank";
		var url="setup_cari_akun.php?btn_go_find=true&kode="+kode;
	} else if (kode==3){
		var ket="Cari Akun BS";
		var url="setup_cari_akun.php?btn_go_find=true&kode="+kode;
	} 
	
	$("#area_cari_data").html("<div class='panel panel-primary' id='panel-cari-data'><div class='panel-body' id='body-cari-data'></div></div>");
	$('#body-cari-data').html(ket+"<br>Kode : <input id='jv_kode' name='f_kd_acc' type='text' value='' size='20' maxlength='15'> Nama : <input id='jv_nama' name='f_kd_nmp' type='text' value='' size='40' maxlength='50'> <button type='button' id='tombol-cari-akun' name='tombol-find-akun' class='btn btn-danger btn-sm' data-loading-text=\"Searching...\"><span class='glyphicon glyphicon-search'></span> FIND</button> <button type='button' id='tombol-close-cari' name='tombol-close-cari' class='btn btn-danger btn-sm'> CLOSE </button>");

	$("#tombol-cari-akun").bind("click", function(event) {
		var kode=$("#jv_kode").val();
		var nama=$("#jv_nama").val();
		
		var btn=$(this);
		
		//alert('FIND');
		btn.button('loading');
		
		$.post(url,{f_kd_acc:kode,f_kd_nmp:nama},function(data) {
			
			$('#body-cari-data').html("<button type='button' id='tombol-close-cari' name='tombol-close-cari' class='btn btn-danger btn-sm'> CLOSE </button>"+data+"<button type='button' id='tombol-close-cari2' name='tombol-close-cari' class='btn btn-danger btn-sm'> CLOSE </button>").show();	
			
			btn.button('reset');

			$("#tombol-close-cari").bind("click", function(event) {
				$("#area_cari_data").html("");
			});

			$("#tombol-close-cari2").bind("click", function(event) {
				$("#area_cari_data").html("");
			});
			
		});
	});
	
	$("#tombol-close-cari").bind("click", function(event) {
		$("#area_cari_data").html("");
	});
	
}


function ambil_akun_kas(kode,nama) {
	document.getElementById("kd-kas").value=kode;
	document.getElementById("nmp-kas").value=nama;
	$("#area_cari_data").html("");
}

function ambil_akun_bank(kode,nama) {
	document.getElementById("kd-bank").value=kode;
	document.getElementById("nmp-bank").value=nama;
	$("#area_cari_data").html("");
}

function ambil_akun_bs(kode,nama) {
	document.getElementById("kd-bs").value=kode;
	document.getElementById("nmp-bs").value=nama;
	$("#area_cari_data").html("");
}