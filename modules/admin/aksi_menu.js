function hapus_menu(idmenu_del,nmmenu_del)
{	var idmenu = $('input:hidden[name=id_menu]').val();
	
	$('#dialog-modul').modal('show');
	$(".modal-header .modal-title").html("DELETE MAIN MENU");			
	$(".modal-body").html("Apakah Menu "+nmmenu_del+" akan dihapus ?");		
	$(".modal-footer").html("<button type='button' id='tombol-save' name='tombol-save' class='btn btn-danger'> DELETE </button><button class='btn btn-success' data-dismiss='modal'> CANCEL </button>");
		
	$("#tombol-save").bind("click", function(event) {
		var nil_id = $('input:hidden[name=id]').val();
										 
		var url2="mst_setup_menu_aksi.php?kd_aksi=DELETE&id_menu="+idmenu+"&btn_save_parent=true&id="+idmenu_del;
		
		$.post(url2,$("#frmmodul").serialize(),function(data) {
			$(".modal-body").html(data).show();
			$(".modal-footer").html("<button class='btn btn-success' id='tombol-close' name='tombol-close'> CLOSE </button>");
		});
	});

	$("#tombol-close").bind("click", function(event) {
		$('#dialog-modul').modal('hide');
	});
	
}

function tambah_menu()
{	var idmenu = $('input:hidden[name=id_menu]').val();
	var url1="mst_setup_menu_aksi.php?kd_aksi=ADDMENU&id_menu="+idmenu;
	
	$('#dialog-modul').modal('show');
	$(".modal-header .modal-title").html("CREATE NEW MAIN MENU");			
	
	$.post(url1,function(data) {
		$(".modal-body").html(data).show();
	});
		
	$(".modal-footer").html("<button type='button' id='tombol-save' name='tombol-save' class='btn btn-danger'> SAVE </button><button class='btn btn-success' data-dismiss='modal'> CANCEL </button>");
		
	$("#tombol-save").bind("click", function(event) {
		var nil_id = $('input:hidden[name=id]').val();
										 
		var url2="mst_setup_menu_aksi.php?kd_aksi=ADDMENU&id_menu="+idmenu+"&btn_save_parent=true&id="+nil_id;
		
		$.post(url2,$("#frmmodul").serialize(),function(data) {
			$(".modal-body").html(data).show();
			$(".modal-footer").html("<button class='btn btn-success' id='tombol-close' name='tombol-close'> CLOSE </button>");
		});
	});

	$("#tombol-close").bind("click", function(event) {
		$('#dialog-modul').modal('hide');
	});
	
}

function ubah_menu(parent_menuid)
{
		var idmenu = $('input:hidden[name=id_menu]').val();
		var url1="mst_setup_menu_aksi.php?kd_aksi=EDIT&id_menu="+idmenu+"&id="+parent_menuid;
	
		$('#dialog-modul').modal('show');
		$(".modal-header .modal-title").html("EDIT MAIN MENU");			
		
		$.post(url1,function(data) {
			$(".modal-body").html(data).show();
		});

		$(".modal-footer").html("<button type='button' name='tombol-save' id='tombol-save' class='btn btn-danger'> SAVE </button><button class='btn btn-success' data-dismiss='modal'> CANCEL </button>");
		
		$("#tombol-save").bind("click", function(event) {
			var url2="mst_setup_menu_aksi.php?kd_aksi=EDIT&btn_save_parent=true&id_menu="+idmenu+"&id="+parent_menuid;
												 
			$.post(url2,$('#frmmodul').serialize(),function(data){
				$(".modal-content .modal-body").html(data).show();				
				$(".modal-footer").html("<button class='btn btn-success' id='tombol-close' name='tombol-close' > CLOSE </button>");		
			});
		});

		$("#tombol-close").bind("click", function(event) {
			$('#dialog-modul').modal('hide');									
		});		

}

function ubah_submenu(parent_menuid)
{	var idmenu = $('input:hidden[name=id_menu]').val();
	var url1="mst_setup_menu_aksi.php?kd_aksi=EDITSUB&id_menu="+idmenu+"&id="+parent_menuid;
	
	$('#dialog-modul').modal('show');
	$(".modal-header .modal-title").html("EDIT SUB MENU");			
	
	$.post(url1,function(data) {
		$(".modal-body").html(data).show();
	});	
	
	$(".modal-footer").html("<button type='button' name='tombol-save' id='tombol-save' class='btn btn-danger'> SAVE </button><button name='tombol-close' class='btn btn-success' data-dismiss='modal'> CLOSE </button>");
	
	$("#tombol-save").bind("click", function(event) {
		var subid = $('input:text[name=txt_subid]').val();
		
		var url1="mst_setup_menu_aksi.php?kd_aksi=EDITSUB&btn_save_parent=true&id_menu="+idmenu+"&id="+parent_menuid+"&subid="+subid;
		
		$.post(url1,$('#frmmodul').serialize(),function(data) {
			$(".modal-body").html(data).show();
		});
		
	});

	$("#tombol-close").bind("click", function(event) {
		$('#dialog-modul').modal('hide');
	});
}

