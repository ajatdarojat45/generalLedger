function klik_delete(kode)
{
		$("#dialog-hapus").modal('show');
		$("#myheader-hapus").html("<span class='glyphicon glyphicon-trash'></span> DELETE > "+kode);			
		$("#mybody-hapus").html("<h5>Apakah Nomor Bukti tersebut akan dihapus ?</h5>");			
		$("#myfooter-hapus").html("<button type='submit' class='btn btn-danger' name=\"tombol_hapus\" value='"+kode+"' ><span class='glyphicon glyphicon-trash'></span>  OK </button><button class='btn btn-success' id='tombol-close' name='tombol-close' data-dismiss='modal' > CANCEL </button>");
}

function klik_add()
{
	tombol_add_edit();
	
	
	$("#tampil_badge").html("New");

	$('#area_cari_data').html("");

	var xx=document.getElementsByName("btn_edit_detail");
	var xx2=document.getElementsByName("btn_delete_detail");
	
	for (var i = 0; i < xx.length; i++) { 
		xx[i].disabled= true ;
		xx2[i].disabled= true ;
	}
	
	var url="trans_pajak_edit.php?nbk=AUTO&jumlah=0";
	
	$.post(url,function(data) {
		$("#area_input_detail").html("<div class='panel panel-primary alert-success' id='panel-input-detail'><div class='panel-body' id='body-detail-jurnal'></div></div>");	
		$("#body-detail-jurnal").html(data).show();
	});
	
}

function klik_edit(nbk,tgp,acc,nmp,ket,deb,krd,jumlah,d_k,kor_dk)
{
	tombol_add_edit();	
	
	$("#tampil_badge").html(nbk);
	$('#area_cari_data').html("");
	
	var xx=document.getElementsByName("btn_edit_detail");
	var xx2=document.getElementsByName("btn_delete_detail");
	
	for (var i = 0; i < xx.length; i++) { 
		xx[i].disabled= true ;
		xx2[i].disabled= true ;
	}
	
	var url="trans_pajak_edit.php?nbk="+nbk+"&tgp="+tgp+"&acc="+acc+"&nmp="+nmp+"&ket="+ket+"&deb="+deb+"&krd="+krd+"&jumlah="+jumlah+"&d_k="+d_k+"&kor_dk="+kor_dk;
	
	$.post(url,function(data) {
		$("#area_input_detail").html("<div class='panel panel-primary alert-success' id='panel-input-detail'><div class='panel-body' id='body-detail-jurnal'></div></div>");	
		$("#body-detail-jurnal").html(data).show();
		
		//document.getElementById("btn_add").disabled = true;
		
	});
	
}

function klik_save()
{	
	tombol_reset();	
	alert('SAVE DATA');
	return false;
}

function reset_isi()
{	
	$('#tampil_badge').html("");	

}

function klik_cancel()
{	$("#area_input_detail").html("");

	reset_isi(); 
	
	tombol_reset();	
	
	var xx=document.getElementsByName("btn_edit_detail");
	var xx2=document.getElementsByName("btn_delete_detail");
	
	for (var i = 0; i < xx.length; i++) { 
		xx[i].disabled= false ;
		xx2[i].disabled= false ;
	}

}

function klik_print()
{
	var v_nbk=$("#no_bukti").val();
	
	if (v_nbk=="") { return ; } else {		
		$("#dialog-konfirmasi").modal('show');
		$(".modal-header .modal-title").html("<span class='glyphicon glyphicon-print'></span> Print");			
		$(".modal-body").html("<h4>Apakah Nomor Bukti "+v_nbk+" akan dicetak ?</h4>");			
		$(".modal-footer").html("<button class='btn btn-danger' id='tombol-ok' name='tombol-ok'><span class='glyphicon glyphicon-print'></span>  OK </button><button class='btn btn-success' id='tombol-close' name='tombol-close' data-dismiss='modal' > CANCEL </button>");
	
	}
}

