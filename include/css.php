<?php
if (eregi('css.php', $_SERVER['PHP_SELF']))
{
	die ("You can't access this file directly...");
}
?>
<STYLE TYPE="text/css">
	body { font-family:verdana,arial;font-size:10px;}
	td { font-family: verdana,arial;font-size:10px; }
	th { background-color:#000000; color:#ffffff;font-size:10px; }
	tr.odd { background-color:#a3a3a3; color:#000000; }
	tr.even { background-color:#dddddd; color:#000000; }
	td.odd { background-color:#a3a3a3; color:#000000; }
	td.even { background-color:#dddddd; color:#000000; }
	tr.menu { background-color:#667788; color:#ffffff; text-decoration:underline; text-align:center; }
	a.menu { color:#ffffff; text-decoration:none; font-weight:bold;font-size:10px;}
	tr.odd:hover { background-color: #def; }
	tr.even:hover { background-color: #def; }
	h1.update { color:#a8a8a8; font-size:48px; font-weight:bold; }
</STYLE>
