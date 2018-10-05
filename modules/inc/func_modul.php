<?php
//--Convert into Indonesian Format Date
function get_system_date($sys_date){
$d_sys = substr($sys_date, 8, 2);
$y_sys = substr($sys_date, 0, 4);

//--Month
$m_sys = substr($sys_date, 5, 2);

if($m_sys == "01")
	$month_sys = "Januari";
else if($m_sys == "02")
	$month_sys = "Februari";
else if($m_sys == "03")
	$month_sys = "Maret";
else if($m_sys == "04")
	$month_sys = "April";
else if($m_sys == "05")
	$month_sys = "Mei";
else if($m_sys == "06")
	$month_sys = "Juni";
else if($m_sys == "07")
	$month_sys = "July";
else if($m_sys == "08")
	$month_sys = "Agustus";
else if($m_sys == "09")
	$month_sys = "September";
else if($m_sys == "10")
	$month_sys = "Oktober";
else if($m_sys == "11")
	$month_sys = "November";
else if($m_sys == "12")
	$month_sys = "Desember";
//--End Month

//$system_date = $d_sys." ".$month_sys." ".$y_sys;
$system_date = $month_sys." ".$y_sys;
return $system_date;
}


//--Convert into Indonesian Format Date
function get_bulan($sys_date){
//--Month
$m_sys = substr($sys_date, 5, 2);

if($m_sys == "01")
	$month_sys = "Januari";
else if($m_sys == "02")
	$month_sys = "Februari";
else if($m_sys == "03")
	$month_sys = "Maret";
else if($m_sys == "04")
	$month_sys = "April";
else if($m_sys == "05")
	$month_sys = "Mei";
else if($m_sys == "06")
	$month_sys = "Juni";
else if($m_sys == "07")
	$month_sys = "Juli";
else if($m_sys == "08")
	$month_sys = "Agustus";
else if($m_sys == "09")
	$month_sys = "September";
else if($m_sys == "10")
	$month_sys = "Oktober";
else if($m_sys == "11")
	$month_sys = "November";
else if($m_sys == "12")
	$month_sys = "Desember";
//--End Month

//$system_date = $d_sys." ".$month_sys." ".$y_sys;
$system_date = $month_sys;
return $system_date;
}

//--For System Date -- Closing System
function get_current_system_date($sys_date){
$d_sys = substr($sys_date, 8, 2);
$y_sys = substr($sys_date, 0, 4);

$m_sys = substr($sys_date, 5, 2);

if($m_sys == "01")
	$month_sys = "Januari";
else if($m_sys == "02")
	$month_sys = "Februari";
else if($m_sys == "03")
	$month_sys = "Maret";
else if($m_sys == "04")
	$month_sys = "April";
else if($m_sys == "05")
	$month_sys = "Mei";
else if($m_sys == "06")
	$month_sys = "Juni";
else if($m_sys == "07")
	$month_sys = "July";
else if($m_sys == "08")
	$month_sys = "Agustus";
else if($m_sys == "09")
	$month_sys = "September";
else if($m_sys == "10")
	$month_sys = "Oktober";
else if($m_sys == "11")
	$month_sys = "November";
else if($m_sys == "12")
	$month_sys = "Desember";
//--End Month

$system_date_current = $month_sys." ".$y_sys;
return $system_date_current;
}



//--Convert into Indonesian Format Date
function get_system_date_simple($sys_date){
$d_sys = substr($sys_date, 8, 2);
$y_sys = substr($sys_date, 0, 4);

//--Month
$m_sys = substr($sys_date, 5, 2);

if($m_sys == "01")
	$month_sys = "Jan";
else if($m_sys == "02")
	$month_sys = "Feb";
else if($m_sys == "03")
	$month_sys = "Mar";
else if($m_sys == "04")
	$month_sys = "Apr";
else if($m_sys == "05")
	$month_sys = "Mei";
else if($m_sys == "06")
	$month_sys = "Jun";
else if($m_sys == "07")
	$month_sys = "Jul";
else if($m_sys == "08")
	$month_sys = "Aug";
else if($m_sys == "09")
	$month_sys = "Sept";
else if($m_sys == "10")
	$month_sys = "Okt";
else if($m_sys == "11")
	$month_sys = "Nov";
else if($m_sys == "12")
	$month_sys = "Des";
//--End Month

$system_date_simple = $d_sys." ".$month_sys." ".$y_sys;
return $system_date_simple;
}


