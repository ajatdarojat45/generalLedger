var url;


function Show_Filter(){
	if ($('#show-del').is(':checked')==true) { 
		$('#dg').datagrid('load',{
			showdel: "0",
			cari: $('#cari').val(),
			filterlvl: $('#filter_lvl').val()
		});
	} 
	if ($('#show-del').is(':checked')==false) { 
		$('#dg').datagrid('load',{
			showdel: "1",
			cari: $('#cari').val(),
			filterlvl: $('#filter_lvl').val()
		});
		
	}	
}

function doAdd(){
	$('#dlg').dialog('open').dialog('setTitle','New');
	//$("#acc").textbox({disabled:false});
	document.getElementById("acc").disabled=false;
	$("#acc").inputmask("9-99-99-99-99");
	
	$('#fm').form('clear');

	document.getElementById("level").value='5';
	document.getElementById("tnd").value='D';
	document.getElementById("jnp").value='N';
	document.getElementById("hpp").value='T';
	document.getElementById("pusat").value='A';
	//$("#jml_sld").textbox('setValue','0');
	
	url = 'save_akun.php';
}

function doEdit(){
	var row = $('#dg').datagrid('getSelected');
	if (row.acc_status!=1) {
		return
	}
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit');
		document.getElementById("acc").disabled=true;
		//$("#acc").inputmask("9-99-99-99-99");
		
		$('#fm').form('load',row);
		
		jml=tandaPemisahTitik($("#jml_sld").val());
		
		//alert(jml);
		
		document.getElementById("jml_sld").value=jml;
		
		url = 'update_akun.php?acc='+row.acc;
	}
}

function saveAkun(){
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
	if (row.acc_status!=1) {
		return
	}
	if (row){
		$.messager.confirm('Confirm Delete','Kode Perkiraan <b>'+row.nmp+'</b> akan dihapus ?',function(r){
			if (r){
				$.post('destroy_akun.php?acc='+row.acc,{acc:row.acc},function(result){
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


function tombol_reset()
{	
	var a_add=$("#id-add").val();
	var a_edit=$("#id-edit").val();
	var a_del=$("#id-delete").val();
	
	if (a_add=="1"){
		$('#btn_add').linkbutton('enable');
	} else {
		$('#btn_add').linkbutton('disable');
	}
	if (a_edit=="2"){
		$('#btn_edit').linkbutton('enable');
	} else {
		$('#btn_edit').linkbutton('disable');
	}
	if (a_del=="3"){
		$('#btn_del').linkbutton('enable');
	} else {
		$('#btn_del').linkbutton('disable');
	}

}