function tombol_add_edit()
{	document.getElementById("btn_add").disabled = true;
	document.getElementById("btn_save").disabled = false;
	document.getElementById("btn_cancel").disabled = false;
	//alert('SAVE CANCEL..');
}

function tombol_reset()
{	var a_add=$("#id-add").val();
	if (a_add=="1"){
		document.getElementById("btn_add").disabled = false;
	} else {
		document.getElementById("btn_add").disabled = true;
	}
	
	document.getElementById("btn_save").disabled = true;
	document.getElementById("btn_cancel").disabled = true;
	
	//alert('RESET BUTTON');
}

function tombol_reset_all()
{	
	tombol_reset();
}

function cari_akun(cari_data,kode)
{	
	if (kode==1) {
		var ket="Cari Akun Pusat";
		var url="tabel_cari_akun.php?btn_go_find=true&jenis_trans=Kas";
	} else {
		var ket="Cari Akun";
		var url="tabel_cari_akun_detail.php?btn_go_find=true&jenis_trans=Kas";
	} 
	
	$("#area_cari_data").html("<div class='panel panel-primary' id='panel-cari-data'><div class='panel-body' id='body-cari-data'></div></div>");
	$('#body-cari-data').html(ket+"<div class='form-inline'><br>Kode : <input class='form-control' id='jv_kode' name='f_kd_acc' type='text' value='' size='20' maxlength='15'> Nama : <input class='form-control' id='jv_nama' name='f_kd_nmp' type='text' value='' size='70' maxlength='50'> <button type='button' id='tombol-cari-akun' name='tombol-find-akun' class='btn btn-danger btn-sm' data-loading-text=\"Searching...\"><span class='glyphicon glyphicon-search'></span> FIND</button> <button type='button' id='tombol-close-cari' name='tombol-close-cari' class='btn btn-success btn-sm'> CLOSE </button></div>");

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

function cari_sup(cari_data,kode)
{	
	var ket="Cari Master Supplier";
	var url="tabel_cari_sup.php?btn_go_find=true";

	$("#area_cari_data").html("<div class='panel panel-primary' id='panel-cari-data'><div class='panel-body' id='body-cari-data'></div></div>");
	$('#body-cari-data').html(ket+"<br>Kode : <input id='jv_kode' name='f_kd_sup' type='text' value='' size='20' maxlength='15'> Nama : <input id='jv_nama' name='f_kd_nmsup' type='text' value='' size='40' maxlength='50'> <button type='button' id='tombol-cari-akun' name='tombol-find-akun' class='btn btn-danger btn-sm' data-loading-text=\"Searching...\"><span class='glyphicon glyphicon-search'></span> FIND</button> <button type='button' id='tombol-close-cari' name='tombol-close-cari' class='btn btn-danger btn-sm'> CLOSE </button>");

	$("#tombol-cari-akun").bind("click", function(event) {
		var kode=$("#jv_kode").val();
		var nama=$("#jv_nama").val();
		
		var btn=$(this);
		
		btn.button('loading');
		
		$.post(url,{f_kd_sup:kode,f_kd_nmsup:nama},function(data) {
			
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

function ambil_akun_pusat(kode,nama) {
	document.getElementById("no_akun").value=kode;
	document.getElementById("nm_akun").value=nama;
	$("#area_cari_data").html("");
}

function ambil_akun_detail(kode,nama) {
	document.getElementById("d_no_akun").value=kode;
	document.getElementById("d_nm_akun").value=nama;
	$("#area_cari_data").html("");
}

function ambil_sup(kode,nama) {
	document.getElementById("d_no_sup").value=kode;
	document.getElementById("d_nm_sup").value=nama;
	$("#area_cari_data").html("");
}

function klik_tipe(kode) {
	if (kode=='KK') {
		document.getElementById("chk_dk1").checked=true;
	} else {
		document.getElementById("chk_dk2").checked=true;
	}
	var isi_badge=$('#tampil_badge').text();
	
	if (isi_badge=="New") {
		document.getElementById("no_bukti").value=kode+"-";
		document.getElementById("no_bukti").focus();
	}
	
	
}