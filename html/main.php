<?php

header("Cache-Control: no-cache"); // Impedisci al browser di salvare la cache
header("Cache-Control: no-store");
error_reporting(E_ERROR | E_WARNING | E_PARSE); // Disabilita gli avvisi (notice) inutili

if($_SERVER["REQUEST_URI"] == "/main.php") // Impedisci agli "hacker" di accedere a questo file
	print("Ciao! Come va?");

// ============== Costanti globali =============

$key = "*48U78YJ4a=nEa#8TQ7_D^2A!T3%Ggyh";

$keysFile   = "../keys/keys.csv";

$menuFile   = "orders/menu.csv";
$ordersFile = "orders/orders.txt";

$rickRoll   = "hacker.webm";
$logFile    = "activity.log";

$verFile    = "information/currentVersion.txt";
$version    = getVersion();

$isBusy = FALSE; // Manutenzione
$checkTime = FALSE; // Controlla l'ora dell'ordine

if($GLOBALS["isBusy"])
	message("MANUTENZIONE IN CORSO. Si prega di riprovare in un secondo momento. Grazie!");

// ================= Classi ====================

class MenuItem{
	private $id    = "";
	private $name  = "";
	private $price = "";
	private $type  = "";
	
	function __construct($fil){
		$temp = fgetcsv($fil);
		
		$this->id    = $temp[0];
		$this->name  = $temp[1];
		$this->price = $temp[2];
		$this->type  = $temp[3];
	}
	
	function getId(){
		return $this->id;
	}
	
	function getName(){
		return $this->name;
	}
	
	function getPrice(){
		return $this->price;
	}
	
	function Type(){
		return $this->type;
	}
}

class Item{
	private $item   = "";
	private $amount = "";
	
	function __construct($item, $amount){
		$str = $amount;
		$amount = floatval($amount);

		if(strlen(strval($amount)) != strlen($str))
			$amount = NULL;
		
		$this->amount = $amount;
		$this->item   = $item;
	}
	
	function getAmount(){
		return $this->amount;
	}
	
	function getItem(){
		return $this->item;
	}
}

class Order{
	private $arr   = [];
	private $len   = 0;
	private $corrupted = FALSE;
	
	function __construct($str){
		$str = base64_decode($str);
		$jsonObject = json_decode($str,true);
		$orders = $jsonObject["orders"];
		
		if(is_null($jsonObject)){
			$this->corrupted = TRUE;
			return;
		}
		
		$this->len = count($orders);
		
		for($i=0; $i<$this->len; $i++){
			$item =   array_keys($orders[$i])[0];
			$amount = array_values($orders[$i])[0];
			
			if(gettype($item) != "string" || gettype($amount) != "string")
				$this->corrupted = TRUE;
			
			$this->arr[$i] = new Item($item, $amount);
		}
	}
	
	function getItemAt($index){
		if($index > $this->len)
			return NULL;
		else
			return $this->arr[$index];
	}
	
	function len(){
		return $this->len;
	}
	
	function isCorrupted(){
		return $this->corrupted;
	}
}

class User{
	private $user = "";
	private $pass = "";
	private $elev = FALSE;
	
	function __construct($fil){
		$temp = fgetcsv($fil);
		
		$this->user = $temp[0];
		$this->pass = $temp[1];
		
		if($temp[2] == "true")
			$this->elev = TRUE;
		else
			$this->elev = FALSE;
		
	}
	
	function getUser(){
		return $this->user;
	}
	function getPass(){
		return $this->pass;
	}
	function isElev(){
		return $this->elev;
	}
}


// ================ Funzioni ===================

// Aggiusta le stringhe
function fixSpace($str){ // Eventually, it will be removed, or at least improved
	$len = strlen($str);
		
	for($i=0; $i<$len; $i++)
		if($str[$i] == ' ' || $str[$i] == '.')
			$str[$i] = '+';
		
	return $str;
}

// Funzioni di redirect (reindirizzamento)
function redirect($doc){
	header("location: $doc");
	exit(0);
}
function redirectArg($doc, $argnames, $argcontents){
	
	$str = "location: $doc?";
	
	if(gettype($argnames) == "array" && gettype($argnames) == "array"){
		$argnamesCount = count($argnames);
		$argcontentsCount = count($argcontents);
		
		if($argnamesCount != $argcontentsCount)
			message("ERRORE 0x2");
		
		for($i=0; $i<$argcontentsCount; $i++)
			$str.=$argnames[$i]."=".$argcontents[$i]."&";
		
		$str[strlen($str)-1] = ' ';
	}
	
	elseif(gettype($argnames) == "string" && gettype($argnames) == "string"){
		$str .= $argnames;
		$str .= "=";
		$str .= $argcontents;
	}
	
	else message("ERRORE 0x1");
	
	header($str);
	exit(0);
}

