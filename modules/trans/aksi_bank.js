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
	
	$('#dlg-induk').dialog('open').dialog('setTitle','Buat Jurnal Bank Baru');
	$('#dlg-induk').dialog('center');	
	$('#fm').form('clear');
	
	// --------------Reset / kosongkan datagrid detail -----------------------------------------
	$('#dg2').datagrid('unselectAll');
	$('#dg2').datagrid('clearSelections');
	$('#dg2').datagrid('loadData',{"total":0,"rows":[]});
	// -------------------------------------------------------
	
	// Set nomor bukti pengeluaran kas sebagai default untuk transaksi baru
	$('#nbk1').textbox('setValue','BK');
	
	// Set nomor seq mulai 001
	$('#seq1').textbox('setValue','001');
	
	acc=window.localStorage.getItem('_acc2');
	nmp=window.localStorage.getItem('_nmp2');
	
	// Set Kode Perkiraan Pusat Transaksi 
	$('#acc1').combobox('setValue',acc);
	$('#acc2').combobox('setValue',nmp);
	
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
	$('#acc1').combobox('readonly',false);
	$('#acc2').combobox('readonly',false);
	$('#ket1').textbox('readonly',false);
	$('#tipetrans1').switchbutton('readonly',false);
	$('#autonumber1').switchbutton('readonly',false);
	$('#btn_cf').hide();

	//window.localStorage.setItem('GLT_EDIT','BARU');
	editmodeinduk='NEW';
	url="trans_crud.php?trx=BANK&crud=NEW";	
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
	
	$('#dlg-induk').dialog('open').dialog('setTitle','Jurnal Bank Nomor : '+nbk);
	$('#dlg-induk').dialog('center');	
	$('#fm').form('clear');
	
	$('#dg2').datagrid('load',{
		tipe:'B',
		nbk:nbk
	});
	
	// -------------Init value
	mnbk=nbk.substring(0,2);
	
	if (mnbk=='BM') {
		$('#tipetrans1').switchbutton('check');
	} else {
		$('#tipetrans1').switchbutton('uncheck');
	}

	mtgp=tglmdy(tgp);			
	$('#tgp1').textbox('setValue',mtgp);
	$('#nbk1').textbox('setValue',nbk);
	$('#acc1').combobox('setValue',acc);
	$('#acc2').combobox('setValue',acc);
	
	$('#totdeb1').numberbox('setValue',rowh.total);
	$('#totkrd1').numberbox('setValue',rowh.total);
	$('#ket1').textbox('setValue',ket);
	
	
	// -------------Disable/Enable value
	$('#tgp1').textbox('readonly',true);
	$('#nbk1').textbox('readonly',true);
	$('#acc1').combobox('readonly',true);
	$('#acc2').combobox('readonly',true);
	$('#ket1').textbox('readonly',true);
	$('#tipetrans1').switchbutton('readonly',true);
	$('#autonumber1').switchbutton('readonly',true);
	
	//alert(rowh.flg);
	
	if (rowh.flg==1){
		$('#btn_cf').show();
	} else {
		$('#btn_cf').hide();		
	}
	
	//$('#imgloader').hide();
	//window.localStorage.setItem('GLT_EDIT','UBAH');
	editmodeinduk='EDIT';
	url="trans_crud.php?trx=BANK&crud=EDIT";	
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
			$.post('trans_crud.php?trx=BANK&crud=DELETE',{nbk:nbk},function(result){
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
	document.getElementById('frame_pdf').src="cetak_jurnal.php?tipe=B&nbk="+nbk;			

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
	tipetrans=document.getElementById('tipetrans1').checked;
	
	trcf=0;
	
	if (editmodeinduk=="EDIT"){
		// Transaksi dari cashflow	
		rowh=$('#dg').datagrid('getSelected'); 
		trcf=rowh.flg;
	}
	
	if (tipetrans==true) {
		// Penerimaan 
		tipe='BM';
	} else {
		//Pengeluaran 
		tipe='BK';
	}	
	
	$.messager.confirm('Save','Simpan Data Tersebut ?',function(r){
		if (r){	
			$.post(url,{trcf:trcf,jenistrans:tipe,nbk:mnbk,data:mdata},function(result){
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
	acc=$("#acc1").combobox('getValue');
	nmp=$("#acc2").combobox('getValue');
	ket=$("#ket1").textbox('getValue');
	
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
	if (nbk=="BK" || nbk=="BM"){
		$.messager.alert('Perhatian','<b>Nomor Bukti harus dilengkapi !</b>','info');
		return ;				
	}
	if (acc=="" ){
		$.messager.alert('Perhatian','<b>Kode Akun Pusat Tidak Boleh Kosong !</b>','info');
		return ;				
	}	
	if (ket==""){
		$.messager.alert('Perhatian','<b>Keterangan tidak boleh kosong !</b>','info');
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
		rowtotal=1;
		//alert(rowtotal);
	}
	
	if (rowtotal==0){
		// Untuk Jurnal Kas dan Bank kode SEQ dimulai dari 000
		// Kode SEQ 000 dibuat otomatis bersamaan dengan input row baru 001
		// Jika rowtotal = 2 maka kode seq berikutnya adalah 002 karena dimulai dari 000-001 berikutnya adalah 002
		rowtotal=1;
	}
	
	var n = rowtotal;

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
	if (row.seq=='000'){
		$.messager.alert('Perhatian','Kode <b>SEQ</b> Salah / Tidak bisa diubah !','info');
		return;
	}
		
	$("#dlg-detail").dialog('open').dialog('setTitle','Ubah Detail Jurnal Kas');
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
	acc=$('#acc1').combo('getValue');
	ketpus=$('#ket1').textbox('getValue');
	totdeb=$('#totdeb1').numberbox('getValue');
	totkrd=$('#totkrd1').numberbox('getValue');
	
	seq=$('#seq2').textbox('getValue');
	accd=$('#accd12').combo('getValue');
	accs=$('#accs12').combo('getValue');
	jml=$('#jml2').numberbox('getValue');
	ketdet=$('#ketdet2').textbox('getValue');
	tipejur=document.getElementById('tipejurnal2').checked;
	tipetrans=document.getElementById('tipetrans1').checked;
	
	if (tipetrans==true) {
		// Penerimaan 
		jmlpusatD=jml;
		jmlpusatK=0;
	} else {
		//Pengeluaran 
		jmlpusatD=0;
		jmlpusatK=jml;
	}	

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
		
		if (seq=='001' && rowtotal==0){
			// Otomatis record akun pusat
			$('#dg2').datagrid('appendRow',{
				trx_status: 1,
				tgp: tgp,
				nbk: nbk,
				seq: '000',
				acc: acc,
				kd_sup: '',
				ket: ketpus,
				deb: jmlpusatD,
				krd: jmlpusatK
			});		
		}
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
	$('#acc1').combobox('readonly',true);
	$('#acc2').combobox('readonly',true);
	$('#ket1').textbox('readonly',true);
	$('#tipetrans1').switchbutton('readonly',true);
	$('#autonumber1').switchbutton('readonly',true);
	
	$("#dlg-detail").dialog('close');
	
	refreshDetail()	;
}

function refreshDetail(){
	// Penomoran ulang kode SEQ dan rekalkulasi total nilai debet/kredit transaksi setelah penghapusan record
	tipetrans=document.getElementById('tipetrans1').checked;
	
	var rw=$('#dg2').datagrid('getRows');
	rowtotal=rw.length;
	
	// Proses hitung ulang total nilai debet/kredit
	mtotd=0;
	mtotk=0;
	
	for (m=0;m<rowtotal;m++) {
		mseq=rw[m].seq;
		if (mseq!='000'){
			mdeb=rw[m].deb;
			mkrd=rw[m].krd;
			mtotd=mtotd+parseInt(mdeb);
			mtotk=mtotk+parseInt(mkrd);
		}
	}
	
	if (tipetrans==true){
		// Penerimaan Kas
		mtot=mtotk-mtotd;
		mdebet=mtotk;
		mkredit=0;
	} else {
		// Pengeluaran Kas
		mtot=mtotd-mtotk;
		mdebet=0;
		mkredit=mtot;
	}
	
	// Update Hasil Kalkulasi Debet/Kredit ke seq 000 / pusat jurnal
	$('#dg2').datagrid('updateRow',{
		index: 0,
		row: { deb: mdebet,krd:mkredit}
	});
	
	// Proses penomoran ulang kode SEQ
	for (m=0;m<rowtotal;m++) {
		newseq=String("000" + m).slice(-3); // returns 00123
		
		$('#dg2').datagrid('updateRow',{
			index: m,
			row: { "seq": newseq}
		});		
	}	
	
	// Update Total Debet / Kredit Jurnal
	if (tipetrans==true){
		$('#totdeb1').numberbox('setValue',mtotk);
		$('#totkrd1').numberbox('setValue',mtotk);
	} else {
		$('#totdeb1').numberbox('setValue',mtotd);
		$('#totkrd1').numberbox('setValue',mtotd);		
	}
}


