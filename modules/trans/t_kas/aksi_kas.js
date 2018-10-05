var url;
var rowh;
var row;

		
function Show_Filter(){
	//sessionStorage.setItem('ongrid','FILTER');
	//alert('test');
	if ($('#show-del').is(':checked')==false) { 
		$('#dg-induk').datagrid('load',{
			showdel: "1",
			cari:$('#cari').searchbox('getValue')
		});		
	}	
	if ($('#show-del').is(':checked')==true) { 
		$('#dg-induk').datagrid('load',{
			showdel: "0",			
			cari:$('#cari').searchbox('getValue')
		});
	}
}

function Show_FilterDet()
{
	if ($('#show-del-det').is(':checked')==false) { 
		$('#dg-det').datagrid('load',{
			showdel: "1"
		});		
	}	
	if ($('#show-del-det').is(':checked')==true) { 
		$('#dg-det').datagrid('load',{
			showdel: "0"			
		});
	}

}

function pesan_dialog(keterangan)
{
	$("#dialog-konfirmasi").modal('show');
	$("#myModalLabel2").html("Perhatian");			
	$("#mybody-konfirmasi").html(keterangan);			
	//$("#myfooter-konfirmasi").html("<button class='btn btn-danger' id='tombol-ok' name='tombol-ok'> OK </button><button class='btn btn-success' id='tombol-close' name='tombol-close' data-dismiss='modal' > CANCEL </button>");
	
}

function doAdd(acc,nmp)
{
	// TAmbah Transaksi Kas Induk Baru
	
	//alert('ADD NEW');
	
	$('#dlg-induk').dialog('open').dialog('setTitle','Buat Jurnal Kas Baru');
	$('#dlg-induk').dialog('center');	
	$('#fm').form('clear');
	
	// Set nomor bukti pengeluaran kas sebagai default untuk transaksi baru
	$('#nbk1').textbox('setValue','KK-');
	
	// Set nomor seq mulai 001
	$('#seq1').textbox('setValue','001');
	
	// Set Kode Perkiraan Pusat Transaksi 
	$('#acc1').combobox('setValue',acc);
	//$('#acc2').combobox('setValue',nmp);
	
	mdate=new Date();
	mtgp=mdate.getDate()+'-'+(mdate.getMonth()+1)+'-'+mdate.getFullYear()
	$('#tgp1').datebox('setValue',mtgp);

	// Enable / Disable
	$('#nbk1').textbox('readonly',false);
	$('#imgloader1').hide();

	url="trans_crud.php?trx=KAS&crud=NEW";	
}


	
function doEdit()
{	rowh=$('#dg-induk').datagrid('getSelected');
	if (rowh==null || rowh==undefined ) {
		$.messager.alert('Perhatian','Pilih Record <b>Tabel Data Transaksi</b> dahulu !','info');
		return;	
	}
	
	nbk=rowh.nbk;
	tgp=$('#tgp').textbox('getValue');
	ket=rowh.ket;
	
	$("#dlg-induk-edit").dialog('open').dialog('setTitle','Ubah Jurnal Kas');
	$('#fmedit').form('clear');
	
	// Set value selected grid
	$('#tgp2').datebox('setValue',tgp);
	$('#nbk2').textbox('setValue',nbk);
	$('#ket2').textbox('setValue',ket);
	$('#imgloader').hide();
	
	url="trans_crud.php?trx=KAS&crud=EDIT";	
}

function doDelete()
{	
	// Hapus / update field trx_status menjadi 0
	
	rowh=$('#dg-induk').datagrid('getSelected'); 
	if (rowh==null || rowh==undefined ) {
		$.messager.alert('Perhatian','Pilih Record <b>Tabel Data Transaksi</b> dahulu !','info');
		return;	
	}
	nbk=rowh.nbk;
	$.messager.confirm('Delete','Hapus Transaksi Jurnal '+nbk+' ?',function(r){
		if (r) {			
			$.post('trans_crud.php?trx=KAS&crud=DELETE',{nbk:nbk},function(result){
				if (result.success){
					$('#dg-induk').datagrid('reload');    // reload the user data
					$('#dg-det').datagrid('reload');    // reload the user data					
					$('#fu').form('reset');    // reload the user data					
					$.messager.show({    // show error message
						title: 'Delete',
						msg: 'Data berhasil dihapus !'
					});
				} else {
					$.messager.show({    // show error message
						title: 'Delete',
						msg: result.errorMsg
					});
				}
			},'json');			
		}
	});
}

