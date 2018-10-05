function klik_all()
{	if ($("#btn_process").val()=="") {
		return ;
	}
	
	//alert('check/uncheck all');
	
	var v_proses=$("#btn_cancel").val();	
	var jml_rec=$("#jml_rec").val();
		
	for (m=1; m < jml_rec ; m++) {
		if (v_proses=="posting") {
			
			var a_post="#area-posting-"+m;		
			
			if ($(a_post).text()=="") {
				var a_cek="#post_"+m;
				//alert(a_cek);
				$(a_cek).prop('checked', true) ; 
			} 			
		} else {
			
			var a_post="#area-posting-"+m;		
			
			if ($(a_post).text()=="") {
				var a_cek="#unpost_"+m;
				//alert(a_cek);
				$(a_cek).prop('checked', false)					
			}
		}
	}
	
}

function klik_posting()
{	$("#badge_cf").html("POSTING");

	document.getElementById("btn_process").value="posting";
	document.getElementById("btn_cancel").value="posting";

	document.getElementById("btn_posting").disabled = true;
	document.getElementById("btn_unposting").disabled = true;
	document.getElementById("btn_process").disabled = false;
	document.getElementById("btn_cancel").disabled = false;

	document.getElementById("btn_sistem").disabled = true;
	document.getElementById("btn_jenis").disabled = true;
	
	var jml_rec=$("#jml_rec").val();
	//alert(jml_rec);
	
	for (m=1; m < jml_rec ; m++) {
		var a_post="#area-posting-"+m;
		var a_nbk=$("#nbk_"+m).text();
		
		//alert(a_post);
		
		if ($(a_post).text()=="-") {
			$(a_post).html("<input type='checkbox' name='post_"+m+"' id='post_"+m+"' value='"+a_nbk+"'>");
		}
	}
	
}

function klik_unposting()
{	$("#badge_cf").html("CANCEL POSTING");

	document.getElementById("btn_process").value="unposting";
	document.getElementById("btn_cancel").value="unposting";

	document.getElementById("btn_posting").disabled = true;
	document.getElementById("btn_unposting").disabled = true;
	document.getElementById("btn_process").disabled = false;
	document.getElementById("btn_cancel").disabled = false;

	document.getElementById("btn_sistem").disabled = true;
	document.getElementById("btn_jenis").disabled = true;
	
	var jml_rec=$("#jml_rec").val();
	
	//alert(jml_rec);
	
	for (m=1; m < jml_rec ; m++) {
		var a_post="#area-posting-"+m;
		var a_nbk=$("#nbk_"+m).text();
		
		//alert($(a_post).text());
		
		if ($(a_post).text()!="-") {
			$(a_post).html("<input type='checkbox' name='unpost_"+m+"' id='unpost_"+m+"' value='"+a_nbk+"' checked>");
		}
	}
		
}

function klik_cancel_post() 
{	$("#badge_cf").html("");

	document.getElementById("btn_posting").disabled = false;
	document.getElementById("btn_unposting").disabled = false;
	document.getElementById("btn_process").disabled = true;
	document.getElementById("btn_cancel").disabled = true;

	document.getElementById("btn_sistem").disabled = false;
	document.getElementById("btn_jenis").disabled = false;
	
	var v_proses=$("#btn_cancel").val();
	var jml_rec=$("#jml_rec").val();
	
	//alert(v_proses);
	
	for (m=1; m < jml_rec ; m++) {
		if (v_proses=="posting") {
			
			var a_post="#area-posting-"+m;		
			
			//alert("-"+$(a_post).text()+"-");
			
			if ($(a_post).text()=="") {
				$(a_post).html("-");						
			} 			
		} else {
			
			var a_post="#area-posting-"+m;		
			
			if ($(a_post).text()=="") {
				$(a_post).html("&radic;");						
			}
		}
	}
	
	document.getElementById("btn_process").value="";
	document.getElementById("btn_cancel").value="";
	
}

function view_detail(kode_nbk,tgl_nbk)
{	
	$("#dialog-modul").modal('show');
	$("#myModalLabel1").html('<h4>Nomor Bukti : '+kode_nbk+'   Tanggal Bukti : '+tgl_nbk+'</h4>');
	$("#mybody-dialog").html("");
	
	var url="trans_cashflow_show_detail.php?nbk="+kode_nbk;

	$.post(url,function(data) {
		$("#mybody-dialog").html(data).show();
	});
	
	$("#myfooter-dialog").html("<button type='button' id='tombol-close' name='tombol-close' class='btn btn-success btn-sm' data-dismiss='modal'> CLOSE </button>");
	
}

function edit_detail(kode_nbk)
{	
	//$("#dialog-modul").modal('show');
	
}


function klik_proses()
{	var v_isi=$("#btn_process").val();
	
	$("#area_proses").html("<br><br><p class='text-center'>Proses "+v_isi+" ? <button type='submit' class='btn btn-danger' name='btn_ok' id='btn_ok' value=\""+v_isi+"\" data-loading-text=\"Processing...\">OK</button> <button class='btn btn-success' type='button' id=\"btn_batal\">CANCEL</button></p><br>");

	$("#btn_ok").bind("click",function(event) {
		var btn=$(this);
		btn.button('loading');
	});
		
	$("#btn_batal").bind("click",function(event) {
		klik_cancel_post() ;
		$("#area_proses").html("");
	});
	
}
