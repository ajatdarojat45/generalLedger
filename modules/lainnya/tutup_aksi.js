// JavaScript Document
function proses_on(nama_proses,nil_selesai)
{	var $bar = $('.progress-bar');

	$bar.css('width',nil_selesai);
	$("#tampil_selesai").html(nama_proses+" "+nil_selesai+" Complete...");
	
	//$("#img_proses").html("<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true' name='tombol-x'>X</button><img src='../../img/processing.gif'> <b> Tunggu...</b> Proses tutup bulan sedang berlangsung </div>");
	
}

function proses_reset()

{	var $bar = $('.progress-bar');
	$bar.width('0%');
	
	//$("#img_proses").html("");
	
	//$('.progress').removeClass('active');
}