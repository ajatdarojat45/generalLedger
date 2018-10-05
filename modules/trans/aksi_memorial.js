var url;
var rowh;
var row;
var editmodeinduk;
var editmodedetail;
var rowtotal;
		
function Show_Filter(){
	//sessionStorage.setItem('ongrid','FILTER');
	//alert('test');
	if ($('#show-del').is(':checked')==false) { 
		$('#dg').datagrid('load',{
			showdel: "1",
			cari:$('#cari').searchbox('getValue')
		});		
	}	
	if ($('#show-del').is(':checked')==true) { 
		$('#dg').datagrid('load',{
			showdel: "0",			
			cari:$('#cari').searchbox('getValue')
		});
	}
}

// --------------------------------------------------------------------------------------------
function doAdd()
{
	// TAmbah Transaksi Kas Induk Baru
	
	//alert('ADD NEW');
	
	$('#dlg-induk').dialog('open').dialog('setTitle','Buat Jurnal BS Baru');
	$('#dlg-induk').dialog('center');	
	$('#fm').form('clear');
	
	// --------------Reset / kosongkan datagrid detail -----------------------------------------
	$('#dg2').datagrid('unselectAll');
	$('#dg2').datagrid('clearSelections');
	$('#dg2').datagrid('loadData',{"total":0,"rows":[]});
	// -------------------------------------------------------
	
	// Set nomor bukti pengeluaran kas sebagai default untuk transaksi baru
	$('#nbk1').textbox('setValue','JM-');
	
	// Set nomor seq mulai 001
	$('#seq1').textbox('setValue','001');
	
	mdate=new Date();
	//mtgp=mdate.getDate()+'-'+(mdate.getMonth()+1)+'-'+mdate.getFullYear();

	var y = mdate.getFullYear();
	var m = mdate.getMonth()+1;
	var d = mdate.getDate();

	var mtgp = (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
	
	$('#tgp1').datebox('setValue',mtgp);
	$('#totdeb1').numberbox('setValue',0);
	$('#totkrd1').numberbox('setValue',0);

	// Enable / Disable
	$('#nbk1').textbox('readonly',false);
	$('#tgp1').textbox('readonly',false);
	//$('#autonumber1').switchbutton('readonly',false);
	$('#btn_cf').hide();

	//window.localStorage.setItem('GLT_EDIT','BARU');
	editmodeinduk='NEW';
	url="trans_crud.php?trx=MEMORIAL&crud=NEW";	
}
// --------------------------------------------------------------------------------------------
function doEdit()
{	
	rowh=$('#dg').datagrid('getSelected');
	if (rowh==null || rowh==undefined ) {
		$.messager.alert('Perhatian','Pilih Record <b>Tabel Data Transaksi</b> dahulu !','info');
		return;	
	}
	
	nbk=rowh.nbk;
	tgp=rowh.tgp;
	acc=rowh.acc;
	ket=rowh.ket;
	
	$('#dlg-induk').dialog('open').dialog('setTitle','Jurnal BS Nomor : '+nbk);
	$('#dlg-induk').dialog('center');	
	$('#fm').form('clear');
	
	$('#dg2').datagrid('load',{
		tipe:'M',
		nbk:nbk
	});
	
	// -------------Init value
	

	mtgp=tglmdy(tgp);			
	$('#tgp1').textbox('setValue',mtgp);
	$('#nbk1').textbox('setValue',nbk);
	
	$('#totdeb1').numberbox('setValue',rowh.total);
	$('#totkrd1').numberbox('setValue',rowh.total);
	
	
	// -------------Disable/Enable value
	$('#tgp1').textbox('readonly',true);
	$('#nbk1').textbox('readonly',true);
	
	//alert(rowh.flg);
	
	if (rowh.flg==1){
		$('#btn_cf').show();
	} else {
		$('#btn_cf').hide();		
	}
	
	//$('#imgloader').hide();
	//window.localStorage.setItem('GLT_EDIT','UBAH');
	editmodeinduk='EDIT';
	url="trans_crud.php?trx=MEMORIAL&crud=EDIT";	
	//refreshDetail()	;

}
// --------------------------------------------------------------------------------------------

function doDelete()
{	
	rowh=$('#dg').datagrid('getSelected'); 
	if (rowh==null || rowh==undefined ) {
		$.messager.alert('Perhatian','Pilih Record <b>Tabel Data Transaksi</b> dahulu !','info');
		return;	
	}
	rowi=$('#dg').datagrid('getRowIndex',rowh);
	nbk=rowh.nbk;
	$.messager.confirm('Delete','Hapus Transaksi Jurnal '+nbk+' ?',function(r){
		if (r) {			
			$.post('trans_crud.php?trx=MEMORIAL&crud=DELETE',{nbk:nbk},function(result){
				if (result.success){
					$('#dg').datagrid('deleteRow',rowi);
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
// --------------------------------------------------------------------------------------------
function doPrint()
{
	rowh=$('#dg').datagrid('getSelected');
	if (rowh==null || rowh==undefined ) {
		$.messager.alert('Perhatian','Pilih Record <b>Tabel Data Transaksi</b> dahulu !','info');
		return;	
	}
	nbk=rowh.nbk;
	$('#dlg-cetak').dialog('open').dialog('setTitle','Cetak Bukti Jurnal : '+nbk);
	document.getElementById('frame_pdf').src="cetak_jurnal.php?tipe=M&nbk="+nbk;			

}

// --------------------------------------------------------------------------------------------

function doSave()
{	// -------------Save Jurnal Transaksi-----------------
	
	mtotd=$('#totdeb1').numberbox('getValue');
	mtotk=$('#totkrd1').numberbox('getValue');
	
	if (mtotd!=mtotk){
		$.messager.alert('Perhatian','Total Nilai Transaksi Belum Seimbang !','info');
		return;	
	}
	
	mdata=$('#dg2').datagrid('getRows');    // reload the user data
	rowtotal=mdata.length;
	
	if (rowtotal==0){
		$.messager.alert('Perhatian','Data kosong, isi dahulu !','info');
		return;	
	}
	
	mnbk=$('#nbk1').textbox('getValue');
	
	trcf=0;
	
	if (editmodeinduk=="EDIT"){
		// Transaksi dari cashflow	
		rowh=$('#dg').datagrid('getSelected'); 
		trcf=rowh.flg;
	}
	
	$.messager.confirm('Save','Simpan Data Tersebut ?',function(r){
		if (r){	
			$.post(url,{trcf:trcf,memorial:'MEMORIAL',nbk:mnbk,data:mdata},function(result){
				if (result.success) {
					$.messager.show({    // show error message
						title: 'Save',
						msg: 'Data berhasil disimpan !'
					});					
					$('#dg').datagrid('reload');
					$("#dlg-induk").dialog('close');
				} else {
					$.messager.show({    // show error message
						title: 'Save Error',
						msg: result.errorMsg
					});					
				}
			},'json');			
		}
	});
	
}

// --------------------------------------------------------------------------------------------

function doAddDetail()
{	// TAmbah Transaksi Kas Induk Baru
	//alert('add detail');
	tglaktif=window.localStorage.getItem('_tgl');
	tgp=$("#tgp1").datebox('getValue');
	nbk=$("#nbk1").textbox('getValue');
	
	thn=tgp.substr(6,4);
	bln=tgp.substr(3,2);
	tgi=thn+'-'+bln;

	// Cek tahun dan bulan input harus sama dengan tahun bulan aktif sistem
	if (tglaktif!=tgi){
		// Tahun dan bulan Berbeda
		$.messager.alert('Perhatian','<b>Bulan Tahun tidak sama dengan periode aktif !</b>','info');
		return ;		
	}	
	//Cek kelengkapan data header
	if (nbk==""){
		$.messager.alert('Perhatian','<b>Nomor Bukti harus dilengkapi !</b>','info');
		return ;				
	}
	
	$('#dlg-detail').dialog('open').dialog('setTitle','Tambah Detail Jurnal Kas');
	$("#dlg-detail").dialog('open').dialog('center');
	$("#fmdetail").form('clear');
	editmodedetail="NEW";
	
	var rw=$('#dg2').datagrid('getRows');
	rowtotal=rw.length;
	
	//alert(rowtotal);
	if (rowtotal==undefined || rowtotal==NaN) {
		rowtotal=0;
		//alert(rowtotal);
	}
	
	var n = rowtotal + 1;
	
	seq=String("000" + n).slice(-3); // returns 00123
	
	$("#seq2").textbox('setValue',seq);

}
// --------------------------------------------------------------------------------------------
function doEditDetail()
{	row=$("#dg2").datagrid('getSelected');
	
	if (row==null || row==undefined || row=="") {
		$.messager.alert('Perhatian','<b>Pilih Record dahulu !</b>','info');
		return;	
	}
		
	$("#dlg-detail").dialog('open').dialog('setTitle','Ubah Detail Jurnal BS');
	$("#dlg-detail").dialog('open').dialog('center');
	$("#fmdetail").form('clear');
	$("#seq2").textbox('setValue',row.seq);
	$("#accd12").combobox('setValue',row.acc);
	$("#accd22").combobox('setValue',row.acc);
	$("#accs12").combobox('setValue',row.kd_sup);
	$("#accs22").combobox('setValue',row.kd_sup);
	
	if (row.krd!=0) {
		$('#tipejurnal2').switchbutton({checked:true});
		$("#jml2").numberbox('setValue',row.krd);
	} else {
		$("#jml2").numberbox('setValue',row.deb);
	}
	
	$("#ketdet2").textbox('setValue',row.ket);
	editmodedetail="EDIT";
	
}
// --------------------------------------------------------------------------------------------
function doDeleteDetail()
{	row=$("#dg2").datagrid('getSelected');
	if (row==null || row==undefined || row=="") {
		$.messager.alert('Perhatian','<b>Pilih Record dahulu !</b>','info');
		return;	
	}
	if (row.seq=='000'){
		$.messager.alert('Perhatian','Kode <b>SEQ</b> Salah / Tidak bisa diubah !','info');
		return;
	}
	$.messager.confirm('Delete','Hapus Detail Transaksi SEQ :'+row.seq+' ?',function(r){
		if (r){	
			editmodedetail="DELETE";
			row=$('#dg2').datagrid('getSelected');
			rowi=$('#dg2').datagrid('getRowIndex',row);
			$('#dg2').datagrid('deleteRow',rowi);
			refreshDetail();
		}
	});
}
// --------------------------------------------------------------------------------------------
function doSaveDetail()
{	// Initial
	// Header
	nbk=$('#nbk1').textbox('getValue');
	tgp=thnblntgl($('#tgp1').datebox('getValue'));
	totdeb=$('#totdeb1').numberbox('getValue');
	totkrd=$('#totkrd1').numberbox('getValue');
	
	seq=$('#seq2').textbox('getValue');
	accd=$('#accd12').combo('getValue');
	accs=$('#accs12').combo('getValue');
	jml=$('#jml2').numberbox('getValue');
	ketdet=$('#ketdet2').textbox('getValue');
	tipejur=document.getElementById('tipejurnal2').checked;

	if (tipejur==true) {
		// Kredit
		jmlD=0;
		jmlK=jml;
	} else {
		//Debet
		jmlD=jml;
		jmlK=0;
	}	
	
	if (editmodedetail=='NEW'){
		// Tambah SEQ baru
		// Cek Apakah record '000' sudah ada ?
		
		var rw=$('#dg2').datagrid('getRows');
		rowtotal=rw.length;
		
		$('#dg2').datagrid('appendRow',{
			trx_status: 1,
			tgp: tgp,
			nbk: nbk,
			seq: seq,
			acc: accd,
			kd_sup: accs,
			ket: ketdet,
			deb: jmlD,
			krd: jmlK
		});					
	} else {
		// Update SEQ
		row=$('#dg2').datagrid('getSelected');
		rowi=$('#dg2').datagrid('getRowIndex',row);
		$('#dg2').datagrid('updateRow',{
			index: rowi,
			row: {
				seq: seq,
				acc: accd,
				kd_sup: accs,
				ket: ketdet,
				deb: jmlD,
				krd: jmlK
			}
		});
		$('#dg2').datagrid('selectRow',rowi);
	}
	$('#tgp1').textbox('readonly',true);
	$('#nbk1').textbox('readonly',true);
	//$('#autonumber1').switchbutton('readonly',true);
	
	$("#dlg-detail").dialog('close');
	
	refreshDetail()	;
}

function refreshDetail(){
	// Penomoran ulang kode SEQ dan rekalkulasi total nilai debet/kredit transaksi setelah penghapusan record

	var rw=$('#dg2').datagrid('getRows');
	rowtotal=rw.length;
	
	// Proses hitung ulang total nilai debet/kredit
	mtotd=0;
	mtotk=0;
	
	for (m=0;m<rowtotal;m++) {
		mseq=rw[m].seq;
		mdeb=rw[m].deb;
		mkrd=rw[m].krd;
		mtotd=mtotd+parseInt(mdeb);
		mtotk=mtotk+parseInt(mkrd);
	}
	
	// Proses penomoran ulang kode SEQ
	for (m=0;m<rowtotal;m++) {
		n=m+1;
		newseq=String("000" +n).slice(-3); // returns 00123
		
		$('#dg2').datagrid('updateRow',{
			index: m,
			row: { "seq": newseq}
		});		
	}	
	
	// Update Total Debet / Kredit Jurnal
	
		$('#totdeb1').numberbox('setValue',mtotd);
		$('#totkrd1').numberbox('setValue',mtotk);
	
}


