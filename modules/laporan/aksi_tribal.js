// JavaScript Document
function klik_set() 
{	var rpt=$("#rpt-sel").val();

	$("#area_isian").html(rpt+" : <br>");
	$("#area_isian").append("PERIODE <br><br>");
	$("#area_isian").append("BULAN :<br>");
	$("#area_isian").append("TAHUN :<br><br>");
	$("#area_isian").append("<button class='btn btn-primary' type='button' id='btn-close' name='btn_close' onclick='klik_close()'>close</button>");
}

function klik_proses() 
{	var laporan=$("#rpt-sel").val();
	var idmenu=$("#id-menu").val();
	var per_bln=$("#rpt-bln").val();
	var per_thn=$("#rpt-thn").val();

	$("#area_isian").html("");
	
	$("#btn_go").button('loading'); 
	
	alert(laporan+"&id_menu="+idmenu+"&per_thn="+per_thn+"&per_bln="+per_bln);
	
	document.getElementById("frame_pdf").src = laporan+"&id_menu="+idmenu+"&per_thn="+per_thn+"&per_bln="+per_bln;
	
	$("#btn_go").button('reset');
}

function klik_close() 
{
	$("#area_isian").html("");
}

function klik_pilih_laporan() 
{	var e = document.getElementById("rpt-sel");
	var strLap = e.options[e.selectedIndex].text;
	$("#area_isian").html("");
	
	//alert(strLap);
	
	document.getElementById("frame_pdf").src = "";
	
	document.getElementById("rpt-bln").disabled=false;
	
	if (strLap=="Neraca Lajur Per Bulan") {
		document.getElementById("rpt-bln").disabled=true;
	}
}




