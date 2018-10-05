function thnblntgl(cdate){
	//Format tanggal untuk textbox
	var ss = (cdate.split('-'));
	var d = parseInt(ss[0],10);
	var m = parseInt(ss[1],10);
	var y = parseInt(ss[2],10);

	return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
}


function tglmdy(cdate){
	//Format tanggal untuk textbox
	var ss = (cdate.split('-'));
	var y = parseInt(ss[0],10);
	var m = parseInt(ss[1],10);
	var d = parseInt(ss[2],10);

	return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
}

function myformattgl(val,row){
	// Format tanggal untuk tabel grid
	var ss = (val.split('-'));
	var y = parseInt(ss[0],10);
	var m = parseInt(ss[1],10);
	var d = parseInt(ss[2],10);
	
	return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
}

function myformatter(date){
	// formatter untuk datebox
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	var d = date.getDate();
	//return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
	return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
}

function myparser(s){
	// parser untuk datebox
	//if (!s) return new Date(d,m-1,y);
	if (!s) return new Date();
	var ss = (s.split('-'));
	var d = parseInt(ss[0],10);
	var m = parseInt(ss[1],10);
	var y = parseInt(ss[2],10);
	
	//alert (d);
	//alert (m);
	//alert (y);
	
	if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
		mDate=new Date(y,m-1,d);
		return mDate;
	} else {
		return new Date();
	}
}

function myparsertgl(s){
	// parser untuk datebox
	//var y = s.getFullYear();
	//var m = s.getMonth()+1;
	//var d = s.getDate();
	//return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
	return s;
}

function formatangkaduadec(val, row) {
    return number_format(val, '2', '.', ',');
}

function formatangkatanpadec(val, row) {
    return number_format(val, '', '.', ',');
}

function number_format(num, dig, dec, sep) {
    x = new Array();
    s = (num < 0 ? "-" : "");
    num = Math.abs(num).toFixed(dig).split(".");
    r = num[0].split("").reverse();
    for (var i = 1; i <= r.length; i++) {
        x.unshift(r[i - 1]);
        if (i % 3 == 0 && i != r.length) x.unshift(sep);
    }
    //return "Rp " + s + x.join("") + (num[1] ? dec + num[1] : "");
    return ""+s + x.join("") + (num[1] ? dec + num[1] : "");
}

   
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

function checkCookie() {
    var user = getCookie("username");
    if (user != "") {
        alert("Welcome again " + user);
    } else {
        user = prompt("Please enter your name:", "");
        if (user != "" && user != null) {
            setCookie("username", user, 365);
        }
    }
}

function tandaPemisahTitik(b){
	var _minus = false;
	if (b<0) _minus = true;
	b = b.toString();
	b=b.replace(",","");
	b=b.replace("-","");
	c = "";
	panjang = b.length;
	j = 0;
	for (i = panjang; i > 0; i--){
		 j = j + 1;
		 if (((j % 3) == 1) && (j != 1)){
		   c = b.substr(i-1,1) + "," + c;
		 } else {
		   c = b.substr(i-1,1) + c;
		 }
	}
	if (_minus) c = "-" + c ;
	return c;
}

function numbersonly(ini, e){
	if (e.keyCode>=49){
		if(e.keyCode<=57){
		a = ini.value.toString().replace(",","");
		b = a.replace(/[^\d]/g,"");
		b = (b=="0")?String.fromCharCode(e.keyCode):b + String.fromCharCode(e.keyCode);
		ini.value = tandaPemisahTitik(b);
		return false;
		}
		else if(e.keyCode<=105){
			if(e.keyCode>=96){
				//e.keycode = e.keycode - 47;
				a = ini.value.toString().replace(",","");
				b = a.replace(/[^\d]/g,"");
				b = (b=="0")?String.fromCharCode(e.keyCode-48):b + String.fromCharCode(e.keyCode-48);
				ini.value = tandaPemisahTitik(b);
				//alert(e.keycode);
				return false;
				}
			else {return false;}
		}
		else {
			return false; }
	}else if (e.keyCode==48){
		a = ini.value.replace(",","") + String.fromCharCode(e.keyCode);
		b = a.replace(/[^\d]/g,"");
		if (parseFloat(b)!=0){
			ini.value = tandaPemisahTitik(b);
			return false;
		} else {
			return false;
		}
	}else if (e.keyCode==95){
		a = ini.value.replace(",","") + String.fromCharCode(e.keyCode-48);
		b = a.replace(/[^\d]/g,"");
		if (parseFloat(b)!=0){
			ini.value = tandaPemisahTitik(b);
			return false;
		} else {
			return false;
		}
	}else if (e.keyCode==8 || e.keycode==46){
		a = ini.value.replace(",","");
		b = a.replace(/[^\d]/g,"");
		b = b.substr(0,b.length -1);
		if (tandaPemisahTitik(b)!=""){
			ini.value = tandaPemisahTitik(b);
		} else {
			ini.value = "";
		}
		
		return false;
	} else if (e.keyCode==9){
		return true;
	} else if (e.keyCode==17){
		return true;
	} else {
		//alert (e.keyCode);
		return false;
	}

}

function bersihPemisah(ini){
	a = ini.toString().replace(",","");
	//a = a.replace(".","");
	return a;
}