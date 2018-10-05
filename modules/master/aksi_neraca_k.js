
function hapus_neraca(kode)
{	//$_GET[k_acc_del]

	$("#dialog-konfirmasi").modal('show');
	$(".modal-header .modal-title").html("<span class='glyphicon glyphicon-trash'></span> DELETE");			
	$("#mybody-konfirmasi").html("<h4>Kode Setting "+kode+" akan dihapus ?</h4>");			
	$("#myfooter-konfirmasi").html("<button class='btn btn-danger' id=\"tombol-ok\" name='k_noset_del' value='"+kode+"'><span class='glyphicon glyphicon-trash'></span>  OK </button><button class='btn btn-success' id='tombol-close' name='tombol-close' data-dismiss='modal' > CANCEL </button>");
	
}


function tombol_add_edit()
{	$("#area-cari-data").html("")	

	document.getElementById("btn_add").disabled = true;
	//document.getElementById("btn_print").disabled = true;
}

function klik_cancel()
{	tombol_reset();	
	
	$("#area-input").html("")	
	$("#area-cari-data").html("")	
}


function tombol_reset()
{
	var a_add=$("#id-add").val();
	var a_edit=$("#id-edit").val();
	var a_del=$("#id-delete").val();
	if (a_add=="1"){
		document.getElementById("btn_add").disabled = false;
	} else {
		document.getElementById("btn_add").disabled = true;
	}
	document.getElementById("btn_print").disabled = false;
}

function ubah_setup(kode,idmenu)
{	tombol_add_edit();

	var url1="tabel_neraca_kons_setup.php?id_menu="+idmenu+"&kd_temp=YA&k_noset="+kode;
	
	$("#area-input").html(""); 
	
	document.getElementById("btn-aksi").value="EDIT"; 
	
	$.post(url1, $("#frm_neraca").serialize(),function(data) {
		$("#area-input").html(data).show();
	});
}

function tambah_neraca()
{	tombol_add_edit();

	var url="tabel_neraca_kons_edit.php?kd_aksi=ADD";
	
	$("#area-input").html(""); 
	document.getElementById("btn-aksi").value="ADD"; 
	
	$.post(url,function(data) {
		$("#area-input").html(data).show();		
	});
}

function ubah_neraca(kode,nama)
{	tombol_add_edit();

	var url="tabel_neraca_kons_edit.php?kd_aksi=EDIT&k_noset="+kode+"&k_nmset="+nama;
	
	$("#area-input").html(""); 
	document.getElementById("btn-aksi").value="EDIT"; 
	
	$.post(url,function(data) {
		$("#area-input").html(data).show();		
	});
}

function klik_print()
{
	$("#dialog-konfirmasi").modal('show');
	$(".modal-header .modal-title").html("<span class='glyphicon glyphicon-print'></span> Print");			
	$("#mybody-konfirmasi").html("<h4>Cetak Daftar ?</h4>");			
	$("#myfooter-konfirmasi").html("<button class='btn btn-danger' type='button' id=\"tombol-ok\" name='tmbl-ok'><span class='glyphicon glyphicon-print'></span>  OK </button><button class='btn btn-success' id='tombol-close' name='tombol-close' data-dismiss='modal' > CANCEL </button>");

	$("#tombol-ok").bind("click", function(event) {
		$("#dialog-konfirmasi").modal('hide');
		window.print();
		
	});
}

function klik_d_up(kode,idmenu,nourut)
{	var url="tabel_neraca_kons_reset_detail.php?id_menu="+idmenu+"&k_noset="+kode+"&row_atas=true&nourut="+nourut;
	
	//row_atas=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]
	
	//$("#area-detail-tabel").html("");

	$.post(url, function(data) {
		$("#area-detail-tabel").html(data).show();		
	});
}

function klik_d_down(kode,idmenu,nourut)
{	var url="tabel_neraca_kons_reset_detail.php?id_menu="+idmenu+"&k_noset="+kode+"&row_bawah=true&nourut="+nourut;
	
	//row_atas=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]
	
	//$("#area-detail-tabel").html("");

	$.post(url, function(data) {
		$("#area-detail-tabel").html(data).show();		
	});
}


function klik_d_insert(kode,idmenu,nourut)
{	var url="tabel_neraca_kons_reset_detail.php?id_menu="+idmenu+"&k_noset="+kode+"&row_sisip=true&nourut="+nourut;
	
	//row_atas=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]
	
	//$("#area-detail-tabel").html("");

	$.post(url, function(data) {
		$("#area-detail-tabel").html(data).show();		
	});
}

