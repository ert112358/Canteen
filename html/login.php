<?php
	include("main.php");
	include("tokentools.php");

	$user        = getURLPostParam("user");
	$postToken   = getURLPostParam("token");
	$payload     = getURLPostParam("pass");
	$token       = getURLParam("token");
	
	$errorCode   = getURLParam("errorCode");

	if($postToken != ""){
		if(verifyToken($postToken) == FALSE)
			redirectArg("login.php",["token","errorCode"],[getToken(),"1"]);

		if($user!="" && $postToken!="" && $payload!="")
			auth($user, $token, $payload); // ==> vai alla pagina degli ordini, se tutto va bene
	}

	elseif($token == "") 
		redirectArg("login.php",["token"],[getToken()]);
	
	elseif(verifyToken($token) == FALSE)
		redirectArg("login.php",["token","errorCode"],[getToken(),"1"]);

?>

<html>

<a href="/index.php"><img style="z-index: -1;" src="/images/home.png"></a>

<head>
<meta charset="UTF-8">
<title>Pagina di Login</title>
</head>

<script src="/scripts/params.js"></script>
<script src="/scripts/common.js"></script>

<body id="body" background="/images/passbg.gif" style="background-color: black; white-space: break-spaces;">

<div class="passwordinput" style="color: white; text-align: center; position: absolute; top: 50%; right: 0%; left: 0%; transform: translate(0%, -50%);">
<?php
switch($errorCode){
	case "" : break;
	case "0": println("Username e/o Password errata. Riprovare..."); break;
	case "1": println("Sessione scaduta. Riprovare..."); break;
	default: println("Errore sconosciuto. Riprovare..."); break;
}
?>

<center><table style="color: white; text-align: center;"><tr>
	<td>Username: </td>
	<td><input type="text" id="user"></td>
</tr>
<tr>
	<td>Password: </td>
	<td><input type="password" id="pass"></td></tr></table></center>
<button type="button" onclick="submit()">Submit</button>
<button type="button" onclick="location.href = 'changepassword.php'">Cambia password</button>

È consigliabile che solo i <strong>rappresentanti di classe</strong> detengano la password.
Le credenziali sono <strong>case sensitive</strong> (es. 4c è diverso da 4C)

<img src="images/warning.png" height=14 width=14></img> Attenzione: effettua il login entro 3 minuti!
</div>
	
</script>

</body>
</html>