function tambah_submenu(parent_menuid)
{	var idmenu = $('input:hidden[name=id_menu]').val();
	var url1="mst_setup_menu_aksi.php?kd_aksi=ADDSUB&id_menu="+idmenu+"&id="+parent_menuid;
	
	$('#dialog-modul').modal('show');
	$(".modal-header .modal-title").html("CREATE NEW SUB MENU");			
	
	$.post(url1,function(data) {
		$(".modal-body").html(data).show();
	});
		
	$(".modal-footer").html("<button id='tombol-save' name='tombol-save' type='button' class='btn btn-danger' > SAVE </button><button class='btn btn-success' data-dismiss='modal'> CANCEL </button>");
	
	$("#tombol-save").bind("click", function(event) {
		var subid = $('input:text[name=txt_subid]').val();
		
		var url1="mst_setup_menu_aksi.php?kd_aksi=ADDSUB&btn_save_parent=true&id_menu="+idmenu+"&id="+parent_menuid+"&subid="+subid;
		
		$.post(url1,$('#frmmodul').serialize(),function(data) {
			$(".modal-body").html(data).show();
			$(".modal-footer").html("<button class='btn btn-success' id='tombol-close' name='tombol-close' > CLOSE </button>");
		});
	});

	$("#tombol-close").bind("click", function(event) {
		$('#dialog-modul').modal('hide');
	});
	
}

function list_submenu(parent_menuid)
{	var idmenu = $('input:hidden[name=id_menu]').val();
	var url1="mst_setup_menu_aksi.php?kd_aksi=LISTSUB&id_menu="+idmenu+"&id="+parent_menuid;
	
	$('#dialog-sub').modal('show');
	$(".modal-header .modal-title").html("SUB MENU LIST");			
	
	$.post(url1,function(data) {
		$(".modal-body").html(data).show();
	});	
	
	$(".modal-footer").html("<button class='btn btn-success' data-dismiss='modal'> CLOSE </button>");
	
}

function aktifkan_user(kodeuser)
{		var url1="f_setup_user_aksi.php?id_us="+kodeuser+"&kd_aksi=AKTIFKAN";
				
		$('#dialog-user').modal('show');
		$(".modal-header .modal-title").html("Aktifkan : "+kodeuser);			
		
		$(".modal-body").html("USER ID. <b>"+kodeuser+"</b>, akan aktifkan ?");
		
		$(".modal-footer").html("<input id='tombol-ok' type='button' class='btn btn-danger' value=' OK '><button class='btn btn-success' id='tombol-close' name='tombol-close' > CANCEL </button>");
		
		$("#tombol-ok").bind("click", function(event) {
			var url2="f_setup_user_aksi.php?id_us="+kodeuser+"&kd_aksi=AKTIFKAN&btn_save_user=true";
			var v_out=$('#frmmodul').serialize();		
				
			$.post(url2, v_out ,function(data) {
				$(".modal-body").html(data).show();	
				$(".modal-footer").html("<button class='btn btn-success' id='tombol-close' name='tombol-close' > CLOSE </button>");
						
			});	
		});
		
		$("#tombol-close").bind("click", function(event) {
			$('#dialog-modul').modal('hide');
		});
}	

function delete_user(kodeuser)
{		var url1="f_setup_user_aksi.php?id_us="+kodeuser+"&kd_aksi=DELETE";
				
		$('#dialog-user').modal('show');
		$(".modal-header .modal-title").html("Nonaktifkan : "+kodeuser);			
		
		$(".modal-body").html("USER ID. <b>"+kodeuser+"</b>, akan dinonaktifkan ?");
		
		$(".modal-footer").html("<input id='tombol-ok' type='button' class='btn btn-danger' value=' OK '><button class='btn btn-success' id='tombol-close' name='tombol-close' > CANCEL </button>");
		
		$("#tombol-ok").bind("click", function(event) {
			var url2="f_setup_user_aksi.php?id_us="+kodeuser+"&kd_aksi=DELETE&btn_save_user=true";
			var v_out=$('#frmmodul').serialize();		
				
			$.post(url2, v_out ,function(data) {
				$(".modal-body").html(data).show();	
				$(".modal-footer").html("<button class='btn btn-success' id='tombol-close' name='tombol-close' > CLOSE </button>");
						
			});	
		});
		
		$("#tombol-close").bind("click", function(event) {
			$('#dialog-modul').modal('hide');
		});
}											  




