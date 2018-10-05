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
			$('#dialog-user').modal('hide');
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
			$('#dialog-user').modal('hide');
		});
}											  

function ubah_user(kodeuser)
{		var idmenu = $('input:hidden[name=id_menu]').val();
		
		var url1="f_setup_user_aksi.php?id_us="+kodeuser+"&kd_aksi=EDIT&id_menu="+idmenu;		
		
		$('#dialog-user').modal('show');
		$(".modal-header .modal-title").html("UBAH USER : "+kodeuser);			
		
		$.post(url1, {id_us:kodeuser} ,function(data) {
			$(".modal-body").html(data).show();
		});

		$(".modal-footer").html("<input type='button' name='tombol-save' id='tombol-save' class='btn btn-danger' value=' SAVE '/><button class='btn btn-success' data-dismiss='modal'> CANCEL </button>");
		
		$("#tombol-save").bind("click", function(event) {
			var txtfullname = $('input:text[name=txt_fullname]').val();
			var txtemail = $('input:text[name=txt_email]').val();
			var txtket = $('input:text[name=txt_ket]').val();
			
			var url2="f_setup_user_aksi.php?id_us="+kodeuser+"&kd_aksi=EDIT&btn_save_user=true";
			
			var v_out=$('#frmmodul').serialize();	
			
			//alert("-"+txtfullname+"-");
			
			if (txtfullname!="") {		
				$.post(url2,v_out,function(data){
					$(".modal-content .modal-body").html(data).show();				
					$(".modal-footer").html("<button class='btn btn-success' id='tombol-close' name='tombol-close' > CLOSE </button>");		
				}); 
			} 
			if (txtfullname=="") {
				$("#tempat-pesan").html("<div class='button btn-sm' ><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a> Fullname cannot empty !</div></div>");
				$("#btn03").focus();
			}
			
		});		

		$("#tombol-close").bind("click", function(event) {
			$('#dialog-user').modal('hide');									
		});		

}

function tambah_user()
{	var idmenu = $('input:hidden[name=id_menu]').val();
	var url1="f_setup_user_aksi.php?kd_aksi=ADD&id_menu="+idmenu;
	var url2="f_setup_user_aksi.php?kd_aksi=ADD&id_menu="+idmenu;
	
	$('#dialog-user').modal('show');
	$(".modal-header .modal-title").html("BUAT USER BARU");			
	
	$.post(url1, {kd_aksi:'ADD'} ,function(data) {
		$(".modal-body").html(data).show();
	});
	
	
	$(".modal-footer").html("<input id='tombol-save' name='tombol-save' type='button' class='btn btn-danger' value=' SAVE '/><button class='btn btn-success' data-dismiss='modal'> CANCEL </button>");
	
	$("#tombol-save").bind("click", function(event) {
		var txtusername = $('input:text[name=txt_username]').val();
		var txtpassword = $('input:password[name=txt_password]').val();
		var txtfullname = $('input:text[name=txt_fullname]').val();
		var txtemail = $('input:text[name=txt_email]').val();
		var txtket = $('input:text[name=txt_ket]').val();
		
		var url2="f_setup_user_aksi.php?id_us="+txtusername+"&kd_aksi=ADD&btn_save_user=true";
		
		var v_out=$('#frmmodul').serialize();
		
		if (txtusername!="" && txtpassword!="" && txtfullname!="") {
			$.post(url2, v_out,function(data){
			
			$(".modal-content .modal-body").html(data).show();
			$(".modal-footer").html("<button class='btn btn-success' id='tombol-close' name='tombol-close'> CLOSE </button>");
	
			//$('#dialog-user').modal('hide');
			
			});
		}

		if (txtfullname=="") {
				
				$("#tempat-pesan").html("<div class='button btn-sm' ><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a> Fullname cannot empty !</div></div>");
				$("#btn03").focus();
				}

		if (txtpassword=="") {
				
				$("#tempat-pesan").html("<div class='button btn-sm' ><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a> Password cannot empty !</div></div>");
				$("#btn02").focus();
				}

		if (txtusername=="") {
				
				$("#tempat-pesan").html("<div class='button btn-sm' ><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a> User ID. cannot empty !</div></div>");
				$("#btn01").focus();
			}

		//alert('saved new!!');
	});

	$("#tombol-close").bind("click", function(event) {
		$('#dialog-user').modal('hide');
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
			$('#dialog-user').modal('hide');
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
			$('#dialog-user').modal('hide');
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
			$('#dialog-user').modal('hide');
		});
}									  