function doPrint()
{
	rowh=$('#dg-induk').datagrid('getSelected');
	if (rowh==null || rowh==undefined ) {
		$.messager.alert('Perhatian','Pilih Record <b>Tabel Data Transaksi</b> dahulu !','info');
		return;	
	}
	nbk=rowh.nbk;
	$('#dlg-cetak').dialog('open').dialog('setTitle','Cetak Bukti Jurnal : '+nbk);
	document.getElementById('frame_pdf').src="cetak_jurnal.php?nbk="+nbk;			

}


function doSave()
{	//alert(url);
	ktgp=$('#tgp1').datebox('getValue');
	ktipetr=document.getElementById('tipetrans1').checked;
	knbk=document.getElementById('nbk1').value;
	kaccpus=$('#acc1').combo('getValue');
	kketpus=document.getElementById('ket1').value;
	
	kseq=document.getElementById('seq1').value;
	kaccdet=$('#accd1').combo('getValue');
	kaccsup=$('#accs1').combo('getValue');
	ktipejur=document.getElementById('tipejurnal1').checked;
	kjumlah=document.getElementById('jml1').value;
	kketdet=document.getElementById('ketdet1').value;
	
	if (ktipetr==true){ 
		ktipetr="KM";
	} else {
		ktipetr="KK";
	}
	
	if (ktipejur==false){ 
		ktipejur="Debet";
	} else {
		ktipejur="Kredit";
	}
	
	// Save Add Transaksi
	$.messager.confirm('Save','Simpan Data Tersebut ?',function(r){
		if (r){	
			$('#imgloader1').show();
			$.post(url,{tgp:ktgp,jenistrans:ktipetr,nbk:knbk,accpus:kaccpus,ketpus:kketpus,seq:kseq,accdet:kaccdet,supdet:kaccsup,tipejur:ktipejur,jumlah:kjumlah,ketdet:kketdet},function(result){				
				if (result.success){
					$.messager.show({    // show error message
						title: 'Add New',
						msg: 'Data berhasil disimpan !'
					});
					$('#dg-induk').datagrid('reload');    // reload the user data
					$('#dg-induk').datagrid('selectRow',0);    // reload the user data
					$("#dlg-induk").dialog('close');	
				} else {
					$.messager.show({    // show error message
						title: 'Add New Error',
						msg: result.errorMsg
					});
				}
				console.log(result);
			},'json');			
		}
	});
}

function doSaveEdit()
{	
	ktgp=$('#tgp2').datebox('getValue');
	knbk=document.getElementById('nbk2').value;
	kketpus=document.getElementById('ket2').value;
	
	$.messager.confirm('Save','Simpan Perubahan Data Tersebut ?',function(r){
		if (r){	
			$('#imgloader').show();
	
			$.post(url,{tgp:ktgp,nbk:knbk,ketpus:kketpus},function(result){
				if (result.success){
					$.messager.show({    // show error message
						title: 'Update',
						msg: 'Data berhasil disimpan !'
					});
					
					row=$('#dg-induk').datagrid('getSelected');    // reload the user data
					
					rowi=$('#dg-induk').datagrid('getRowIndex',row);    // reload the user data
					
					$('#dg-induk').datagrid('updateRow',{
						index: rowi,
						row: {
							tgp: myformattgl(ktgp),
							ket: kketpus
						}
					});
					
					$('#dg-induk').datagrid('selectRow',rowi);    // reload the user data
					
					$("#dlg-induk-edit").dialog('close');	
					
				} else {
					$.messager.show({    // show error message
						title: 'Error',
						msg: result.errorMsg
					});
				}
			},'json');			
		}
	});
}