function reset_user(kodeuser)
{		var url1="f_setup_user_aksi.php?id_us="+kodeuser+"&kd_aksi=RESET";
		
		$('#dialog-user').modal('show');
		$(".modal-header .modal-title").html("RESET PASSWORD : "+kodeuser);			
		
		$.post(url1, {id_us:kodeuser} ,function(data) {
			$(".modal-body").html(data).show();
		});		
		
		
		$(".modal-footer").html("<input id='tombol-save' type='button' class='btn btn-danger' value=' SAVE '><button class='btn btn-success' data-dismiss='modal'> CANCEL </button>");
		
		$("#btn02").focus();
		
		$("#tombol-save").bind("click", function(event) {
			var txtusername = $('input:text[name=txt_username]').val();
			var txtpassword = $('input:password[name=txt_password]').val();
			var txtfullname = $('input:text[name=txt_fullname]').val();
			var txtemail = $('input:text[name=txt_email]').val();
			var txtket = $('input:text[name=txt_ket]').val();
			var url2="f_setup_user_aksi.php?id_us="+kodeuser+"&kd_aksi=RESET&btn_save_user=true&txt_username="+txtusername+"&txt_password="+txtpassword+"&txt_fullname="+txtfullname+"&txt_email="+txtemail+"&txt_ket="+txtket;
			
			if (txtpassword=="") {				
				$("#tempat-pesan").html("<div class='button btn-sm' ><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a> Password cannot empty !</div></div>");
				$("#btn02").focus();
			} else {

			$.post(url2, {id_us:kodeuser, kd_aksi:'RESET', txt_username: txtusername, txt_password:txtpassword, txt_fullname:txtfullname, txt_email:txtemail, txt_ket:txtket},function(data){
			
				$(".modal-content .modal-body").html(data).show();
				$(".modal-footer").html("<button class='btn btn-success' id='tombol-close' name='tombol-close'> CLOSE </button>");
		
				//$('#dialog-user').modal('hide');
			
			});
			
			}
		});

		$("#tombol-close").bind("click", function(event) {
			$('#dialog-modul').modal('hide');
		});


	//$('#dialog-user').modal('hide');
}

function company_user(kodeuser)
{
		var url1="f_setup_user_aksi.php?id_us="+kodeuser+"&kd_aksi=COMPANY";
		
		//alert("Comp button");

		$('#dialog-user').modal('show');

		$(".modal-header .modal-title").html("UBAH USER : "+kodeuser);			
		
		$.post(url1, {id_us:kodeuser,kd_aksi:'COMPANY'} ,function(data) {
			$(".modal-body").html(data).show();
			
		});

		$(".modal-footer").html("<input id='btn_save' name='btn_save_company' type='button' class='btn btn-danger' value=' SAVE '><button class='btn btn-success' id='tombol-close' name='tombol-close'> CANCEL </button>");

		$("#btn_save").bind("click", function(event) {		
			var url2="f_setup_user_aksi.php?id_us="+kodeuser+"&kd_aksi=COMPANY&btn_save_company=true";
			
			$.post(url2,$("#frmmodul").serialize(),function(data) {
				$(".modal-body").html(data).show();
				$(".modal-footer").html("<button class='btn btn-success' id='tombol-close' name='tombol-close'> CLOSE </button>");

			});
			
		});
		
		$("#tombol-close").bind("click", function(event) {
			$('#dialog-modul').modal('hide');
		});

}									  

function menu_user(kodeuser)
{
		var url1="f_setup_user_aksi.php?id_us="+kodeuser+"&kd_aksi=MENU";
		
		$('#dialog-user').modal('show');

		$(".modal-header .modal-title").html("MENU USER AUTHORITY : "+kodeuser);			
		
		$.post(url1, {id_us:kodeuser} ,function(data) {
			$(".modal-body").html(data).show();
		});

		$(".modal-footer").html("<input id='btn_save_menu' name='btn_save_menu' type='button' class='btn btn-danger' value=' SAVE '/><button class='btn btn-success' name='tombol-close' id='tombol-close'> CLOSE </button>");
		
		$("#btn_save_menu").bind("click", function(event) {		
			var url2="f_setup_user_aksi.php?id_us="+kodeuser+"&kd_aksi=MENU&btn_save_menu=true";
			
			$.post(url2,$("#frmmodul").serialize(),function(data) {
				$(".modal-body").html(data).show();
			});
		});
		
		$("#tombol-close").bind("click", function(event) {
			$('#dialog-modul').modal('hide');
		});
}									  

function pilih_sub()
{	var isi=$("#sub_menu_list").val();
	var isi_arr=isi.split('|');
	
	//alert(isi_arr[3]);
	
	$('#btn01').val(isi_arr[0]); 
	$('#btn02').val(isi_arr[1]);
	$('#btn03').val(isi_arr[2]);
	$('#btn04').val(isi_arr[3]);
	$('#btn05').val(isi_arr[4]);
	
	//alert(isi_arr[5]);
	
	//alert($('#btn06').val(isi_arr[5]));
	
	if (isi_arr[5]=='1') {
		$('#btn06').prop('checked', true);
	} else { 
		$('#btn06').prop('checked', false);
	}

}



