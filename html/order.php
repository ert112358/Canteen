<title>Ordina</title>

<?php

include("main.php");
include("tokentools.php");

$orderLimit    = 30;
$qtyLimit      = 20;
$orderLimitBis = 80;
$qtyLimitBis   = 200;

$ticket = getURLPostParam("ticket");
$data   = getURLPostParam("data");

if(strlen($data) > 1024){
	addLine("Tried to overflow the site");
	hack("ERRORE: Payload enorme. Probabile tentativo di hacking. Altrimenti contattare la 5C.");
}

if($ticket == "")
	redirect("login.php");

$decTik = decrypt($ticket);

$user = getUserFromTicket($decTik); // Ottieni il nome utente tramite il ticket

if($ticket != getTicket($user)){
	addLine("used an invalid/expired ticket");
	message("Sessione scaduta o Ticket invalido. Ripetere la procedura di Login.");
}

if(hasOrdered($user) == TRUE)
	message("Ordine già effettuato. Non è possibile annullarlo. Leggere il regolamento.");

$order = new Order($data);

if($order->isCorrupted()){
	addLine("Tried to send a corrupted payload");
	hack("ERRORE: Payload corrotto. Probabile tentativo di hacking. Altrimenti contattare la 5C.");
}

$price = 0;
$qty   = 0;
$len   = $order->len();

checkRepeatedItems($order);            // Poliziotto 1
checkForHackers($order);               // Poliziotto 2

for($i=0; $i<$len ; $i++){             // Calcolo prezzo totale
	$item = $order->getItemAt($i);
	
	$qtyTmp = $item->getAmount();
	
	$qty   += $qtyTmp;
	$price += $qtyTmp * getArticlePrice($item->getItem());
}

checkForMistakes($user, $qty, $price); // Poliziotto 3

writeLine("\n".$user.": ");
println("Buongiorno, $user. Ecco gli articoli ordinati: ");

// <-- a questo punto, non potrebbero esserci ordini hackerati. Ripeto: non "dovrebbero"

for($i=0; $i<$len ; $i++){ // Riassunto ordine.
	$item = $order->getItemAt($i);

	println($item->getAmount()." ".getArticleName($item->getItem()));
	writeOrder($item);
}

writeLine("Prezzo totale: ".$price."€");
println("Prezzo totale: ".$price."€");

addLine("($user) placed an order");
message("L'ordine è stato effettuato con successo. Si ricorda di segnalare eventuali problematiche/richieste alla classe 5C.<br>Buona lezione!");


function getArticleName($id){ // ID:Name:Price
	$f = fopen($GLOBALS["menuFile"],"r");
	
	while(!feof($f)){
		$it = new MenuItem($f);
		if($it->getId() == $id)
			return $it->getName();
	}
	
	return "";
}

function getArticlePrice($id){
	$f = fopen($GLOBALS["menuFile"],"r");
	
	while(!feof($f)){
		$it = new MenuItem($f);
		if($it->getId() == $id)
			return floatval($it->getPrice());
	}
	
	return -1;
}


function writeLine($str){
	$f = fopen($GLOBALS["ordersFile"],"a");
	
	fputs($f,$str."\r\n");
	
	fclose($f);
}

function writeOrder($item){
	$f = fopen($GLOBALS["ordersFile"],"a");
	
	fputs($f,$item->getAmount()." ".getArticleName($item->getItem())."\r\n");
	
	fclose($f);
}


function checkForMistakes($user, $qty, $price){ // Poliziotto 1
	if($qty == 0){
		addLine("tried to order with empty cart");
		hack("Carrello vuoto.");
	}
	
	$qtyLimit      = $GLOBALS["qtyLimit"];
	$orderLimit    = $GLOBALS["orderLimit"];
	$qtyLimitBis   = $GLOBALS["qtyLimitBis"];
	$orderLimitBis = $GLOBALS["orderLimitBis"];
	
	if(isElevated($user)){
		if($qty > $qtyLimitBis)
			message("I docenti non possono ordinare più di $qtyLimitBis articoli.");
		if($price > $orderLimitBis)
			message("I docenti non possono ordinare articoli il cui prezzo complessivo supera i $orderLimitBis Euro.");
	}
	else{
		if($qty > $qtyLimit)
			message("Gli studenti non possono ordinare più di $qtyLimitBis articoli.");
		if($price > $orderLimit)
			message("Gli studenti non possono ordinare articoli il cui prezzo complessivo supera i $qtyLimitBis Euro.");
	}
}

function checkForHackers($order){               // Poliziotto 2
	$len = $GLOBALS["len"];
	for($i=0; $i<$len; $i++){
		$item = $order->getItemAt($i);
		
		if(isFloat($item->getAmount())){              // Se la quantità è un float, double o simili
			addLine("tried to order [float] things");
			hack("Presenti quantità decimali.");
		}
		
		if($item->getAmount() == 0 || $item->getAmount() == NULL){  // Se la quantità è una stringa oppure NULL/NaN e simili
			addLine("tried to order [string] things");
			hack("Presenti quantità invalide.");
		}
		
		if(getArticlePrice($item->getItem()) < 0){                  // Se c'è un articolo sconosciuto (f99,d80)
			addLine("tried to order an unknown item");
			hack("ERRORE: articolo ".$item->getItem()." non trovato. Probabile tentativo di hacking. Altrimenti contattare la classe 5C.");
		}
		
		if($item->getAmount() > 10 || $item->getAmount() <= 0){     // Se la quantità è > 10 o negativa
			addLine("tried to order more than intended");
			hack("Trovato uno o più articoli con quantità maggiori di 10 o negative.");
		}
	}
}

function checkRepeatedItems($order){            // Poliziotto 3
	$len = $order->len();
	for($i=0; $i<$len-1; $i++)
		for($j=$i+1; $j<$len; $j++)
			if($order->getItemAt($i)->getItem() == $order->getItemAt($j)->getItem())
				hack("Uno o più articoli ripetuti.");
}


function isFloat($n){
	return (intval($n) - $n != 0);
}

function revertPoints($str){
	$len = strlen($str);
		
	for($i=0; $i<$len; $i++)
		if($str[$i] == '+')
			$str[$i] = '.';
		
	return $str;
}


?>