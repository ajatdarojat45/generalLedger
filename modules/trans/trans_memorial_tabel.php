<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {
	include "../inc/inc_akses.php";
	include "../inc/func_modul.php";
	//include "../inc/inc_aed.php";	
	$jenis_trans="MEMORIAL";
	$tbl_view_trx="trans_memorial".$company_id ; 
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tbl_mst_sup="mst_sup_".$company_id ;
	
	$tbl_trx_temp="trx_tmp_caseglt_".$company_id ;
	$tbl_trx="trx_caseglt_".$company_id ;
	 
	$tgl_trans=substr($aktif_tgl,0,7);
	
	echo "<script>window.localStorage.setItem('_tgl','$tgl_trans')</script>";
	
?>
	
	<table id="dg" class="easyui-datagrid" title="" style="width:100%;height:96%;padding:0px;"
			data-options="rownumbers:true,singleSelect:true,url:'get_trans.php?tipe=M',pagination:true,pageSize:50,pageList:[50,100,250,500,1000,2000],
							rowStyler: function(index,row){
								if (row.trx_status!=1){
									return 'background-color:#999999;color:#fff;font-weight:bold;text-decoration: line-through;';
								}
							},toolbar:'#tb'">
		<thead>
			<tr>
				<th data-options="field:'tgp',resizable:true,align:'left',sortable:true,formatter:myformattgl" width="100px"><strong>Tanggal</strong></th>
				<th data-options="field:'nbk',resizable:true,align:'left',sortable:true" width="150px"><strong>Nomor Bukti</strong></th>
				<th data-options="field:'total',resizable:true,align:'right',sortable:true,formatter:formatangkaduadec" width="150px"><strong>Jumlah</strong></th>
				<th data-options="field:'ket',resizable:true,align:'left',sortable:true" width="600px"><strong>Keterangan</strong></th>
				<th data-options="field:'flg',resizable:true,align:'left',sortable:true" width="100px"><strong>Cashflow</strong></th>
			</tr>
		</thead>
		<div id="tb" style="padding:2px 5px;">
				<a id="btn_add" href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="doAdd('<?php echo $acc ?>','<?php echo $nmp ?>')">ADD NEW</a>
				<span class="button-sep"></span> <a id="btn_edit" href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="doEdit()">EDIT</a>
				<span class="button-sep"></span> <a id="btn_del" href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="doDelete()">DELETE</a>
				<span class="button-sep"></span> <a id="btn_prn" href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="doPrint()">PRINT</a>
				<span class="button-sep"></span> <input id="cari" class="easyui-searchbox" data-options="prompt:'Find..',searcher:Show_Filter" style="width:200px;height:26px"></input>
				<span class="button-sep"></span> <a id="btn_reload" href="" class="easyui-linkbutton" iconCls="icon-reload" plain="true" >Refresh View</a>
		</div>		
	</table>
	
	<!---------------------------------- Dialog FORM  Add Edit --------------------------------------------------->
	<div id="dlg-induk" class="easyui-dialog" style="width:80%;padding:10px 10px" 
            closed="true" buttons="#btn-induk" modal="true" draggable="true" title="Transaksi Jurnal Memorial"> 
		<form id="fm" method="post"> <!--novalidate-->
			<table width="100%">
				<tr height="30px">
					<td align="right" width="120px"><label>Tanggal Bukti : &nbsp;&nbsp;</label></td>
					<td> 
						<input id="tgp1" name="tglbukti1" class="easyui-datebox" type="text" id="trx_date" name="trx_date" 
						data-options="required:true,formatter:myformatter,parser:myparser" style="width:120px" required="true"/> 
						<a id="btn_cf" href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" data-options="disabled:false"> Dari Cashflow</a>
					</td>
					<td><label>Total Debet : &nbsp;&nbsp;</label>
						<input id="totdeb1" name="totaldebet" class="easyui-numberbox" style="width:150px;text-align:right" 
						data-options="groupSeparator:',',decimalSeparator:'.',precision:2" tooltip="true" readonly='true'/>
					</td>
				</tr>
				<tr height="30px">
					<td align="right"><label>Nomor Bukti : &nbsp;&nbsp;</label></td>
					<td> <input id="nbk1" name="nobukti1" class="easyui-textbox" style="width:200px;" tooltip="true"> 
						<!--<input id="autonumber1" class="easyui-switchbutton" data-options="onText:'Auto Number',offText:'Manual',width:'100px',
								onChange:function(checked){
									if (checked){
										$('#nbk1').textbox('readonly',true);
										$('#nbk1').textbox('setValue','Auto Number');										
									} else {
										if (document.getElementById('tipetrans1').checked==false){
											$('#nbk1').textbox('readonly',false);
											$('#nbk1').textbox('setValue','BS');
										} else {
											$('#nbk1').textbox('readonly',false);
											$('#nbk1').textbox('setValue','PB');											
										}
									}}"/>-->
					</td>					
					<td><label>Total Kredit : &nbsp;&nbsp;</label>
						<input id="totkrd1" name="totalkredit" class="easyui-numberbox" style="width:150px;text-align:right" 
						data-options="groupSeparator:',',decimalSeparator:'.',precision:2" tooltip="true" readonly='true'/>
					</td>
				</tr>			
			</table>
		</form>
		<table id="dg2" class="easyui-datagrid" title="Detail Transaksi" style="width:100%;height:300px;"
			data-options="rownumbers:true,singleSelect:true,url:'get_trans_det.php',footer:'#tb2',idField:'seq'">
			<thead>
				<tr>
					<th data-options="field:'seq',sortable:true,resizable:false,align:'left'" width="5%"><strong>SEQ</strong></th>
					<th data-options="field:'acc',resizable:false,align:'left'" width="15%"><strong>No.Perkiraan</strong></th>
					<th data-options="field:'kd_sup',resizable:false,align:'left'"><strong>Supplier</strong></th>
					<th data-options="field:'ket',resizable:false,align:'left'" width="40%"><strong>Keterangan</strong></th>
					<th data-options="field:'deb',resizable:false,align:'right',formatter:formatangkaduadec" width="15%"><strong>Debet</strong></th>
					<th data-options="field:'krd',resizable:false,align:'right',formatter:formatangkaduadec" width="15%"><strong>Kredit</strong></th>
				</tr>
			</thead>
		</table>			
		<div id="tb2" style="padding:2px 5px">
			<a id="btn_add2" href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" 
					data-options="disabled:false" onclick="doAddDetail()">Add Detail</a><span class="button-sep">
			</span> <a id="btn_edit2" href="#" class="easyui-linkbutton" 
					iconCls="icon-edit" plain="true" data-options="disabled:false" onclick="doEditDetail()">Edit Detail</a>
			<span class="button-sep"></span> <a id="btn_del2" href="#" class="easyui-linkbutton" 
					iconCls="icon-remove" plain="true" data-options="disabled:false" onclick="doDeleteDetail()">Delete Detail</a>
			<span class="button-sep"></span> <a id="btn_del2" href="#" class="easyui-linkbutton" 
					iconCls="icon-reload" plain="true" data-options="disabled:false" onclick="refreshDetail()">Rekalkulasi</a>
					
			<!--<span class="button-sep"></span> <a id="btn_del2" href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" data-options="disabled:false" onclick="doTest()">TEST ADD</a>
			<span class="button-sep"></span> <a id="btn_del2" href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" data-options="disabled:false" onclick="doTestDel()">TEST DEL</a>
			<span class="button-sep"></span> <a id="btn_del2" href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" data-options="disabled:false" onclick="doTestReset()">TEST RESET</a>
			-->
		</div>
	</div>
	<div id="btn-induk">
		<!--<img id="imgloader" src="../../img/processing.gif" alt="" width="30" height="30" border="0"/>  -->
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-save" onclick="doSave()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" onclick="javascript:$('#dlg-induk').dialog('close')" style="width:90px">Cancel</a>
	</div>	
	
	<!------------------------------------- Dialog Form DETAIL DATA -------------------------------------------------------->
	<div id="dlg-detail" class="easyui-dialog" style="padding:10px 20px"
            closed="true" buttons="#btn-detail" modal="true" draggable="true" title="Detail Transaksi Jurnal Memorial"> 			
		<form id="fmdetail" > <!--novalidate-->
			<!-- Form Detail -->
			<table width="100%">
				<tr height="30px">
					<td align="right"> <label> Kode SEQ :  &nbsp;&nbsp; </label> </td>
					<td>
					<input id="seq2" name="kodeseq2" class="easyui-textbox" style="width:50px;" readonly="true" tooltip="true" required="true"/>
					</td>
				</tr>			
				<tr height="30px">
					<td align="right"> <label> Kode Akun :  &nbsp;&nbsp; </label> </td>
					<td>
					<input id="accd12" class="easyui-combobox" name="pil_accd1" style="width:130px;"  
							data-options="valueField:'acc',textField:'acc',url:'get_akun_kode_list.php',
							onSelect: function(rec){ $('#accd22').combobox('setValue', rec.acc); }" required="true"/>
							
					<input id="accd22" class="easyui-combobox" name="pil_nmpd1" style="width:350px;"  
							data-options="valueField:'acc',textField:'nmp',url:'get_akun_name_list.php',
							onSelect: function(rec){ $('#accd12').combobox('setValue', rec.acc);}" required="true"/>
					</td>
				</div>
				<tr height="30px">
					<td align="right"> <label> Supplier :  &nbsp;&nbsp; </label> </td>
					<td>
					<!-- Search Combo untuk di form -->
					<input id="accs12" class="easyui-combobox" name="pil_accs1" style="width:130px;"  
							data-options="valueField:'kd_sup',textField:'kd_sup',url:'get_sup_kode_list.php',
							onSelect: function(rec){ $('#accs22').combobox('setValue', rec.kd_sup); }"/>
							
					<input id="accs22" class="easyui-combobox" name="pil_nmps2" style="width:350px;"  
							data-options="valueField:'kd_sup',textField:'nm_sup',url:'get_sup_name_list.php',
							onSelect: function(rec){ $('#accs12').combobox('setValue', rec.kd_sup);}"/>
					<a id="btn_csup" href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" data-options="disabled:false" onclick="$('#accs12').combobox('reset');$('#accs22').combobox('reset');">Clear</a>
					</td>
				</tr>				
				<tr height="30px">
					<td align="right"> <label> Tipe Jurnal :  &nbsp;&nbsp; </label> </td>
					<td>
					<input id="tipejurnal2" name="tipejurnal2" class="easyui-switchbutton" data-options="onText:'Kredit',offText:'Debet',width:'100px'"/>
					</td>
				</tr>
				<tr height="30px">
					<td align="right"> <label> Jumlah : &nbsp;&nbsp; </label> </td>
					<td>
					<input class="easyui-numberbox" id="jml2" name="jumlah2" style="width:150px;text-align:right" 
					data-options="groupSeparator:',',decimalSeparator:'.',precision:2,required:'true'"></input>
					</td>
				</tr>
				<tr height="30px">
					<td align="right"> <label> Keterangan : &nbsp;&nbsp; </label> </td>
					<td>
					<input id="ketdet2" class="easyui-textbox" name="ketdetail2" style="width:480px" required="true"/>
					</td>
				</tr>
			</table>
		</form>		
	</div>
	<div id="btn-detail">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-save" onclick="doSaveDetail()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" onclick="javascript:$('#dlg-detail').dialog('close')" style="width:90px">Cancel</a>
    </div>	
	
	<!---------------------- Dialog Cetak Form Jurnal ----------------------------------------------------------------->
	<div id="dlg-cetak" class="easyui-dialog" style="width:70%;height:80%;padding:10px 20px"
            closed="true" buttons="#dlg-btn-cetak" modal="true" maximizable="true" draggable="true" title="Transaksi Jurnal Memorial"> 
		<iframe src="blank.html" id="frame_pdf" name="frame_cetak" width="100%" height="100%" frameborder="1"> </iframe>
	</div>
	<div id="dlg-btn-cetak">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-cetak').dialog('close')" style="width:90px">Close</a>
    </div>
	
<?php		
}

else { header("location:/glt/no_akses.htm"); }

?>