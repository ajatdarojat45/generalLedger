<?php
session_start();
//echo "TRANSAKSI KAS<br>";

if (isset($_SESSION['app_glt'])) {	
	include "../inc/inc_akses.php";
	include "../inc/inc_trans_menu.php";
	
	ins_trans_menu($_GET[id_menu], $_SESSION[app_glt]);	
	
	include "../inc/func_modul.php";
	include "../inc/inc_aed.php";
	
	$jenis_trans="Kas";
	$tbl_view_trx="trans_kas".$company_id ; 
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tbl_mst_sup="mst_sup_".$company_id ;
	
	$tbl_trx_temp="trx_tmp_caseglt_".$company_id ;
	$tbl_trx="trx_caseglt_".$company_id ;
	 
	$tgl_trans=substr($aktif_tgl,0,7);
	
?>


<!-- Bootstrap core CSS -->
<!--
<link href="../../bootstrap-3/css/bootstrap.css" rel="stylesheet">
<link href="../../bootstrap-3/css/bootstrap-theme.css" rel="stylesheet">
<link href="../../css/black-tie/jquery-ui-1.10.4.custom.css" rel="stylesheet">
-->
<!--
<link href="../../style/style_utama.css" rel="stylesheet">
<link href="../../themes/default/easyui.css" rel="stylesheet" >
<link href="../../themes/icon.css" rel="stylesheet" >
-->	

	
	<!--<form name="frmkas" id="frm_kas" method="post">-->
<?php
	$jenis_trans="Kas";
	$tbl_view_trx="trans_kas".$company_id ; 
	$tbl_mst_akun="mst_akun_".$company_id ;
	$tbl_mst_sup="mst_sup_".$company_id ;
	
	$tbl_trx_temp="trx_tmp_caseglt_".$company_id ;
	$tbl_trx="trx_caseglt_".$company_id ;
	 
	$tgl_trans=substr($aktif_tgl,0,7);
	
	$jumdata = "";
	
	$que_sel = "SELECT acc_kas,acc_bank,acc_bs FROM mst_company WHERE mcom_id='$company_id'  ";		
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
	
	$qry_tmp_del=mysql_query("DELETE FROM ".$tbl_trx_temp." WHERE user_id='$user_id'", $conn) or die("Error Delete Temp Caseglt ");

?>
	<input type="hidden" id="id-menu" value="<? echo $_GET[id_menu]; ?>">			
	<input type="hidden" id="id-add" value="<? echo $tmbl_add; ?>">	
	<input type="hidden" id="id-edit" value="<? echo $tmbl_edit; ?>">	
	<input type="hidden" id="id-delete" value="<? echo $tmbl_del; ?>">	
	
	<div id="tb" style="padding:2px 5px;background-color:white;margin-bottom:10px">				
		<a id="btn_add" href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="doAdd()">ADD NEW</a>
		<span class="button-sep"></span> <a id="btn_edit" href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="doEdit()">EDIT</a>
		<span class="button-sep"></span> <a id="btn_del" href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="doDelete()">DELETE</a>
		<span class="button-sep"></span> <a id="btn_prn" href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="doPrint()">PRINT</a>
		<span class="button-sep"></span> <a id="btn_prn" href="" class="easyui-linkbutton" iconCls="icon-reload" plain="true">Refresh View</a>
	</div>
		
		<!--<form id="fm" method="post"> -->
			<table width="100%" style="margin-bottom:10px">
				<tr height="30px">
					<td align="right" width="120px"> <label> Transaksi : &nbsp;&nbsp; </label> </td>
					<td> <input class="easyui-switchbutton" data-options="onText:'Kas Masuk',offText:'Kas Keluar',width:'150px'"/></td>
					
				</tr>
				<tr height="30px">
					<td align="right"><label>Nomor Bukti : &nbsp;&nbsp;</label></td>
					<td> <input name="nmp" class="easyui-textbox" style="width:200px;" tooltip="true"></td>				
				</tr>
				<tr height="30px">
					<td align="right"><label>Tanggal Bukti : &nbsp;&nbsp;</label></td>
					<td> <input name="nmp" class="easyui-textbox" style="width:100px;" tooltip="true"></td>
				</tr>
				<tr height="30px">
					<td align="right"> <label> Kode Akun Pusat : &nbsp;&nbsp; </label> </td>
					<td> <input name="nmp" class="easyui-textbox" style="width:100px;" tooltip="true">
						<input name="nmp" class="easyui-textbox" style="width:400px;" tooltip="true">
					</td>					
				</tr>
				<tr height="30px">
					<td align="right"> <label> Keterangan : &nbsp;&nbsp; </label> </td>
					<td>
						<input class="easyui-textbox" name="keterangan" style="width:500px;height:40px" data-options="multiline:true">
					</td>
					<td align="right"><label>Total Nilai : &nbsp;&nbsp;</label>
						<input name="nmp" class="easyui-textbox" style="width:150px;" tooltip="true">
					</td>
				</tr>
			</table>			
			<table id="dg1" class="easyui-datagrid" title="" style="width:100%;height:270px;"
					data-options="rownumbers:true,singleSelect:true,url:'get_trans_det.php',
									rowStyler: function(index,row){
										if (row.trx_status!=1){
											return 'background-color:#999999;color:#fff;font-weight:bold;text-decoration: line-through;';
										}
									},toolbar:'#tb2'">
				<thead>
					<tr>
						<th data-options="field:'seq',resizable:false,align:'left'" width="5%"><strong>SEQ</strong></th>
						<th data-options="field:'acc',resizable:false,align:'left'" width="15%"><strong>No.Perkiraan</strong></th>
						<th data-options="field:'ket',resizable:false,align:'left'" width="40%"><strong>Keterangan</strong></th>
						<th data-options="field:'debet',resizable:false,align:'left'" width="15%"><strong>Debet</strong></th>
						<th data-options="field:'kredit',resizable:false,align:'center'" width="15%"><strong>Kredit</strong></th>
					</tr>
				</thead>
			</table>			
			<div id="tb2" style="padding:2px 5px;">
				<a id="btn_add2" href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="doAddDetail()" data-options="disabled:false">Add New Detail</a>
				<span class="button-sep"></span> <a id="btn_edit2" href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" data-options="disabled:false" onclick="doEditDetail()">Edit Detail</a>
				<span class="button-sep"></span> <a id="btn_del2" href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" data-options="disabled:false" onclick="doDeleteDetail()">Delete Detail</a>
				<span class="button-sep"></span> Show deleted : <input id="show-del2" class="l-btn" type="checkbox" disabled="false"> </input> 
			</div>
		<!--</form>-->
	
	
	<div id="dlg-induk" class="easyui-dialog" style="width:50%;height:300px;padding:10px 20px"
            closed="true" buttons="#dlg-btn-induk" modal="true" draggable="true" title="Transaksi Jurnal Kas"> 
        <form id="fm" method="post"> <!--novalidate-->
			<table width="100%">
				<tr height="30px">
					<td align="right" width="120px"> <label> Transaksi : &nbsp;&nbsp; </label> </td>
					<!-- Radio Button untuk di form -->
					<td>
						<input class="easyui-switchbutton" data-options="onText:'Kas Keluar',offText:'Kas Masuk',width:'150px'"/>
						
						<!--<input type="radio" name="jenis_trs" id="chk_trs1" value="K" <? if ($tipe=='K') { echo "checked"; } ?> onClick="klik_tipe('KK');"></input ><label for="chk_trs1">Kas Keluar</label>
						<input type="radio" name="jenis_trs" id="chk_trs2" value="M" <? if ($tipe=='M') { echo "checked"; } ?> onClick="klik_tipe('KM');"></input><label for="chk_trs2">Kas Masuk</label>					
						-->
					</td>
					
				</tr>
				<tr height="30px">
					<td align="right"><label>Nomor Bukti : &nbsp;&nbsp;</label></td>
					<td> <input name="nmp" class="easyui-textbox" style="width:250px;" tooltip="true"> <input class="easyui-switchbutton" data-options="onText:'Auto Number',offText:'Manual',width:'100px'"/></td>
					</td>					
				</tr>
				<tr height="30px">
					<td align="right"><label>Tanggal Bukti : &nbsp;&nbsp;</label></td>
					<td> 
						<!--<input class="easyui-textbox" type="text" id="trx_date" name="trx_date" 
						data-options="readonly:'true',formatter:myformatter" style="width:100px"></input>-->
						<input class="easyui-datebox" type="text" id="trx_date" name="trx_date" 
						data-options="required:true,formatter:myformatter,parser:myparser" style="width:120px">
					</td>
				</tr>
				<tr height="30px">
					<td align="right"> <label> Kode Akun Pusat : &nbsp;&nbsp; </label> </td>
					<td>
						<!-- Search Combo untuk di form -->
						<input id="cc1" class="easyui-combobox" name="pil_acc" style="width:130px;"  
								data-options="valueField:'acc',textField:'acc',url:'get_akun_kode_list.php',
								onSelect: function(rec){ $('#cc2').combobox('setValue', rec.acc); }">
								
						<input id="cc2" class="easyui-combobox" name="pil_nmp" style="width:350px;"  
								data-options="valueField:'acc',textField:'nmp',url:'get_akun_name_list.php',
								onSelect: function(rec){ $('#cc1').combobox('setValue', rec.acc);}">
					</td>
				</tr>
				<tr height="30px">
					<td align="right"> <label> Keterangan : &nbsp;&nbsp; </label> </td>
					<td>
						<br><input class="easyui-textbox" name="keterangan" style="width:500px;height:60px" data-options="multiline:true">
					</td>
				</tr>
			</table>
        </form>
	</div>
	<div id="dlg-btn-induk">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveKas()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-induk').dialog('close')" style="width:90px">Cancel</a>
    </div>
	<div id="dlg-detail" class="easyui-dialog" style="width:50%;height:430px;padding:10px 20px"
            closed="true" buttons="#dlg-btn-detail" modal="true" draggable="true" title="Detail Transaksi Jurnal Kas"> 			
		<form id="fmd" method="post"> <!--novalidate-->
			<div style="margin-bottom:10px">
				Kode SEQ : <br><input name="nmp" class="easyui-textbox" style="width:50px;" tooltip="true">
			</div>					
			<div style="margin-bottom:10px">
				Tipe Jurnal : <br><input class="easyui-switchbutton" data-options="onText:'Kredit',offText:'Debet',width:'150px'"/>
			</div>
			<div style="margin-bottom:10px">
				<!-- Search Combo untuk di form -->
				Kode Akun : <br><input id="ccd1" class="easyui-combobox" name="pil_acc" style="width:130px;"  
						data-options="valueField:'acc',textField:'acc',url:'get_akun_kode_list.php',
						onSelect: function(rec){ $('#ccd2').combobox('setValue', rec.acc); }">
						
				<input id="ccd2" class="easyui-combobox" name="pil_nmp" style="width:400px;"  
						data-options="valueField:'acc',textField:'nmp',url:'get_akun_name_list.php',
						onSelect: function(rec){ $('#ccd1').combobox('setValue', rec.acc);}">
			</div>
			<div style="margin-bottom:10px">
				<!-- Search Combo untuk di form -->
				Kode Supplier : <br><input id="ccs1" class="easyui-combobox" name="pil_acc" style="width:130px;"  
						data-options="valueField:'acc',textField:'acc',url:'get_akun_kode_list.php',
						onSelect: function(rec){ $('#ccs2').combobox('setValue', rec.acc); }">
						
				<input id="ccs2" class="easyui-combobox" name="pil_nmp" style="width:400px;"  
						data-options="valueField:'acc',textField:'nmp',url:'get_akun_name_list.php',
						onSelect: function(rec){ $('#ccs1').combobox('setValue', rec.acc);}">
			</div>				
			<div style="margin-bottom:10px">
				Keterangan : <br><input class="easyui-textbox" name="message" style="width:500px;height:60px" data-options="multiline:true">
			</div>				
			<div style="margin-bottom:10px">
				Jumlah : <br><input name="nmp" class="easyui-textbox" style="width:150px;" tooltip="true">
			</div>
		</form>
		
	</div>
	<div id="dlg-btn-detail">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveKas()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg-detail').dialog('close')" style="width:90px">Cancel</a>
    </div>

<?
			
}
else { header("location:/glt/no_akses.htm"); }

?>