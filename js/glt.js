function mkeluar(nama)
{		var namanya=nama;
		var teksnya=", Anda yakin akan keluar dari system ?";
		var tanya="<b>"+namanya+"</b>"+teksnya;
	
		$('#dialog-utama').modal('show');
		$(".modal-header .modal-title").html("Logout System");			
		$(".modal-body").html(document.innerHTML=tanya);
		$(".modal-footer").html("<a href='?user_logout=true' id='tombol-ok' class='btn btn-danger'> OK </a><button class='btn btn-success' data-dismiss='modal' aria-hidden='true'>CANCEL</button>");
		
}

function mubahpass(kodeuser)
{	var url="modules/admin/mst_change_password.php";

	$('#dialog-utama').modal('show');
	$(".modal-header .modal-title").html("Change Password");			
	
	$.post(url, {user_id : kodeuser} ,function(data) {
			$(".modal-body").html(data).show();
	});
	
	$(".modal-footer").html("<button type='button' name='tombol-save' id='tombol-save' class='btn btn-danger'> CHANGE </button><button id='tombol-close' class='btn btn-success' data-dismiss='modal'>CLOSE</button>");
	$('.modal-body #btn02').focus();
	
	$("#tombol-save").bind("click", function(event) {
			var txt_username_jv = $('input:text[name=txt_username]').val();
			var txt_oldpassword_jv = $('input:password[name=txt_oldpassword]').val();
			var txt_newpassword_jv = $('input:password[name=txt_newpassword]').val();
			var txt_renewpassword_jv = $('input:password[name=txt_renewpassword]').val();
			
			var url="modules/admin/mst_change_password.php?btn_save_changepassword=true";

			if( txt_oldpassword_jv!="" && txt_newpassword_jv!="" && txt_renewpassword_jv!="" ) {							
				$.post(url,$("#frmutama").serialize(),function(data) { 							

					$(".modal-body").html(data).show(); 
					
				});	
			} else if (txt_oldpassword_jv=="") {
				
				$("#tempat-pesan").html("<div class='button btn-sm' ><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a><strong>"+txt_username_jv+"</strong>, Current Password cannot empty !</div></div>");
				$('#btn02').focus();

				
			} else if (txt_newpassword_jv=="") {
				$("#tempat-pesan").html("<div class='button btn-sm' ><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a><strong>"+txt_username_jv+"</strong>, New Password cannot empty !</div></div>");
				$('#btn03').focus();

				
			} else if (txt_renewpassword_jv=="") {
				$("#tempat-pesan").html("<div class='button btn-sm' ><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a><strong>"+txt_username_jv+"</strong>, Retype New  Password cannot empty !</div></div>");
				$('#btn04').focus();	
			} 
			
			//$('#dialog-utama').on('shown.bs.modal', function (e) {
					//var v_pesan = $('input:hidden[name=test_var]').val();
					//alert (v_pesan);
			//});
			
			
			//$(".modal-footer").html("<button class='btn btn-success' name='tombol-close' id='tombol-close' data-dismiss='modal'>CLOSE</button>");
			
	});	
				
	
}

