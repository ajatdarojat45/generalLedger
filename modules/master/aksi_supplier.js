var url;

function doAdd(){
	$('#dlg').dialog('open').dialog('setTitle','New');
	$("#kd_sup").textbox({disabled:false});
	$('#fm').form('clear');
	url = 'save_supplier.php';
}

function doEdit(){
	var row = $('#dg').datagrid('getSelected');
	if (row.sup_status!=1) {
		return
	}
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit');
		$("#kd_sup").textbox({disabled:true});
		$('#fm').form('load',row);
		url = 'update_supplier.php?kd_sup='+row.kd_sup;
	}
}

function saveSupplier(){
	$('#fm').form('submit',{
		url: url,
		onSubmit: function(){
			return $(this).form('validate');
		},
		success: function(result){
			var result = eval('('+result+')');
			if (result.errorMsg){
				$.messager.show({
					title: 'Error',
					msg: result.errorMsg
				});
				
			} else {
				$('#dlg').dialog('close');        // close the dialog
				$('#dg').datagrid('reload');    // reload the user data
			}
		}
	});
}
function doDelete(){
	var row = $('#dg').datagrid('getSelected');
	if (row.sup_status!=1) {
		return
	}
	if (row){
		$.messager.confirm('Confirm Delete','Supplier <b>'+row.nm_sup+'</b> akan dihapus ?',function(r){
			if (r){
				$.post('destroy_supplier.php?kd_sup='+row.kd_sup,{kd_sup:row.kd_sup},function(result){
					if (result.success){
						$('#dg').datagrid('reload');    // reload the user data
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
}

function Show_Filter(){	
	if ($('#show-del').is(':checked')==true) { 
		$('#dg').datagrid('reload',{
			showdel: "0",
			cari: $('#cari').val()
		});
	} 
	if ($('#show-del').is(':checked')==false) { 
		$('#dg').datagrid('reload',{
			showdel: "1",
			cari: $('#cari').val()
		});
	}	
}

function tombol_reset()
{
	var a_add=$("#id-add").val();
	var a_edit=$("#id-edit").val();
	var a_del=$("#id-delete").val();
	
	if (a_add=="1"){
		//document.getElementById("btn_add").disabled = false;
		$("#btn_add").linkbutton({disabled:false});
	} else {
		$("#btn_add").linkbutton({disabled:true});
	}
	if (a_edit=="2"){
		$("#btn_edit").linkbutton({disabled:false});
	} else {
		$("#btn_edit").linkbutton({disabled:true});
	}
	if (a_del=="3"){
		$("#btn_del").linkbutton({disabled:false});
	} else {
		$("#btn_del").linkbutton({disabled:true});
	}
	
}


	