function klik_d_delete(kode,idmenu,nourut)
{	var url="tabel_neraca_kons_reset_detail.php?id_menu="+idmenu+"&k_noset="+kode+"&row_hapus=true&nourut="+nourut;
	
	//row_atas=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]
	
	//$("#area-detail-tabel").html("");

	$.post(url, function(data) {
		$("#area-detail-tabel").html(data).show();		
	});
}

function klik_d_add(kode,idmenu)
{	var url="tabel_neraca_kons_reset_detail.php?id_menu="+idmenu+"&k_noset="+kode+"&row_baru=true";
	
	//row_atas=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]
	
	//$("#area-detail-tabel").html("");

	$.post(url, function(data) {
		$("#area-detail-tabel").html(data).show();		
	});
}

function klik_d_head(kode,nama)
{	//var url="tabel_neraca_kons_reset_detail.php?id_menu="+idmenu+"&k_noset="+kode+"&row_baru=true";
	
	//row_atas=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]
	
	//$("#area-detail-tabel").html("");

	//$.post(url, function(data) {
	//	$("#area-detail-tabel").html(data).show();		
	//});
	
	$("#dialog-form").modal('show');
	$("#mytitle").html("No.Id : "+kode+"  Keterangan : "+nama);
	
}

function klik_d_detail(kode,nama)
{	//var url="tabel_neraca_kons_reset_detail.php?id_menu="+idmenu+"&k_noset="+kode+"&row_baru=true";
	
	//row_atas=true&nourut=$query_hasil[3]&k_noset=$query_hasil[1]&id_menu=$_GET[id_menu]&page=$_GET[page]&no_urut=$_GET[no_urut]
	
	//$("#area-detail-tabel").html("");

	//$.post(url, function(data) {
	//	$("#area-detail-tabel").html(data).show();		
	//});
	
	$("#dialog-form").modal('show');
	$("#mytitle").html("No.Id : <input type='text' class='form-control' value='"+kode+"' readonly >  Keterangan : <input type='text' class='form-control' value='"+nama+"' >");
	
}

function klik_d_edit(nomor,kode,noset)
{	
	$("#area_btn_row_"+nomor).html("<button id='btn_row_save"+nomor+"' type='button' class='btn btn-danger btn-xs' onClick='klik_d_save("+nomor+","+kode+","+noset+")'><span class='glyphicon glyphicon-ok'></span></button> <button id='btn_row_cancel"+nomor+"' type='button' class='btn btn-success btn-xs' onClick='klik_d_cancel("+nomor+","+kode+","+noset+")'><span class='glyphicon glyphicon-remove'></span></button>");
	
	document.getElementById("input_1_"+nomor).disabled=false;
	document.getElementById("input_2_"+nomor).disabled=false;
	document.getElementById("input_3_"+nomor).disabled=false;
	document.getElementById("input_4_"+nomor).disabled=false;
	
	tot_row=$("#tot_row").val();
	tot_row=tot_row-1;
	
	//alert(tot_row);
	
	for (var i = 1; i <= tot_row ; i++) { 
		if(i!=nomor){
        	document.getElementById("btn_edit_"+i).onclick="";
		}
    }
}

function klik_d_cancel(nomor,kode,noset)
{	//$("#area_btn_row_"+nomor).html("");

	var url="tabel_neraca_kons_reset_detail.php?k_noset="+noset;
	$.post(url, function(data) {
		$("#area-detail-tabel").html(data).show();		
	});
}

function klik_d_save(nomor,kode,noset)
{	//$("#area_btn_row_"+nomor).html("");

	var url="tabel_neraca_kons_reset_detail.php?k_noset="+noset+"&btn_save_row=true&nourut_id="+kode;
	$.post(url,$("#frm_neraca").serialize(),function(data) {
		$("#area-detail-tabel").html(data).show();		
	});
}

function klik_d_setup(nomor,kode,noset,ket,flg,hdr,tampil,pt,nm_pt)
{	
	$("#dialog-form").modal('show');
	
	if (flg=="D") { v_edit="Detail";} 
	if (flg=="H" && hdr=="S") { v_edit="Sub Header";}
	if (flg=="H" && hdr=="H") { v_edit="Header";}
	
	$("#mytitle").html('Setup '+v_edit+' : '+kode+' - '+ket);
	
	var url="setup_edit.php?flg="+flg+"&pt="+pt+"&nm_pt="+nm_pt+"&nourut="+kode+"&noset="+noset+"&level=5&nm_lvl=Level 5";
	
	$.post(url,function(data) {
		$("#mybody").html(data).show();		
	});	
	$("#myfooter").html("<button name='btn_setup_save' class='btn btn-success' type='button' data-dismiss='modal'>CLOSE</button>");		
}

function klik_akun_pt(pt,nourut)
{
	$("#frame_hasil").attr('src','tampil_setup_akun.php?nourut='+nourut+'&pt='+pt);
}
