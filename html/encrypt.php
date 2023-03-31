<?php
	
	include("main.php");
	
	$key = $GLOBALS['key'];
	$data = $_GET["data"];

	if($data!=""){
		$data = fixSpace($data);
		
		print("Password: ".openssl_encrypt($data,"aes-256-ecb",$key));
		
		exit(0);
	}
?>

<html>

<head>
<title>Password Encryptor</title>
<meta charset="UTF-8">
</head>

<script src="/scripts/jsencrypt.js"></script>
<script src="/scripts/common.js"></script>

<body>
Questo strumento è riservato agli amministratori che vogliono cambiare la password.<br>
Le password sono cifrate con una chiave privata (AES-256-EBC).<br>
Per mantenere la sicurezza, è stata sviluppata questa pagina atta a cifrare le password.<br>
<br>
<input id="pass" type="text"></input><button onclick="enc()">Send</button>

<script>
function enc(){
	var s = document.getElementById("pass").value;
	location.href = "/encrypt.php?data="+s;
}
</script>

</body>

</html>