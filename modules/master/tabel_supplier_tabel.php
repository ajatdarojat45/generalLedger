<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt']))
{
	include "../inc/inc_akses.php";
	include "../inc/func_modul.php";
	include "../inc/inc_aed.php";
	
	$table_sup="mst_sup_".$company_id;
	
?>
    <!------- FORM HEADER NAVIGATION ----------->
	<input type="hidden" id="id-menu" value="<? echo $_GET[id_menu]; ?>">			
	<input type="hidden" id="id-add" value="<? echo $tmbl_add; ?>">	
	<input type="hidden" id="id-edit" value="<? echo $tmbl_edit; ?>">	
	<input type="hidden" id="id-delete" value="<? echo $tmbl_del; ?>">	
	<input type="hidden" id="btn-aksi" value="<? echo "ADD"; ?>">	

	<div id="tb" style="padding:2px 5px;">
		<a id="btn_add" href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="doAdd()">ADD NEW</a>
		<span class="button-sep"></span> <a id="btn_edit" href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="doEdit()">EDIT</a>
		<span class="button-sep"></span> <a id="btn_del" href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="doDelete()">DELETE</a>
		<span class="button-sep"></span> <input id="cari" class="easyui-searchbox" data-options="prompt:'Cari...',searcher:Show_Filter" style="width:200px;height:26px"></input>
		<span class="button-sep"></span> Show deleted row : <input id="show-del" class="l-btn" type="checkbox" onchange="Show_Filter()"> 
		<span class="button-sep"></span> <a id="btn_prn" href="" class="easyui-linkbutton" iconCls="icon-reload" plain="true" >Refresh View</a>
	</div>
	<table id="dg" class="easyui-datagrid" title="" style="width:100%;height:96%;padding:0px;"
	data-options="rownumbers:true,singleSelect:true,url:'get_supplier_list.php',pagination:true,pageSize:50,pageList:[50,100,250,500,1000,2000],
				rowStyler: function(index,row){
                    if (row.sup_status!=1){
                        return 'background-color:#999999;color:#fff;font-weight:bold;text-decoration: line-through;';
                    }
                },toolbar:'#tb'">
		<thead>
			<tr>
				<th data-options="field:'kd_sup',resizable:false" width="10%" sortable="true"><strong>Kode</strong></th>
				<th data-options="field:'nm_sup',resizable:false" width="50%" sortable="true"><strong>Nama Supplier</strong></th>
			</tr>
		</thead>
	</table>
	<div id="dlg" class="easyui-dialog" style="width:50%;height:180px;padding:10px 20px;background-color:#FFD1B2"
            closed="true" buttons="#dlg-buttons" modal="true" draggable="true"> 
        <form id="fm" method="post" > <!--novalidate-->
			<table width="100%">
                <tr height="30px">
					<td align="right"> <label> Kode Supplier : &nbsp;&nbsp; </label> </td>
					<td> <input id="kd_sup" name="kd_sup" class="easyui-textbox" style="width:50px;" data-options="required:true,validType:'length[3,3]'"></td>
				</tr>
				<tr height="30px">
					<td align="right"><label>Nama Supplier : &nbsp;&nbsp;</label></td>
					<td> <input name="nm_sup" title="Test..." class="easyui-textbox easyui-tooltip" required="true" style="width:450px;" tooltip="true"></td>
				</tr>
			</table>
        </form>
	</div>
	<div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveSupplier()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
    </div>
	    
<!-- session -->
<?
	if($_POST[btn_add]){
	}
}
else
{
	echo"<title>Manage Care</title>
				<link href=\"../../style\style.css\" rel=stylesheet>";
	echo "<center>";
	echo "<h3>Acess Denied</h3>";
	echo "Please <a href=../../index.php target=$_self>[Login]</a> First<br>";
	echo "</center>";

}

?>