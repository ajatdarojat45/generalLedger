<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {	
?>
	
	<table id="dg2" class="easyui-datagrid" title="" style="width:99%;height:96%;padding:0px;"
			data-options="rownumbers:true,singleSelect:true,url:'get_trans_det.php',pagination:true,pageSize:50,pageList:[50,100,250,500,1000,2000],
							rowStyler: function(index,row){
								if (row.trx_status!=1){
									return 'background-color:#999999;color:#fff;font-weight:bold;text-decoration: line-through;';
								}
							},toolbar:'#tb3'">
		<thead>
			<tr>
				<th data-options="field:'tanggal',resizable:true,align:'left'" width="25%"><strong>Tanggal</strong></th>
				<th data-options="field:'nbk',resizable:true,align:'left'" width="35%"><strong>Nomor Bukti</strong></th>
				<th data-options="field:'total',resizable:true,align:'right'" width="35%"><strong>Total Nilai</strong></th>
			</tr>
		</thead>
		<div id="tb3" style="padding:2px 5px;">
			<input id="cari" class="easyui-searchbox" data-options="prompt:'Find..',searcher:Show_Filter" style="width:200px;height:26px"></input>
			<span class="button-sep"></span> Show deleted : <input id="show-del2" class="l-btn" type="checkbox"> </input> 
		</div>
		
	</table>
	
<?php		
}

else { header("location:/glt/no_akses.htm"); }

?>