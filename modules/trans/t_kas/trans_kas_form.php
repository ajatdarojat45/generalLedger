<?php
session_start();
//echo "TRANSAKSI KAS<br>";

if (isset($_SESSION['app_glt'])) {	
	include "../inc/inc_akses.php";
	include "../inc/func_modul.php";
	include "../inc/inc_aed.php";
	
	$jenis_trans="Kas";
	$tbl_view_trx="trans_kas".$company_id ; 
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tbl_mst_sup="mst_sup_".$company_id ;
	
	$tbl_trx_temp="trx_tmp_caseglt_".$company_id ;
	$tbl_trx="trx_caseglt_".$company_id ;
	 
	$tgl_trans=substr($aktif_tgl,0,7);
	
	$jumdata = "";
	
	$que_sel = "SELECT acc_kas,acc_bank,acc_bs FROM mst_company WHERE mcom_id='$company_id' ";		
	$que_view = mysql_query($que_sel, $conn) or die("Error Query select Acc Pusat");
	$data_pusat = mysql_fetch_array($que_view);		

	$acc=$data_pusat[acc_kas];
	
	$que_sel = "SELECT nmp FROM ".$tbl_mst_akun." WHERE acc='$acc' AND acc_status='1'";		
	$que_view = mysql_query($que_sel, $conn) or die("Error Query acc name");
	$data_pusat = mysql_fetch_array($que_view);			
	
	$nmp=$data_pusat[nmp];
	
	$nbk="";
	
	$tgp=$_POST[cdate];;

	$kd_sup="";
	$nm_sup="";
	
	//transform
	$txtdebet="0.00";				
	$txtkredit="0.00";						
	
	//murni
	$totdebet="0";				
	$totkredit="0";						
	
	$tipe=substr($nbk,1,1);
	$data_cf="";
	
	//echo $acc.'-'.$nmp ;
	//$qry_tmp_del=mysql_query("DELETE FROM ".$tbl_trx_temp." WHERE user_id='$user_id'", $conn) or die("Error Delete Temp Caseglt ");

?>
	<input type="hidden" id="id-menu" value="<? echo $_GET[id_menu]; ?>">			
	<input type="hidden" id="id-add" value="<? echo $tmbl_add; ?>">	
	<input type="hidden" id="id-edit" value="<? echo $tmbl_edit; ?>">	
	<input type="hidden" id="id-delete" value="<? echo $tmbl_del; ?>">	
	
	<div id="tb" style="padding:2px 5px;background-color:white;margin-bottom:10px">				
		<a id="btn_add" href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="doAdd('<?php echo $acc ?>','<?php echo $nmp ?>')">ADD NEW</a>
		<span class="button-sep"></span> <a id="btn_edit" href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="doEdit()">EDIT</a>
		<span class="button-sep"></span> <a id="btn_del" href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="doDelete()">DELETE</a>
		<span class="button-sep"></span> <a id="btn_prn" href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="doPrint()">PRINT</a>
		<span class="button-sep"></span> <a id="btn_prn" href="" class="easyui-linkbutton" iconCls="icon-reload" plain="true" >Refresh View</a>
	</div>
		
	<form id="fu">
		<table width="100%" style="margin-bottom:10px">
			<tr height="30px">
				<td align="right"  width="120px"><label>Tanggal Bukti : &nbsp;&nbsp;</label></td>
				<td> <input id="tgp" name="tglbukti" class="easyui-textbox" style="width:100px;" tooltip="true"/></td>
				<td><div id="datadeleted"></div></td>
			</tr>
			<tr height="30px">
				<td align="right"> <label> Transaksi : &nbsp;&nbsp; </label> </td>
				<td> <input id="tipetrans" name="tipetrans" class="easyui-switchbutton" data-options="onText:'Penerimaan Kas',offText:'Pengeluaran Kas',width:'150px'" readonly='true'/></td>
			</tr>
			<tr height="30px">
				<td align="right"><label>Nomor Bukti : &nbsp;&nbsp;</label></td>
				<td> <input id="nbk" name="nobukti" class="easyui-textbox" style="width:120px;" tooltip="true" readonly='true'/></td>				
				<td><div id="infobal" style="text-align:center;font-size:16px;border:1px solid red" hidden><b>Not Balance</b> </div></td>
			</tr>
			<tr height="30px">
				<td align="right"> <label> Keterangan : &nbsp;&nbsp; </label> </td>
				<td>
					<input id="ket" class="easyui-textbox" name="keterangan" style="width:500px" data-options="multiline:false" readonly='true'/>
				</td>
				<td align="right"><label>Total Nilai : &nbsp;&nbsp;</label>
					<input id="total" name="total" class="easyui-textbox" style="width:150px;text-align:right" tooltip="true" readonly='true'/>
				</td>
			</tr>
			<!--<tr height="30px">
				<td align="right"> <label> Kode Akun Pusat : &nbsp;&nbsp; </label> </td>
				<td> <input id="kodeacc" name="kodeacc" class="easyui-textbox" style="width:100px;" tooltip="true"/>
					<input id="kodenmp" name="kodenmp" class="easyui-textbox" style="width:400px;" tooltip="true"/>
				</td>				
			</tr>-->
		</table>
	</form>
	<!-- get_trans_det.php?tipe=K -->
	<table id="dg-det" class="easyui-datagrid" title="Detail Transaksi" style="width:100%;height:300px;"
			data-options="rownumbers:true,singleSelect:true,url:'get_trans_det.php?tipe=K',footer:'#tb2'">
		<thead>
			<tr>
				<th data-options="field:'seq',resizable:false,align:'left'" width="5%"><strong>SEQ</strong></th>
				<th data-options="field:'acc',resizable:false,align:'left'" width="15%"><strong>No.Perkiraan</strong></th>
				<th data-options="field:'kd_sup',resizable:false,align:'left'"><strong>Supplier</strong></th>
				<th data-options="field:'ket',resizable:false,align:'left'" width="40%"><strong>Keterangan</strong></th>
				<th data-options="field:'deb',resizable:false,align:'right',formatter:formatangkaduadec" width="15%"><strong>Debet</strong></th>
				<th data-options="field:'krd',resizable:false,align:'right',formatter:formatangkaduadec" width="15%"><strong>Kredit</strong></th>
			</tr>
		</thead>
	</table>			
	<div id="tb2" style="padding:2px 5px">
		<a id="btn_add2" href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="doAddDetail()" data-options="disabled:false">Add New Detail</a>
		<span class="button-sep"></span> <a id="btn_edit2" href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" data-options="disabled:false" onclick="doEditDetail()">Edit Detail</a>
		<span class="button-sep"></span> <a id="btn_del2" href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" data-options="disabled:false" onclick="doDeleteDetail()">Delete Detail</a>
		<!--<span class="button-sep"></span> Show deleted : <input id="show-del-det" class="l-btn" type="checkbox" onchange="Show_FilterDet()"> </input> -->
	</div>	
	
	<!-- Dialog FORM  Header hanya untuk Add New ------------------------>
	<div id="dlg-induk" class="easyui-dialog" style="padding:10px 20px" 
            closed="true" buttons="#dlg-btn-induk-add" modal="true" draggable="true" title="Transaksi Jurnal Kas"> 
		<form id="fm" method="post"> <!--novalidate-->
			<table width="100%">
				<tr height="30px">
					<td align="right"><label>Tanggal Bukti : &nbsp;&nbsp;</label></td>
					<td> 
						<input id="tgp1" name="tglbukti1" class="easyui-datebox" type="text" id="trx_date" name="trx_date" 
						data-options="required:true,formatter:myformatter,parser:myparser" style="width:120px" value="<?php $mdate=date(); echo $mdate; ?>" required="true"/>
					</td>
				</tr>
				<tr height="30px">
					<td align="right" width="120px"> <label> Transaksi : &nbsp;&nbsp; </label> </td>
					<td>
						<input id="tipetrans1" name="tipetrans1" class="easyui-switchbutton" value='off' 
							data-options="onText:'Penerimaan Kas',offText:'Pengeluaran Kas',width:'150px',
							onChange:function(checked){	
								if (document.getElementById('autonumber1').checked==false){
									if (checked){
										$('#nbk1').textbox('setValue','KM-');										
									} else {
										$('#nbk1').textbox('setValue','KK-');																				
									}
								}							
							}"/>
					</td>					
				</tr>
				<tr height="30px">
					<td align="right"><label>Nomor Bukti : &nbsp;&nbsp;</label></td>
					<td> <input id="nbk1" name="nobukti1" class="easyui-textbox" style="width:200px;" tooltip="true"> 
						<input id="autonumber1" class="easyui-switchbutton" data-options="onText:'Auto Number',offText:'Manual',width:'100px',
								onChange:function(checked){
									if (checked){
										$('#nbk1').textbox('readonly',true);
										$('#nbk1').textbox('setValue','Auto Number');										
									} else {
										if (document.getElementById('tipetrans1').checked==false){
											$('#nbk1').textbox('readonly',false);
											$('#nbk1').textbox('setValue','KK-');
										} else {
											$('#nbk1').textbox('readonly',false);
											$('#nbk1').textbox('setValue','KM-');											
										}
									}}"/></td>
					</td>					
				</tr>
				<tr height="30px">
					<td align="right"> <label>Akun Pusat Kas : &nbsp;&nbsp; </label> </td>
					<td>
						<!-- Search Combo untuk di form -->
						<input id="acc1" class="easyui-combobox" name="pil_acc" style="width:130px;"  
								data-options="valueField:'acc',textField:'acc',url:'get_akun_kode_list.php?pusat=K',
								onSelect: function(rec){ $('#acc2').combobox('setValue', rec.acc); }"/>
								
						<input id="acc2" class="easyui-combobox" name="pil_nmp" style="width:350px;"  
								data-options="valueField:'acc',textField:'nmp',url:'get_akun_name_list.php?pusat=K',
								onSelect: function(rec){ $('#acc1').combobox('setValue', rec.acc);}"/>
					</td>
				</tr>
				<tr height="30px">
					<td align="right"> <label> Keterangan : &nbsp;&nbsp; </label> </td>
					<td><input id="ket1" class="easyui-textbox" name="keterangan1" style="width:480px" required="true"/></td>
				</tr>
				<!-- Form Detail -->
				<tr height="30px">
					<td align="right"> <label> Kode SEQ :  &nbsp;&nbsp; </label> </td>
					<td>
					<input id="seq1" name="kodeseq1" class="easyui-textbox" style="width:50px;" readonly="true" tooltip="true" required="true"/>
					</td>
				</tr>			
				<tr height="30px">
					<td align="right"> <label> Kode Akun :  &nbsp;&nbsp; </label> </td>
					<td>
					<input index='1' id="accd1" class="easyui-combobox" name="pil_accd1" style="width:130px;"  
							data-options="valueField:'acc',textField:'acc',url:'get_akun_kode_list.php',
							onSelect: function(rec){ $('#accd2').combobox('setValue', rec.acc); }" required="true"/>
							
					<input id="accd2" class="easyui-combobox" name="pil_nmpd1" style="width:350px;"  
							data-options="valueField:'acc',textField:'nmp',url:'get_akun_name_list.php',
							onSelect: function(rec){ $('#accd1').combobox('setValue', rec.acc);}" required="true"/>
					</td>
				</div>
				<tr height="30px">
					<td align="right"> <label> Supplier :  &nbsp;&nbsp; </label> </td>
					<td>
					<!-- Search Combo untuk di form -->
					<input id="accs1" class="easyui-combobox" name="pil_accs1" style="width:130px;"  
							data-options="valueField:'kd_sup',textField:'kd_sup',url:'get_sup_kode_list.php',
							onSelect: function(rec){ $('#accs2').combobox('setValue', rec.kd_sup); }"/>
							
					<input id="accs2" class="easyui-combobox" name="pil_nmps2" style="width:350px;"  
							data-options="valueField:'kd_sup',textField:'nm_sup',url:'get_sup_name_list.php',
							onSelect: function(rec){ $('#accs1').combobox('setValue', rec.kd_sup);}"/>
					<a id="btn_csup" href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" data-options="disabled:false" onclick="$('#accs1').combobox('reset');$('#accs2').combobox('reset');">Clear</a>
					</td>
				</tr>				
				<tr height="30px">
					<td align="right"> <label> Tipe Jurnal :  &nbsp;&nbsp; </label> </td>
					<td>
					<input id="tipejurnal1" name="tipejurnal1" class="easyui-switchbutton" data-options="onText:'Kredit',offText:'Debet',width:'100px'"/>
					</td>
				</tr>
				<tr height="30px">
					<td align="right"> <label> Jumlah : &nbsp;&nbsp; </label> </td>
					<td>
					<input class="easyui-numberbox" id="jml1" name="jumlah1" style="width:150px;text-align:right" 
					data-options="groupSeparator:',',decimalSeparator:'.',precision:2,required:'true'"></input>
					</td>
				</tr>
				<tr height="30px">
					<td align="right"> <label> Keterangan : &nbsp;&nbsp; </label> </td>
					<td>
					<input id="ketdet1" class="easyui-textbox" name="ketdetail1" style="width:480px" required="true"/>
					</td>
				</tr>			
		</table>
	</div>
	<div id="dlg-btn-induk-add">
		<img id="imgloader1" src="../../img/processing.gif" alt="" width="30" height="30" border="0"/>  
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSave()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-induk').dialog('close')" style="width:90px">Cancel</a>
    </div>
	
	<!-- Dialog FORM  Header hanya untuk Edit ------------------------>
	<div id="dlg-induk-edit" class="easyui-dialog" style="padding:10px 20px" 
            closed="true" buttons="#dlg-btn-induk-edit" modal="true" draggable="true" title="Transaksi Jurnal Kas"> 
		<form id="fmedit" method="post"> <!--novalidate-->
			<table width="100%">
				<tr height="30px">
					<td align="right"><label>Nomor Bukti : &nbsp;&nbsp;</label></td>
					<td> <input id="nbk2" name="nobukti2" class="easyui-textbox" style="width:200px;" tooltip="true" disabled="true"> 						
					</td>					
				</tr>
				<tr height="30px">
					<td align="right"><label>Tanggal Bukti : &nbsp;&nbsp;</label></td>
					<td> 
						<input id="tgp2" name="tglbukti2" class="easyui-datebox" type="text" id="trx_date" name="trx_date" 
						data-options="required:true,formatter:myformatter,parser:myparser" style="width:120px" value="<?php $mdate=date(); echo $mdate; ?>" required="true"/>
					</td>
				</tr>
				<tr height="30px">
					<td align="right"> <label> Keterangan : &nbsp;&nbsp; </label> </td>
					<td><input id="ket2" class="easyui-textbox" name="keterangan2" style="width:480px" required="true"/></td>
				</tr>
			</form>
		</table>
	</div>
	<div id="dlg-btn-induk-edit">
		<img id="imgloader" src="../../img/processing.gif" alt="" width="30" height="30" border="0"/>  
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSaveEdit()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-induk-edit').dialog('close')" style="width:90px">Cancel</a>
    </div>
	
	<!--- Dialog Form DETAIL DATA ----------------->
	<div id="dlg-detail" class="easyui-dialog" style="padding:10px 20px"
            closed="true" buttons="#dlg-btn-detail" modal="true" draggable="true" title="Detail Transaksi Jurnal Kas"> 			
		<form id="fmd" method="post"> <!--novalidate-->
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
					<a id="btn_csup" href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" data-options="disabled:false" onclick="$('#accs1').combobox('reset');$('#accs2').combobox('reset');">Clear</a>
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
	<div id="dlg-btn-detail">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSave()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-detail').dialog('close')" style="width:90px">Cancel</a>
    </div>	
	
	<!-- Dialog Cetak Form Jurnal ------------------------>
	<div id="dlg-cetak" class="easyui-dialog" style="width:70%;height:80%;padding:10px 20px"
            closed="true" buttons="#dlg-btn-cetak" modal="true" maximizable="true" draggable="true" title="Transaksi Jurnal Kas"> 
		<iframe src="blank.html" id="frame_pdf" name="frame_cetak" width="100%" height="100%" frameborder="1"> </iframe>
	</div>
	<div id="dlg-btn-cetak">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-cetak').dialog('close')" style="width:90px">Close</a>
    </div>
<?

}
else { header("location:/glt/no_akses.htm"); }

?>