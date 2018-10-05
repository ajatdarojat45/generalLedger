<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt']))
{
	include "../inc/inc_akses.php";
	include "../inc/func_modul.php";
	include "../inc/inc_aed.php";
	
	$table_akun="mst_akun_".$company_id;
	
?>

	<!--<form name="frmakun" method="post">-->
		<!--<input type="hidden" id="id-menu" value="<?// echo $_GET[id_menu]; ?>">			
		<input type="hidden" id="id-add" value="<? //echo $tmbl_add; ?>">	
		<input type="hidden" id="id-edit" value="<? //echo $tmbl_edit; ?>">	
		<input type="hidden" id="id-delete" value="<?// echo $tmbl_del; ?>">	
		<input type="hidden" id="btn-aksi" value="<? //echo "ADD"; ?>">	-->

		<table id="dg" class="easyui-datagrid" title="" style="width:100%;height:96%;padding:0px;"
		data-options="rownumbers:true,singleSelect:true,url:'get_akun_list.php',pagination:true,pageSize:50,pageList:[50,100,250,500,1000,2000],
					rowStyler: function(index,row){
						if (row.acc_status!=1){
							return 'background-color:#999999;color:#fff;font-weight:bold;text-decoration: line-through;';
						}
					},toolbar:'#tb'">
			<thead>
				<tr>
					<th data-options="field:'level',resizable:false,align:'center'" width="3%"><strong>Level</strong></th>
					<th data-options="field:'acc',resizable:false" width="10%" sortable="true"><strong>Kode Perkiraan</strong></th>
					<th data-options="field:'nmp',resizable:false" width="50%" sortable="true"><strong>Nama Perkiraan</strong></th>
					<th data-options="field:'tnd',resizable:false" width="5%"><strong>D/K</strong></th>
					<th data-options="field:'jnp',resizable:false" width="5%"><strong>N/R</strong></th>
					<th data-options="field:'hpp',resizable:false" width="5%"><strong>HPP</strong></th>
					<th data-options="field:'pusat',resizable:false" width="5%"><strong>Pusat</strong></th>
				</tr>
			</thead>
		</table>
		<div id="tb" style="padding:2px 5px;">
			<a id="btn_add" href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="doAdd()">ADD NEW</a>
			<span class="button-sep"></span> <a id="btn_edit" href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="doEdit()">EDIT</a>
			<span class="button-sep"></span> <a id="btn_del" href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="doDelete()">DELETE</a>
			<span class="button-sep"></span> <input id="cari" class="easyui-searchbox" data-options="prompt:'Cari...',searcher:Show_Filter" style="width:200px;height:26px"></input>
			<span class="button-sep"></span> Show deleted row : <input id="show-del" class="l-btn" type="checkbox" onchange="Show_Filter()"> 
			<span class="button-sep"></span> Filter Level : 
			<select id="filter_lvl" name="filterlvl" onChange="Show_Filter()"> 
				<option value="ALL">ALL</option>
				<option value="1">Level 1</option>
				<option value="2">Level 2</option>
				<option value="3">Level 3</option>
				<option value="4">Level 4</option>
				<option value="5">Level 5</option>
			</select> 			
			<span class="button-sep"></span> <a id="btn_prn" href="" class="easyui-linkbutton" iconCls="icon-reload" plain="true" >Refresh View</a>
		</div>
	<!--</form>	-->
	<div id="dlg" class="easyui-dialog" style="width:50%;height:360px;padding:10px 20px;background-color:#FFD1B2"
            closed="true" buttons="#dlg-buttons" modal="true" draggable="true"> 
        <form id="fm" method="post"> <!--novalidate-->
			<table width="100%">
                <tr height="30px">
					<td align="right"> <label> Level : &nbsp;&nbsp; </label> </td>
					<td><select id="level" name="level" data-options="required:true" style="width:90px"> 
							<option value="1">Level 1</option>
							<option value="2">Level 2</option>
							<option value="3">Level 3</option>
							<option value="4">Level 4</option>
							<option value="5">Level 5</option>
						</select>
					</td>
				</tr>
                <tr height="30px">
					<td align="right"> <label> Kode Perkiraan : &nbsp;&nbsp; </label> </td>
					<td> <input id="acc" name="acc" style="width:100px;" required></td>						
				</tr>
				<tr height="30px">
					<td align="right"><label>Nama Perkiraan : &nbsp;&nbsp;</label></td>
					<td> <input name="nmp" class="easyui-textbox" required="true" style="width:450px;"></td>
				</tr>
				<tr height="30px">
					<td align="right"><label>Debet / Kredit : &nbsp;&nbsp;</label></td>
					<td> <!--<input name="tnd" class="easyui-textbox easyui-tooltip" required="true" style="width:30px;" tooltip="true">-->
						<select id="tnd" name="tnd" style="width:90px"> 
							<option value="D">Debet</option>
							<option value="K">Kredit</option>							
						</select>
					</td>
				</tr>
				<tr height="30px">
					<td align="right"><label>Neraca / Rugi-laba : &nbsp;&nbsp;</label></td>
					<td> <!--<input name="jnp" class="easyui-textbox easyui-tooltip" required="true" style="width:30px;" tooltip="true">-->
						<select id="jnp" name="jnp" style="width:90px" value="N"> 
							<option value="N">Neraca</option>
							<option value="R">Rugi-Laba</option>							
						</select>
					</td>
				</tr>
				<tr height="30px">
					<td align="right"><label>HPP : &nbsp;&nbsp;</label></td>
					<td> <!--<input name="hpp" class="easyui-textbox easyui-tooltip" required="true" style="width:30px;" tooltip="true">-->
						<select id="hpp" name="hpp" style="width:90px"> 
							<option value="Y">Ya</option>
							<option value="T">Tidak</option>							
						</select>
					</td>
					
				</tr>
				<tr height="30px">
					<td align="right"><label>Perkiraan Pusat : &nbsp;&nbsp;</label></td>
					<td> <!--<input name="pusat" class="easyui-textbox easyui-tooltip" required="true" style="width:30px;" tooltip="true">-->
						<select id="pusat" name="pusat" style="width:90px"> 
							<option value="K">Kas</option>
							<option value="B">Bank</option>							
							<option value="A">Semua</option>							
						</select>
					</td>
				</tr>
				<tr height="30px">
					<td align="right"><label>Saldo Awal : &nbsp;&nbsp;</label></td>
					<td> <label>Tahun</label> <input id="th_sld" name="th_sld" class="textbox" style="width:70px;" value="2015"></input>
					     <label>Bulan</label> 
						 <select id="bl_sld" name="bl_sld"> 
							<option value="01">Januari</option>
							<option value="02">Februari</option>							
							<option value="03">Maret</option>							
							<option value="04">April</option>							
							<option value="05">Mei</option>							
							<option value="06">Juni</option>							
							<option value="07">Juli</option>							
							<option value="08">Agustus</option>							
							<option value="09">September</option>							
							<option value="10">Oktober</option>							
							<option value="11">Nopember</option>							
							<option value="12">Desember</option>							
						</select>
						 
					     <label>Jumlah</label> 
						 <!--
						 <input id="jml_sld" class="textbox" required="true" name="jml_sld" style="width:120px;text-align:right" data-options="required:true" onKeyDown="return numbersonly(this, event);" onKeyUp="javascript:tandaPemisahTitik(this);"> 
						 -->
						 <input id="jml_sld" type="text" class="easyui-numberbox" name="jml_sld" style="width:120px;text-align:right" data-options="groupSeparator:',',decimalSeparator:'.'" /> 
						 <a href="#" class="easyui-linkbutton" onclick="javascript: $('#jml_sld').numberbox('setValue', 0); document.getElementById('bl_sld').value='';document.getElementById('th_sld').value=''; ">RESET</a>
					</td>
				</tr>
			</table>
        </form>
	</div>
	<div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveAkun()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
    </div>
<!-- session -->
<?	

}
else { header("location:/glt/no_akses.htm"); }

?>

