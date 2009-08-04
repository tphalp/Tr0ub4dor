<?php
if (eregi('headerstuff.php', $_SERVER['PHP_SELF']))
{
	die ("You can't access this file directly...");
}
?>

<?php 
// session timout
echo "<meta http-equiv=\"refresh\" content=\"".$session_timeout."; URL=logout.php\">\n";

echo "<script type=\"text/javascript\">\n";
echo "<!--\n";
echo "var aktive = window.setInterval(\"counter()\",1000);\n";
echo "var count = ".$session_timeout.";\n";
echo "function counter() {\n";
echo "count = count - 1;\n";
echo "window.status=count+' seconds left until forced logout';\n";
echo "if(count == 1) window.clearInterval(aktive);\n";
echo "}\n";
echo "//-->\n";
echo "</script>\n";

?>
