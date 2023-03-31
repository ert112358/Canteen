<?php

function getDelay($token){
	$token = decrypt($token);
	if($token == FALSE){
		addLine("tried to hack the token");
		redirect("hackerfound.html");
	}
	
	for($i=0; $token[$i]!='D'; $i++)
		$delay.=$token[$i];
		
	return $delay;
}

function extractToken($str){
	$str = decrypt($str);
	if($str == FALSE){
		addLine("tried to hack the token");
		redirect("hackerfound.html");
	}
	
	$s = "";
	$l = strlen($str);
	
	$i = strpos($str,"D");
	if($i == 0){
		addLine("tried to hack the token");
		redirect("hackerfound.html");
	}
		
	
	for($i++; $i<$l; $i++)
		$s = $s.$str[$i];
	return $s;
}

function verifyToken($str){
	$a = extractToken($str);
	$b = extractToken(getToken(getDelay($str)));
	
	if($a != $b)
		return FALSE;
	else
		return TRUE;
}

function getToken($magic = 0){
	$ts = time();
	
	$magic = intval($magic);
	
	$delay = strval($ts%180);
	
	$m3 = intval((($ts - $magic))/180);
	
	$ip = $_SERVER["REMOTE_ADDR"];
	
	$enctok = $ip . $m3;

	return encrypt($delay."D".$enctok);
}

function getTicket($user){
	$ts = time();
	$hours = intval($ts / 3600);
	
	$hourofday = getHourOfDay(time()) + 2;
	
	if($hourofday > 8 && !isElevated($user) && $GLOBALS["checkTime"]) 
		redirect("timeout.html");
	
	$ip = $_SERVER["REMOTE_ADDR"];
	
	$ticket = encrypt($ip . $hours . $user);

	return str_replace("=","",$ticket);
}

function getUserFromTicket($ticket){
	$ts = time();
	$hours = intval($ts / 3600);
	$ip = $_SERVER["REMOTE_ADDR"];
	
	return substr($ticket,strlen($hours.$ip));
}

function getHourOfDay($ts){
	return ($ts/3600)%24;
}

?>