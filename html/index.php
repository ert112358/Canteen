<?php
include("main.php");
?>

<html style="background-color: #D8D8D8;">

<head>
<meta charset="UTF-8">
<title>Canteen</title>
</head>

<body>
<center>

<img src="/images/welcome.gif"></center>
<p style="font-family: Times New Roman; white-space: pre-line;">

Benvenuto nella piattaforma <strong>Canteen</strong>, che permette di ordinare gli articoli della mensa direttamente dal proprio dispositivo!
Per entrare è necessario fornire il nome utente (la classe o il professore) e la password nella <a href="/login.php">pagina di accesso</a>.

Non è consentito eseguire ordinazioni esagerate (tipo: 10 pizze o 1000€ di articoli) o ordinare al di fuori degli orari stabiliti.

Se si riscontrano bug/errori/incoerenze in qualsiasi operazione, è opportuno contattare un amministratore (5C, Sala Server) e segnalarlo.

È possibile, inoltre, ripristinare la password <a href="/changepassword.php">qui</a>. Nel caso si smarrisca o nel caso altri utenti la conoscono, <strong>contattare la classe 5C</strong>.

Infine, <strong>questo sito NON utilizza i cookie</strong>.

</p>



<p>Canteen v<?php print($GLOBALS["version"]); ?> &#169 Made by E.P. 2020-2021</p>

</body>
</html>