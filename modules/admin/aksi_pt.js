
function tambah_pt()
{	var idmenu = $('input:hidden[name=id_menu]').val();
	var url1="mst_company_edit.php?kd_aksi=ADD&id_menu="+idmenu;
	
	$('#dialog-pt').modal('show');
	$(".modal-header .modal-title").html("BUAT PERUSAHAAN BARU");			
	
	$.post(url1, {kd_aksi:'ADD'} ,function(data) {
		$(".modal-body").html(data).show();
	});
	
	$(".modal-footer").html("<input id='tombol-save' name='tombol-save' type='button' class='btn btn-danger' value=' SAVE '/><button name='tombol-close' id='tombol-close' class='btn btn-success' data-dismiss='modal'> CLOSE </button>");
	
	$("#tombol-save").bind("click", function(event) {
		var nama = $('input:text[name=kd_nmpt]').val();
		var url2="mst_company_edit.php?kd_aksi=ADD&btn_save_pt=true";
		
		if (nama!="") {
			$.post(url2,$('#frmmodul').serialize(),function(data){
				$(".modal-content .modal-body").html(data).show();	
				$(".modal-footer").html("<button name='tombol-close' id='tombol-close' class='btn btn-success'> CLOSE </button>");
					
			});
		}
		else {
			$("#tempat-pesan").html("<div class='button btn-sm'><div class='alert alert-danger alert-dismissable'><a class='close' data-dismiss='alert'><span class='glyphicon glyphicon-info-sign'></span></a> Nama Perusahaan Tidak Boleh Kosong ! </div></div>");
			$("#btn02").focus();
		}
	});	
}

function ubah_pt(kode)
{		var idmenu = $('input:hidden[name=id_menu]').val();
		var url1="mst_company_edit.php?kd_pt="+kode+"&kd_aksi=EDIT&id_menu="+idmenu;		
		
		$('#dialog-pt').modal('show');
		$(".modal-header .modal-title").html("UBAH PERUSAHAAN : "+kode);			
		
		$.post(url1,{kd_pt:kode},function(data) {
			$(".modal-body").html(data).show();
		});

		$(".modal-footer").html("<input type='button' name='tombol-save' id='tombol-save' class='btn btn-danger' value=' SAVE '/><button class='btn btn-success' data-dismiss='modal'> CANCEL </button>");
		
		$("#tombol-save").bind("click", function(event) {
			var url2="mst_company_edit.php?kd_pt="+kode+"&kd_aksi=EDIT&btn_save_pt=true";
			var v_out=$('#frmmodul').serialize();	
			
			//alert("-"+txtfullname+"-");
			
			$.post(url2,v_out,function(data){
				$(".modal-content .modal-body").html(data).show();				
				$(".modal-footer").html("<button class='btn btn-success' id='tombol-close' name='tombol-close' > CLOSE </button>");		
			}); 
				
		});		

		$("#tombol-close").bind("click", function(event) {
			$('#dialog-pt').modal('hide');									
		});		

}

function delete_pt(kode)
{		
		$('#dialog-pt').modal('show');
		$(".modal-header .modal-title").html("Nonaktifkan : "+kode);			
		
		$(".modal-body").html("Perusahaan akan dinonaktifkan ?");
		
		$(".modal-footer").html("<input id='tombol-ok' name='tombol-ok' type='button' class='btn btn-danger' value=' OK '><button class='btn btn-success' id='tombol-close' name='tombol-close' > CANCEL </button>");
		
		$("#tombol-ok").bind("click", function(event) {
			var url2="mst_company_edit.php?kd_pt="+kode+"&kd_aksi=DELETE&btn_save_pt=true";
			var v_out=$('#frmmodul').serialize();		
				
			$.post(url2, v_out ,function(data) {
				$(".modal-body").html(data).show();	
				$(".modal-footer").html("<button class='btn btn-success' id='tombol-close' name='tombol-close' > CLOSE </button>");
						
			});	
		});
		
		$("#tombol-close").bind("click", function(event) {
			$('#dialog-pt').modal('hide');
		});
}											  