// Funzioni di log
function println($s){   // Stampa una stringa con ritorno a capo (<br>)
	print("$s<br>");
}
function message($str){ // Stampa una schermata contenente un messaggio e lo sfondo
	print("<html style=\"background-image: url(/images/stars2.gif); color: white;\">");
	print("<style>a { color: white; }</style>");
	println($str);
	print("Ritorna alla <a href=index.php>pagina iniziale</a>");
	exit(0);
}
function hack($str){    // Stampa una schermata che avvisa l'utente di un fallito tentativo di hacking
	print("<style>a { color: white; }</style>");
	print("<html style=\"background-image: url(/images/stars2.gif); color: white;\">");
	println($str . "<br><img src=images/cat.jpg></img>");
	print("Ritorna alla <a href=index.php>pagina iniziale</a>");
	exit(0);
}
function addLine($str){ // Aggiungi una stringa al file activity.log
	$f = fopen($GLOBALS["logFile"],"a");
	fprintf($f,$_SERVER["REMOTE_ADDR"]." $str ".strftime("on %e %h %Y at %X")."\r\n");
	fclose($f);
}

// Funzioni di gestione password, autenticazione ecc.
function getPass($user){
	$in = fopen($GLOBALS["keysFile"],"r");
	while(!feof($in)){
		$us = new User($in);
		if($us->getUser() == $user) return decrypt($us->getPass());
	}
	return "";
}
function auth($user, $token, $providedPass){
	$pass = getPass($user);
	
	if($pass == $providedPass && $pass!=""){
		addLine("logged in as $user");
		redirectArg("menu.php","ticket",getTicket($user));
	}
	else
		redirectArg("login.php",["token","errorCode"],[getToken(),"0"]);
}
function changePassword($user, $token, $old, $new){                          // Da migliorare (!)
	$pass = getPass($user);
	
	if($user=="" || $old=="" || $new=="")
		return;
	
	if($old != $pass)
		message("Ripristino password fallito. Controllare se la password precedente sia stata inserita in modo corretto, e che non siano state effettuate modifiche non autorizzate alla pagina.");

	$enc = encrypt($new);
	
	$in = fopen($GLOBALS["keysFile"],"r");

	$a = fgetcsv($in);
	$all = "";
	
	while(!feof($in)){
		if($a[0] == $user)
			$a[1] = $enc;
		$all .= implode(",",$a);
		$all .= "\n";
		$a = fgetcsv($in);
	}

	fclose($in);

	$out = fopen($GLOBALS["keysFile"],"w");
	fwrite($out,$all);
	fclose($out);
	
	addLine("reset the password of $user");
	
	message("Password resettata.");
	
	exit(0);
}
function isElevated($user){
	$f = fopen($GLOBALS["keysFile"],"r");
	while(!feof($f)){
		$us = new User($f);
		
		if($us->getUser() == $user)
			if($us->isElev() == TRUE)
				return TRUE;
			else 
				return FALSE;
	}
	fclose($f);
	return FALSE;
}


// Altre funzioni
function hasOrdered($user){                                                  // Da migliorare (!)
	$f = fopen($GLOBALS["ordersFile"],"r");
	$s = "";
	
	while(!feof($f)){
		$s = fgets($f);
		if($s == "$user: \r\n")
			return TRUE;
	}
	
	return FALSE;
}

// Ottieni i parametri inviati via GET e POST
function getURLParam($name){
	return fixSpace($_GET[$name]);
}
function getURLPostParam($name){
	return fixSpace($_POST[$name]);
}

// Funzioni di crittografia
function decrypt($data){
	return openssl_decrypt($data,"aes-256-ecb",$GLOBALS['key']);
}
function encrypt($data){
	return openssl_encrypt($data,"aes-256-ecb",$GLOBALS['key']);
}

// Ottiene la versione di Canteen
function getVersion(){
	$f = file($GLOBALS["verFile"]);
	return implode("",$f);
}
?>