function doAddDetail()
{	// TAmbah Transaksi Kas Induk Baru
	//alert('add detail');
	
	rowh=$("#dg-induk").datagrid('getSelected');
	
	if (rowh==null || rowh==undefined ) {
		$.messager.alert('Perhatian','Pilih Record <b>Tabel Data Transaksi</b> dahulu !','info');
		return;	
	}
		
	$('#dlg-detail').dialog('open').dialog('setTitle','Tambah Detail Jurnal Kas');
	$("#fmd").form('clear');
	//$('#jml2').numberbox('setValue',0);
	$("#seq2").textbox('setValue','Auto');
	//$("#acc12").textbox('setValue',row.acc);
	//$("#acc22").textbox('setValue','Auto');
	//$("#tipejurnal2").switchbutton('check');
}
	
function doEditDetail()
{	
	row=$("#dg-det").datagrid('getSelected');
	
	if (row==null || row==undefined) {
		$.messager.alert('Perhatian','<b>Pilih Record dahulu !</b>','info');
		return;	
	}
	if (row.seq=='000'){
		$.messager.alert('Perhatian','Kode <b>SEQ</b> Salah / Tidak bisa diubah !','info');
		return;
	}
	
		
	$("#dlg-detail").dialog('open').dialog('setTitle','Ubah Detail Jurnal Kas');
	$("#fmd").form('clear');
	$("#seq2").textbox('setValue',row.seq);
	$("#accd12").combobox('setValue',row.acc);
	$("#accs12").combobox('setValue',row.kd_sup);
	if (row.krd!=0) {
		$('#tipejurnal2').switchbutton({checked:true});
	}
	$("#jml2").numberbox('setValue',row.deb+row.krd);
	$("#ketdet2").textbox('setValue',row.ket);
	
}

function doDeleteDetail()
{
	$.messager.confirm('Delete','Hapus Detail Transaksi Jurnal ?',function(r){
		if (r){			
		}
	});
}



