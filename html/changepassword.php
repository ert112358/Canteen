<?php

	include("main.php");
	include("tokentools.php");

	$user  = getURLPostParam("user");
	$postToken = getURLPostParam("token");
	$old   = getURLPostParam("old");
	$new   = getURLPostParam("new");
	$token = getURLParam("token");
	
	$errorCode = getURLParam("user");

	if($postToken != ""){
		
		if(verifyToken($postToken) == FALSE)
			redirectArg("changepassword.php",["token","errorCode"],[getToken(),"1"]);
		
		changePassword($user, $postToken, $old, $new);
			
	}

	if($token == "") 
		redirectArg("changepassword.php","token",getToken());
	
	elseif(verifyToken($token) == FALSE)
		redirectArg("changepassword.php",["token","errorCode"],[getToken(),"1"]);
	
?>

<html>
<a href="/index.php"><img style="position: absolute; z-index: 1; left: 5px; top: 5px;" src="/images/home.png"></a>
<head>
<title>Cambio password</title>
<meta charset="UTF-8"></meta>
</head>

<script src="/scripts/params.js"></script>
<script src="/scripts/common.js"></script>

<body background="/images/water.gif">

<center><div id=form style="background-image: url('/images/sand.png'); color: black; text-align: center; position: absolute; top: 50%; right: 0%; left: 50%; transform: translate(-50%,-50%); white-space: pre-wrap; width: 60%">
<?php
switch($errorCode){
	case "" : break;
	case "0": println("Username/Password vecchia errata. Riprovare..."); break;
	case "1": println("Sessione scaduta. Riprovare..."); break;
	default: println("Errore sconosciuto. Riprovare..."); break;
}
?>

Nome utente: <input type="text" id="user"></input>
Password attuale: <input type="password" id="oldpass"></input>
Password nuova: <input type="password" id="newpass"></input>
Password nuova: <input type="password" id="newpass2"></input>
<button onclick="changepwd()">SUBMIT</button>

In caso non si conosca la password attuale, oppure è stata cambiata in modo malintenzionato, <strong>contattare la 5C</strong>

</div></center>

</body>
</html>