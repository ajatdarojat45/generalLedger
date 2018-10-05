<?php  if ($prod == "waktu") {  ?>

	<script language="Javascript">
		var days = Array('Minggu','Senin','Selasa','Rabu','Kamis','Jum\'at','Sabtu');
		var months = Array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		/*
		function tampilkanjam()
		{
			var dt = new Date();
			var date = (dt.getDate()<10)? '0'+dt.getDate() : dt.getDate();
			var day = days[dt.getDay()];
			var month = months[dt.getMonth()];
			var waktu = new Date();
			var jam = waktu.getHours();
			var menit = waktu.getMinutes();
			var detik = waktu.getSeconds();
			var teksjam = new String();	
			if (jam == 1)
			{ jam = "01"; }
			else if (jam == 2)
			{ jam = "02"; }
			else if (jam == 3)
			{ jam = "03"; }	
			else if (jam == 4)
			{ jam = "04"; }
			else if (jam == 5)
			{ jam = "05"; }
			else if (jam == 6)
			{ jam = "06"; }
			else if (jam == 7)
			{ jam = "07"; }
			else if (jam == 8)
			{ jam = "08"; }
			else if (jam == 9)
			{ jam = "09"; }
			if (jam == 12)
			{ jam = "12"; ampm = "PM"; }
			else if (jam == 13)
			{ jam = "01"; ampm = "PM"; }
			else if (jam == 14)
			{ jam = "02"; ampm = "PM"; }
			else if (jam == 15)
			{ jam = "03"; ampm = "PM"; }
			else if (jam == 16)
			{ jam = "04"; ampm = "PM"; }
			else if (jam == 17)
			{ jam = "05"; ampm = "PM"; }
			else if (jam == 18)
			{ jam = "06"; ampm = "PM"; }
			else if (jam == 19)
			{ jam = "07"; ampm = "PM"; }
			else if (jam == 20)
			{ jam = "08"; ampm = "PM"; }
			else if (jam == 21)
			{ jam = "09"; ampm = "PM"; }
			else if (jam == 22)
			{ jam = "10"; ampm = "PM"; }
			else if (jam == 23)
			{ jam = "11"; ampm = "PM"; }
			else if (jam == 0)
			{ jam = "00"; ampm = "AM"; }
			else 
			{ ampm = "AM"; }
			if(menit <= 9)
				menit = "0" + menit;
			if(detik <= 9)
				detik = "0" + detik;
			teksjam = day+', '+date+' '+month+' '+dt.getFullYear()+'  -  '+ jam + ":" + menit + ":" + detik+"   " + ampm;
			tempatjam.innerHTML = teksjam;
			setTimeout("tampilkanjam()",1000);
		} window.onload = tampilkanjam*/
		
	</script>

<? 	}  ?>