function klik_d_save()
{	
	var trans_detail=$('#tampil_badge_detail').text();
	
	var v_nbk=$("#no_bukti").val();
	var v_tgp=$("#tgl_bukti").val();
	var v_tglaktif=$("#tgl_aktif").val();
	var v_acc=$("#no_akun").val();
	var v_nmp=$("#nm_akun").val();
	var v_ket=$("#nm_ket").val();
	var v_tipe=v_nbk.substring(1,2);
		
	//alert(v_tipe);
	
	//alert(v_nbk+'-'+v_tgp+'-'+v_acc+'-'+v_nmp+'-'+v_ket+' ('+v_tipe+')');
	
	if (v_nbk=="" || v_nbk=="KK" || v_nbk=="KM") {
		$("#area_cari_data").html("<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button> <b>Nomor bukti</b> tidak boleh kosong ! </div>");
		$("#no_bukti").focus();
		$("#no_bukti").select();
		return ;
	}	
	if (v_tgp=="" || v_tgp=="--" || v_tgp.substring(3)!=v_tglaktif.substring(3) ) {
		$("#area_cari_data").html("<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button> <b>Tanggal bukti,</b> Salah (Harus Aktif dan tidak boleh kosong) ! </div>");
		return ;
		$("#tgl_bukti").focus();
		$("#tgl_bukti").select();
	}	
	
	if (v_ket=="") {
		$("#area_cari_data").html("<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button> <b>Keterangan</b> tidak boleh kosong ! </div>");
		$("#nm_ket").focus();
		return ;
	}
	
	if (v_acc=="" || v_nmp=="" ) {
		$("#area_cari_data").html("<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button> <b>Kode Akun</b> tidak boleh kosong ! </div>");
		$("#no_akun").focus();
		return ;
	}
	
	
	var d_seq=$("#d_seq").val();
	var d_acc=$("#d_no_akun").val();
	var d_nmp=$("#d_nm_akun").val();
	var d_kdsup=$("#d_no_sup").val();
	var d_nmsup=$("#d_nm_sup").val();
	var d_ket=$("#d_nm_ket").val();
		
	var xx=document.getElementsByName("jenis_dk");
	
	for (var i = 0; i < xx.length; i++) { 
		if(xx[i].checked){
        	d_dk = xx[i].value;
		}
    }
	
	//alert(d_dk);
	
	var jml=$("#d_nm_jml").val();
	
	if (jml=="") {
		jml="0";
	}
	
	var d_jml=parseFloat(jml);
	
	var tot_deb=parseFloat($('#tot_debet').val());
	
	//alert(d_seq+' - '+d_acc+' - '+d_kdsup+' - '+d_ket+' - '+d_dk+' - '+d_jml+' - '+tot_deb);
	
	if (d_seq=="") {
		$("#area_cari_data").html("<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button> <b>Nomor SEQ</b> tidak boleh kosong ! </div>");
		return ;
	}
	
	if (d_acc=="" || d_nmp=="") {
		$("#area_cari_data").html("<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button> <b>Nomor Akun Detail </b> tidak boleh kosong ! </div>");
		return ;
	}
	
	if (d_ket=="") {
		$("#area_cari_data").html("<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button> <b> Keterangan Detail</b> tidak boleh kosong ! </div>");
		$("#d_nm_ket").focus();
		return ;
	}
	
	if (d_jml==0 ) {
		$("#area_cari_data").html("<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button> <b> Jumlah Detail </b> tidak boleh kosong ! </div>");
		$("#d_nm_jml").focus();
		return ;
	}
	
	if (v_tipe=='K') {
		if (d_dk=='K') {
			$("#area_cari_data").html("<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button> <b> Debet Kredit </b> Salah ! </div>");
			return ;
		}
	}
	if (v_tipe=='M') {
		if (d_dk=='D') {
			$("#area_cari_data").html("<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>X</button> <b> Debet Kredit </b> Salah ! </div>");
			return ;
		}
	}
	
	//alert(d_seq+' - '+d_acc+' - '+d_kdsup+' - '+d_ket+' - '+jml+' - '+tot_deb);
	
	$("#area_cari_data").html("");	
	
	//return ;
	
	document.getElementById("btn_save").disabled = false;
	document.getElementById("btn_cancel").disabled = false;	

	document.getElementById("dbtn_add").disabled = false;
	document.getElementById("dbtn_save").disabled = true;
	document.getElementById("dbtn_cancel").disabled = true;	

	var xx=document.getElementsByName("btn_edit_detail");
	var xx2=document.getElementsByName("btn_delete_detail");
	
	for (var i = 0; i < xx.length; i++) { 
		xx[i].disabled= false ;
		xx2[i].disabled= false ;
	}
	
	if (trans_detail=="Add") {
		var v_get="trans_kas_save_detail.php?add_detail=true&no_bukti="+v_nbk+"&seq="+d_seq+"&tipe="+v_tipe+"&txt_tglbukti="+v_tgp;
	} else {
		var v_get="trans_kas_save_detail.php?edit_detail=true&no_bukti="+v_nbk+"&seq="+d_seq+"&tipe="+v_tipe+"&txt_tglbukti="+v_tgp;
	}

	$.post(v_get,$("#frm_kas").serialize(),function(data) {
			
		$("#table_detail").html(data).show();	

		t_debet=document.getElementById("t_debet").value;
		t_kredit=document.getElementById("t_kredit").value;
		txt_debet=document.getElementById("txt_debet").value;
		txt_kredit=document.getElementById("txt_kredit").value;
		
		//alert(d_dk);
		
		document.getElementById("jml_debet").value=txt_debet;
		document.getElementById("jml_kredit").value=txt_kredit;
				
		$("#tampil_badge_detail").html("");
		$("#area_input_detail").html("");
		
		$("#no_bukti").attr("readonly", true);
		$("#tgl_bukti").attr("readonly", true);
		$("#nm_ket").attr("readonly", true);
		
		document.getElementById("chk_trs1").disabled = true;
		document.getElementById("chk_trs2").disabled = true;
		
		var xx=document.getElementsByName("btn_edit_detail");
		var xx2=document.getElementsByName("btn_delete_detail");
		
		for (var i = 0; i < xx.length; i++) { 
			xx[i].disabled= false ;
			xx2[i].disabled= false ;
		}
		
	});
	
	
}