//--Convert into Pengkodean
function get_system_date_pengkodean($sys_date){
$d_sys = substr($sys_date, 8, 2);
$y_sys = substr($sys_date, 2, 2);

//--Month
$m_sys = substr($sys_date, 5, 2);

if($m_sys == "01")
	$month_sys = "I";
else if($m_sys == "02")
	$month_sys = "II";
else if($m_sys == "03")
	$month_sys = "III";
else if($m_sys == "04")
	$month_sys = "IV";
else if($m_sys == "05")
	$month_sys = "V";
else if($m_sys == "06")
	$month_sys = "VI";
else if($m_sys == "07")
	$month_sys = "VII";
else if($m_sys == "08")
	$month_sys = "VIII";
else if($m_sys == "09")
	$month_sys = "IX";
else if($m_sys == "10")
	$month_sys = "X";
else if($m_sys == "11")
	$month_sys = "XI";
else if($m_sys == "12")
	$month_sys = "XII";
//--End Month

$system_date_kodean = $month_sys."/".$y_sys;
return $system_date_kodean;
}

function tampil_uang($number, $fractional=false) { 
    if ($fractional) { 
        $number = sprintf('%.2f', $number); 
    } 
    while (true) { 
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number); 
        if ($replaced != $number) { 
            $number = $replaced; 
        } else { 
            break; 
        } 
    } 
    return $number; 
} 

function ubah_tgl($ambiltgl) { 
	// 2014-01-01
	$ambiltgl_tgl=substr($ambiltgl,8,2);
	$ambiltgl_bln=substr($ambiltgl,5,2);
	$ambiltgl_thn=substr($ambiltgl,0,4);
	$ambil_tgl=$ambiltgl_tgl."-".$ambiltgl_bln."-".$ambiltgl_thn;
	//echo $ambil_tgl;
    return $ambil_tgl; 
} 

function set_tglblntahun($ambiltgl) { 
	// 2014-01-01
	$ambiltgl_tgl=substr($ambiltgl,8,2);
	$ambiltgl_bln=substr($ambiltgl,5,2);
	$ambiltgl_thn=substr($ambiltgl,0,4);
	$ambil_tgl=$ambiltgl_tgl."-".$ambiltgl_bln."-".$ambiltgl_thn;
	//echo $ambil_tgl;
    return $ambil_tgl; 
} 

function pilih_sub()
{
		echo "<script> alert('test'); </script>";
	
}

//--Convert into Indonesian Format Date
function ambil_bulan($bln){
//--Month
$m_sys = $bln;

if($m_sys == "01")
	$month_sys = "Januari";
else if($m_sys == "02")
	$month_sys = "Februari";
else if($m_sys == "03")
	$month_sys = "Maret";
else if($m_sys == "04")
	$month_sys = "April";
else if($m_sys == "05")
	$month_sys = "Mei";
else if($m_sys == "06")
	$month_sys = "Juni";
else if($m_sys == "07")
	$month_sys = "July";
else if($m_sys == "08")
	$month_sys = "Agustus";
else if($m_sys == "09")
	$month_sys = "September";
else if($m_sys == "10")
	$month_sys = "Oktober";
else if($m_sys == "11")
	$month_sys = "November";
else if($m_sys == "12")
	$month_sys = "Desember";
//--End Month

//$system_date = $d_sys." ".$month_sys." ".$y_sys;
$nama_bulan = $month_sys;
return $nama_bulan;
}

if (!function_exists('session_unregister')) {
    function session_unregister($var) { unset($_SESSION[$var]); }
}

if (!function_exists('session_register')) {
    function session_register($var) { $_SESSION[$var]=$user_name; }
}

?>