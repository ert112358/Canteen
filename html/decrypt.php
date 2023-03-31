<?php
	include("main.php");
	
	$key = $GLOBALS['key'];
	$data = $_GET["data"];

	if($data!=""){
		$data = fixSpace($data);
	
		print("Password: ".openssl_decrypt($data,"aes-256-ecb",$key));
		
		exit(0);
	}
?>

<html>

<head>
<title>Password Decryptor</title>
<meta charset="UTF-8">
</head>

<script src="/scripts/common.js"></script>

<body>
Questo strumento è riservato agli amministratori che vogliono cambiare la password.<br>
Le password sono cifrate con una chiave privata (AES-256-EBC).<br>
Per mantenere la sicurezza, è stata sviluppata questa pagina atta a decifrare le password.<br>
<br>
<input id="pass" type="text"></input><button onclick="dec()">Send</button>

<script>
function dec(){
	var s = document.getElementById("pass").value;
	location.href = "/decrypt.php?data="+s;
}
</script>

</body>

</html>