function klik_d_cancel()
{	
	$("#tampil_badge_detail").html("");
	
	$("#no_bukti").attr("readonly", true);
	$("#tgl_bukti").attr("readonly", true);
	$("#nm_ket").attr("readonly", true);

	document.getElementById("btn_save").disabled = false;
	document.getElementById("btn_cancel").disabled = false;	
	document.getElementById("dbtn_add").disabled = false;
	document.getElementById("dbtn_save").disabled = true;
	document.getElementById("dbtn_cancel").disabled = true;
	
	$("#area_input_detail").html("");
	
	var xx=document.getElementsByName("btn_edit_detail");
	var xx2=document.getElementsByName("btn_delete_detail");
	
	for (var i = 0; i < xx.length; i++) { 
		xx[i].disabled= false ;
		xx2[i].disabled= false ;
	}
	
	var nbk=$('#tampil_badge').text();
	var tot_deb=parseInt(document.getElementById("t_debet").value);
	
	//alert(tot_deb);
	
	// jika transaksi belum ada datanya
	if (nbk=="New" && tot_deb==0 ) {
		klik_cancel();
		reset_isi();
	}
}

function klik_d_add()
{
	//alert('ADD NEW DETAIL');
	var nbk=$('#tampil_badge').text();	
	
	var xx=document.getElementsByName("btn_edit_detail");
	var xx2=document.getElementsByName("btn_delete_detail");
	var xx3=document.getElementsByName("nomor_seq");
	
	//var isi_seq="00";
	
	for (var i = 0; i < xx.length; i++) { 
		xx[i].disabled= true ;
		xx2[i].disabled= true ;
	}
	
	noseq=0;
	for (var i = 0; i < xx3.length; i++) { 
		var noseq=parseInt(xx3[i].value);	
	}
	
	var str='00'+(noseq+1);
    var pj  = str.length; 
	var isi_seq=str.substring((pj-3), pj);
	//var isi_seq=substring(nomor_next,
	
	$("#tampil_badge_detail").html("Add");
	
	var v_nbk=$("#no_bukti").val();
	var v_tgp=$("#tgl_bukti").val();
	var v_acc=$("#no_akun").val();
	var v_ket=$("#nm_ket").val();
	
	document.getElementById("btn_save").disabled = true;
	document.getElementById("btn_cancel").disabled = true;	
	
	document.getElementById("dbtn_add").disabled = true;
	document.getElementById("dbtn_save").disabled = false;
	document.getElementById("dbtn_cancel").disabled = false;

	//$("#d_nm_jml").mask({mask: "###,###,###,###,###.##", allowPartials: true});	

	$("#area_input_detail").append("<div class='panel panel-primary alert-success' id='panel-input-detail'><div class='panel-body' id='body-detail-jurnal'> </div></div>");
	
	var get_url="trans_detail.php?kode="+isi_seq;
	
	$.post(get_url,function(data) {
		$("#body-detail-jurnal").html(data).show();
	});
	
	var jenis_trs=v_nbk.substring(0,2);

	klik_tipe_d(jenis_trs);
	
	
	//$("#d_nm_jml").inputmask("999,999,999,999,999",{radixPoint: ".",groupSeparator: ',', autoGroup: true, clearMaskOnLostFocus: true ,rightAlign: true});
	//$("#d_nm_jml").inputmask({ radixPoint: "." ,groupSeparator: ',', autoGroup: true});

	$("#btn-reset-akun").bind("click", function(event) {
		document.getElementById("d_no_akun").value="";
		document.getElementById("d_nm_akun").value="";		
	});
	$("#btn-reset-sup").bind("click", function(event) {
		document.getElementById("d_no_sup").value="";
		document.getElementById("d_nm_sup").value="";		
	});
	
}


