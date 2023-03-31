<?php

include("main.php");

if($_GET["ticket"] == "")
	redirect("login.php");
	
?>

<html>

<a href="/index.php"><img style="position: absolute; right: 5px; top: 5px;" src="/images/home.png"></a>

<style>
body{
	background-image: url(images/hills2.png);
    background-position-y: bottom;
	background-repeat: repeat-x;
	margin: unset;
}
html{
	background-image: url(images/stars2.gif);
}
input{
	width: 100%;
}
</style>

<body onload="setIDs()" text=white>
<meta charset="UTF-8">
<title>Menu</title>

<script src="/scripts/params.js"></script>
<script src="/scripts/common.js"></script>
<script src="/scripts/menucontroller.js"></script>

<div id=price style="position: fixed; left: 8px; top: 8px;">Costo complessivo: 0</div>

<img src="/images/moon.png" id=moon style="position: absolute; right: 100px; top: 100px; width: 300px; height: 300px; z-index: -2;">

<center>

<h1>Bibite</h1>
<table border=5 width=40% bgcolor=white id=drinks style="color: black;">
<tr>
	<th width="70%">Articolo</th>
	<th width="15%">Prezzo</th>
	<th width="15%">Quantità</th>
</tr>

<?php

$f = fopen($GLOBALS["menuFile"],"r");

while(($item = new MenuItem($f))->Type() == "drink"){
	print("<tr>");
	
	print("<td id=");
	print($item->getId());
	print(">");
	print($item->getName());  // Esempio: <td id=d1>Acqua naturale 50 cc</td>
	print("</td>");
	
	print("<td>");
	print($item->getPrice());
	print("</td>");
	
	print("<td><input type=\"number\" value=0 min=0 max=10></td></tr>");
}

?>
</table>

<h1>Cibi</h1>
<table border=5 width=60% bgcolor=white id=food style="color: black;">

<tr>
	<th width="70%">Articolo</th>
	<th width="15%">Prezzo</th>
	<th width="15%">Quantità</th>
</tr>

<?php

while(TRUE){
	print("<tr>");
	
	print("<td id=");
	print($item->getId());
	print(">");
	print($item->getName());  // Esempio: <td id=d1>Acqua naturale 50 cc</td>
	print("</td>");
	
	print("<td>");
	print($item->getPrice());
	print("</td>");
	
	print("<td><input type=\"number\" value=0 min=0 max=10></td>");
	
	if(feof($f)) break;
	
	$item = new MenuItem($f);
}

?>

</table>

<br>
<img src="/images/order.png" onclick="order()" width=200 height=60>
<div id=price2>Costo complessivo: 0</div>

</center>

</body>
</html>