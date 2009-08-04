<?php 
	session_start();

	// test if session is ok
	require("include/testsession.php");
	if (test_session() == 1)
	{
		include("include/config.php");	
		include("include/crypt.php");
	
		echo "<html>\n<head>\n<title>w3pw Delete Wallet entry</title>\n";
		
		include("include/css.php"); 
		include("include/headerstuff.php");
		
		if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd))
		{
			if (mysql_select_db($database,$conn))
			{
				$list = mysql_query ("SELECT * FROM wallet WHERE ID=".$_GET['ID']);
				$entries = mysql_fetch_object($list);
				
				echo "</head><body>\n";

				echo "<form method=\"post\" action=\"main.php\">\n<input type=\"hidden\" name=\"action\" value=\"reallydelete\">\n<input type=\"hidden\" name=\"ID\" value=\"".$_GET['ID']."\">";
				echo "<center><table>\n";
				echo "<tr><th colspan=\"2\">Delete Wallet entry</th></tr>\n";
				echo "<tr><td class=\"odd\">Entryname: </td><td class=\"even\">".de_crypt($entries->itemname,$_SESSION['key'])."</td></tr>\n";
				echo "<tr><td class=\"odd\">Host/URL: </td><td class=\"even\">".de_crypt($entries->host,$_SESSION['key'])."</td></tr>\n";
				echo "<tr><td class=\"odd\">Login: </td><td class=\"even\">".de_crypt($entries->login,$_SESSION['key'])."</td></tr>\n";
				echo "<tr><th colspan=\"2\">Really delete this entry?</th></tr>\n";

				echo "</table>\n";
				echo "<input type=\"submit\" value=\"YES - Delete\">\n";
				echo "</form>\n<br /><br />No, go back to <a href=\"main.php\">Main Menu</a> without deleting.</center>\n";
			}
			else
			{
				echo "<br />Ooops - <b>Can't find the database</b>....\n";
			}
			mysql_close($conn);
		}
		else
		{
			echo "<br />Ooops - <b>Can't connect to the database-server</b>...\n";
		}
		echo "</body>\n";
	}
?>