function klik_d_edit(kode,acc,nmp,ket,kdsup,nmsup,deb,krd)
{	
	new_ket=ket;
	//var new_ket = ket.replace('"', '\&quot;' );
	//var new_ket = new_ket.replace("'", "\'" );
	
	//alert(new_ket);

	$("#tampil_badge_detail").html("Edit");

	var xx=document.getElementsByName("btn_edit_detail");
	var xx2=document.getElementsByName("btn_delete_detail");
	
	for (var i = 0; i < xx.length; i++) { 
		xx[i].disabled= true ;
		xx2[i].disabled= true ;
	}
	
	var v_deb=parseFloat(deb);
	var v_krd=parseFloat(krd);

	var v_jumlah=v_deb+v_krd;
	
	//alert(v_jumlah);
	
	var v_nbk=$("#no_bukti").val();
	var v_tgp=$("#tgl_bukti").val();
	var v_acc=$("#no_akun").val();
	var v_ket=$("#nm_ket").val();
	
	document.getElementById("btn_save").disabled = true;
	document.getElementById("btn_cancel").disabled = true;	
	document.getElementById("dbtn_add").disabled = true;
	document.getElementById("dbtn_save").disabled = false;
	document.getElementById("dbtn_cancel").disabled = false;
	
	
	
	$("#area_input_detail").html("<div class='panel panel-primary alert-success' id='panel-input-detail'><div class='panel-body' id='body-detail-jurnal'> </div></div>");
	var get_url="trans_detail.php?kode="+kode+"&acc="+acc+"&nmp="+nmp+"&new_ket="+new_ket+"&kdsup="+kdsup+"&nmsup="+nmsup+"&v_jumlah="+v_jumlah;
	
	$.post(get_url,function(data) {
		$("#body-detail-jurnal").html(data).show();
	});
	
	var jenis_trs=v_nbk.substring(0,2);

	klik_tipe_d(jenis_trs);
	
	$("#btn-reset-sup").bind("click", function(event) {
		document.getElementById("d_no_sup").value="";
		document.getElementById("d_nm_sup").value="";		
	});
	
	$("#d_seq").disabled=true;
	
}

function klik_d_delete(kode)
{
	//alert('DELETE DETAIL');
	
	var v_seq=kode;
	
	if (v_seq=="") { return ; } else {
	
		var nama_id="#hapus-detail-"+kode;
		$(nama_id).html("Apakah Detail Transaksi "+v_seq+" akan dihapus ? <button id='btn-del-detail' type='button' class='btn btn-danger btn-sm' onclick=\"klik_row_delete('"+v_seq+"','"+nama_id+"')\" >DELETE</button> <button id='btn-del-detail-cancel' type='button' class='btn btn-success btn-sm' onclick=\"klik_row_cancel('"+v_seq+"','"+nama_id+"')\" >CANCEL</button> ");			
		
	}
	
}

function klik_row_cancel(kode,nama_id)
{
	$(nama_id).html("");	
}

function klik_row_delete(kode,nama_id)
{
	$(nama_id).html("");
	
	var v_nbk=$("#no_bukti").val();
	var v_tgp=$("#tgl_bukti").val();
	var v_tipe=v_nbk.substring(1,2);
	var d_seq=kode;

	var v_get="trans_kas_save_detail.php?delete_detail=true&no_bukti="+v_nbk+"&seq="+d_seq+"&tipe="+v_tipe+"&txt_tglbukti="+v_tgp;
	
	$.post(v_get,$("#frm_kas").serialize(),function(data) {
			
		$("#table_detail").html(data).show();	

		t_debet=document.getElementById("t_debet").value;
		t_kredit=document.getElementById("t_kredit").value;
		txt_debet=document.getElementById("txt_debet").value;
		txt_kredit=document.getElementById("txt_kredit").value;
		
		document.getElementById("jml_debet").value=txt_debet;
		document.getElementById("jml_kredit").value=txt_kredit;
		
		document.getElementById("tot_debet").value=t_debet;
		document.getElementById("tot_kredit").value=t_kredit;
		
		var xx=document.getElementsByName("btn_edit_detail");
		var xx2=document.getElementsByName("btn_delete_detail");
		
		for (var i = 0; i < xx.length; i++) { 
			xx[i].disabled= false ;
			xx2[i].disabled= false ;
		}

	});
}



function klik_delete()
{	var v_nbk=$("#no_bukti").val();
	var idmenu=$("#id-menu").val();
	
	if (v_nbk=="") { 
		return  ; 
	} else {
		$("#dialog-hapus").modal('show');
		$("#myheader-hapus").html("<h3><span class='glyphicon glyphicon-trash'></span> DELETE TRANSACTION</h3>");			
		$("#mybody-hapus").html("<h4>Apakah Nomor Bukti "+v_nbk+" akan dihapus ?</h4>");			
		$("#myfooter-hapus").html("<button class='btn btn-danger' id='tombol-hapus' name=\"tombol_hapus\" value=\""+v_nbk+"\"><span class='glyphicon glyphicon-trash'></span> DELETE </button>  <button class='btn btn-success' id='tombol-cancel' data-dismiss='modal'> CANCEL </button>");
	}
}

