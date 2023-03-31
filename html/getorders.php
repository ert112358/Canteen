<pre style="font-family: Times New Roman; white-space: pre-wrap;">
<?php
include("main.php");
include("tokentools.php");

$version = $GLOBALS["version"];
$ordersFile = $GLOBALS["ordersFile"];

print("Canteen v.$version. Stampato il: ".strftime("%e %h %Y alle %X<br>"));
print("<strong>&#169 Made by E.P. 2020-2021</strong><br>");
print("<script>print()</script>");
readFile($ordersFile);
?>
</pre>