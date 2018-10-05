<?php
session_start();

//print_r($_POST);
//print_r($_GET);

if (isset($_SESSION['app_glt'])) {
	
?>
	
	<table id="dg-induk" class="easyui-datagrid" title="" style="width:99%;height:96%;padding:0px;"
			data-options="rownumbers:true,singleSelect:true,url:'get_trans.php?tipe=K',pagination:true,pageSize:50,pageList:[50,100,250,500,1000,2000],
							rowStyler: function(index,row){
								if (row.trx_status!=1){
									return 'background-color:#999999;color:#fff;font-weight:bold;text-decoration: line-through;';
								}
							},
							onSelect: function(index,row){
								$('#dg-det').datagrid({
									queryParams: {
										filtertrans:row.nbk
									}
								});
								mdate=tglmdy(row.tgp);
								$('#nbk').textbox('setValue',row.nbk);
								$('#tgp').textbox('setValue',mdate);
								$('#ket').textbox('setValue',row.ket);
								mtotal=formatangkaduadec(row.total);
								$('#total').textbox('setValue',mtotal);
								if (row.totdebet!=row.totkredit){ $('#infobal').show();} else { $('#infobal').hide();}
							},toolbar:'#tb3'">
		<thead>
			<tr>
				<th data-options="field:'tgp',resizable:true,align:'left',sortable:true,formatter:myformattgl" width="25%"><strong>Tanggal</strong></th>
				<th data-options="field:'nbk',resizable:true,align:'left',sortable:true" width="35%"><strong>Nomor Bukti</strong></th>
				<th data-options="field:'total',resizable:true,align:'right',sortable:true,formatter:formatangkaduadec" width="30%"><strong>Total Nilai</strong></th>
			</tr>
		</thead>
		<div id="tb3" style="padding:2px 5px;">
			<input id="cari" class="easyui-searchbox" data-options="prompt:'Find..',searcher:Show_Filter" style="width:200px;height:26px"></input>
			<!-- Tidak bisa digunakan karena data program lama data diinput dengan nomor bukti yang sama-->
			<!--<span class="button-sep"></span> Show deleted : <input id="show-del" class="l-btn" type="checkbox" onchange="Show_Filter()"> </input> -->
		</div>		
	</table>
	
	
<?php		
}

else { header("location:/glt/no_akses.htm"); }

?>