function klik_save()
{	
	tombol_reset();	
	alert('SAVE DATA');
	return false;
}

function reset_isi()
{	
	document.getElementById("no_bukti").value="";
	//document.getElementById("tgl_bukti").value="";
	document.getElementById("nm_ket").value="";	
	//document.getElementById("no_akun").value="";	
	//document.getElementById("nm_akun").value="";	
	document.getElementById("jml_debet").value="0.00";	
	document.getElementById("jml_kredit").value="0.00";
	document.getElementById("t_debet").value="0";	
	document.getElementById("t_kredit").value="0";
	document.getElementById("txt_debet").value="0.00";	
	document.getElementById("txt_kredit").value="0.00";
	
	$('#tampil_badge').html("");	

}

function klik_cancel()
{	
	var isi_badge=$('#tampil_badge').text();

	//alert(isi_badge);
	
	if (isi_badge=="New") { reset_isi(); }
	//$('#frame_utama').load();
	
	tombol_reset();	
	
	var v_get="trans_kas_reset_detail.php?kode_reset="+isi_badge;
	
	$.post(v_get,function(data) {
			
		$("#table_detail").html(data).show();	

		t_debet=document.getElementById("t_debet").value;
		t_kredit=document.getElementById("t_kredit").value;
		txt_debet=document.getElementById("txt_debet").value;
		txt_kredit=document.getElementById("txt_kredit").value;
		
		v_ket=document.getElementById("h_ket").value;
		v_tglbukti=document.getElementById("h_tgl_bukti").value;
		
		//alert(v_tglbukti);
		
		document.getElementById("jml_debet").value=txt_debet;
		document.getElementById("jml_kredit").value=txt_kredit;
		
		document.getElementById("nm_ket").value=v_ket;
		document.getElementById("tgl_bukti").value=v_tglbukti;
	
		var xx=document.getElementsByName("btn_edit_detail");
		var xx2=document.getElementsByName("btn_delete_detail");
		
		for (var i = 0; i < xx.length; i++) { 
			xx[i].disabled= true ;
			xx2[i].disabled= true ;
		}
		
	});
}

function klik_print()
{
	var v_nbk=$("#no_bukti").val();
	
	if (v_nbk=="") { return ; } else {
		
		$("#dialog-konfirmasi").modal('show');
		$(".modal-header .modal-title").html("<span class='glyphicon glyphicon-print'></span> Print");			
		$("#mybody-konfirmasi").html("<h4>Apakah Nomor Bukti "+v_nbk+" akan dicetak ?</h4>");			
		$("#myfooter-konfirmasi").html("<button class='btn btn-danger' type='button' id=\"tombol-ok\" name='tmbl-ok'><span class='glyphicon glyphicon-print'></span>  OK </button><button class='btn btn-success' id='tombol-close' name='tombol-close' data-dismiss='modal' > CANCEL </button>");

		$("#tombol-ok").bind("click", function(event) {
			$("#dialog-konfirmasi").modal('hide');
			window.print();
		});
	
	}
}
function tombol_add_edit()
{	document.getElementById("btn_add").disabled = true;
	document.getElementById("btn_edit").disabled = true;
	document.getElementById("btn_delete").disabled = true;
	document.getElementById("btn_save").disabled = false;
	document.getElementById("btn_cancel").disabled = false;
	document.getElementById("btn_print").disabled = true;
	document.getElementById("btn_find").disabled = true;
	
	document.getElementById("dbtn_add").disabled = false;
	//alert('SAVE CANCEL..');
}

