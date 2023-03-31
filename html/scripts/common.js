function goTo(url, args){
	var x = document.createElement("form");
	var splitted = args.split("&");
	var len = splitted.length;
	
	x.action = url;
	x.method = "POST";
	
	for(var i=0; i<len; i++){
		var y = document.createElement("input");
		
		y.hidden = true;
		y.type = "text";
		y.name = splitted[i].split("=")[0];
		y.value = splitted[i].split("=")[1];
		
		x.appendChild(y);
	}
	
	document.getElementsByTagName("body")[0].appendChild(x);
	
	x.submit();
}

function submit(str){
	const token = getParam("token");
	
	var user = document.getElementById("user").value;
	var pass = document.getElementById("pass").value;

	if(user=="" || pass=="") {alert("Uno o più campi lasciati vuoti"); return;}
	
	goTo("login.php","user="+user+"&pass="+pass+"&token="+token);
}

function changepwd(){
	const token = getParam("token");
	
	var user = document.getElementById("user").value;
	var oldp = document.getElementById("oldpass").value;
	var newp = document.getElementById("newpass").value;
	var newp2 = document.getElementById("newpass2").value;
	
	if(user==""||oldp==""||newp==""||newp2=="") {alert("Uno o più campi lasciati vuoti. Riprovare."); return;}
	if(newp != newp2) {alert("Le nuove password non corrispondono. Riprovare."); return;}
	
	goTo("changepassword.php","user="+user+"&old="+oldp+"&new="+newp+"&token="+token);
}