function tombol_reset()
{	var a_add=$("#id-add").val();
	var a_edit=$("#id-edit").val();
	var a_del=$("#id-delete").val();
	if (a_add=="1"){
		document.getElementById("btn_add").disabled = false;
	} else {
		document.getElementById("btn_add").disabled = true;
	}
	
	if (a_edit=="2"){
		document.getElementById("btn_edit").disabled = false;
	} else {
		document.getElementById("btn_edit").disabled = true;
	}

	if (a_del=="3"){
		document.getElementById("btn_delete").disabled = false;
	} else {
		document.getElementById("btn_delete").disabled = true;
	}
	document.getElementById("btn_save").disabled = true;
	document.getElementById("btn_cancel").disabled = true;
	document.getElementById("btn_print").disabled = false;
	document.getElementById("btn_find").disabled = false;
	document.getElementById("cari_nama").disabled = true;
	document.getElementById("chk_trs1").disabled = true;
	document.getElementById("chk_trs2").disabled = true;
	
	document.getElementById("dbtn_add").disabled = true;

	document.getElementById("no_bukti").disabled = true;
	document.getElementById("tgl_bukti").disabled = true;
	document.getElementById("nm_ket").disabled = true;
	
	//alert('RESET BUTTON');
}

function tombol_reset_all()
{	
	tombol_reset();
}

function klik_find()
{
	//alert('FIND TRANSACTION');
	var url="trans_cari.php?jenis_trans=Kas";
			
	$('#dialog-modul').modal('show');
	
		
	$(".modal-header .modal-title").html("<h4><span class='glyphicon glyphicon-search'></span> CARI TRANSAKSI KAS</h4><div class='form-inline'><input class='form-control' name='f_kd_nbk' type='text' value='' size='20' maxlength='20' placeholder='no bukti ?'> <input class='form-control' id=\"cari_tgl\" name='f_kd_tgp' type='text' value='' size='10' maxlength='10' placeholder='Tgl.Bukti ?'> <input class='form-control' name='f_kd_ket' type='text' value='' size='40' maxlength='60' placeholder='Keterangan ?'> <button type='button' id='tombol-find' name='tombol-find' class='form-control btn btn-danger btn-sm' data-loading-text=\"Searching...\"><span class='glyphicon glyphicon-search'></span> FIND</button></div>");			
	
	$("#cari_tgl").datepicker({dateFormat: "dd-mm-yy"});	
	
	$(".modal-body").load(url);	
	
	$(".modal-footer").html("<button class='btn btn-success btn-sm' id='tombol-close' name='tombol-close' data-dismiss='modal' > CLOSE </button>");
	
	$("#tombol-find").bind("click", function(event) {
		var btn=$(this);
		
		//alert('FIND');
		btn.button('loading');
		
		var url="trans_cari.php?jenis_trans=Kas&btn_go_find=true";
		
		$.post(url,$("#frmmodul").serialize(),function(data) {
		
			$(".modal-body").html(data).show();	
			btn.button('reset');
		});
	});	
}

function cari_akun(cari_data,kode)
{	
	if (kode==1) {
		var ket="Cari Akun Pusat";
		var url="tabel_cari_akun.php?btn_go_find=true&jenis_trans=Kas";
	} else {
		var ket="Cari Akun Detail";
		var url="tabel_cari_akun_detail.php?btn_go_find=true&jenis_trans=Kas";
	} 
	
	$("#area_cari_data").html("<div class='panel panel-primary' id='panel-cari-data'><div class='panel-body' id='body-cari-data'></div></div>");
	$('#body-cari-data').html(ket+"<div class='form-inline'><br>Kode : <input class='form-control' id='jv_kode' name='f_kd_acc' type='text' value='' size='20' maxlength='15'> Nama : <input class='form-control' id='jv_nama' name='f_kd_nmp' type='text' value='' size='70' maxlength='50'> <button type='button' id='tombol-cari-akun' name='tombol-find-akun' class='btn btn-danger btn-sm' data-loading-text=\"Searching...\"><span class='glyphicon glyphicon-search'></span> FIND</button> <button type='button' id='tombol-close-cari' name='tombol-close-cari' class='btn btn-danger btn-sm'> CLOSE </button></div>");
	
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
		document.getElementById("no_bukti").value=kode;
		document.getElementById("no_bukti").focus();
	}
}

function klik_tipe_d(kode) {
	if (kode=='KK') {
		document.getElementById("chk_dk1").checked=true;
	} else {
		document.getElementById("chk_dk2").checked=true;
	}
}