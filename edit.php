<?php 
	session_start();

	// test if session is ok
	require("include/testsession.php");
	if (test_session() == 1)
	{
		include("include/config.php");	
		include("include/crypt.php");
	
		echo "<html>\n<head>\n<title>w3pw Edit Wallet entry</title>\n";
		
		include("include/css.php"); 
		include("include/headerstuff.php");
		
		if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd))
		{
			if (mysql_select_db($database,$conn))
			{
				$list = mysql_query ("SELECT * FROM wallet WHERE ID=".$_GET['ID']);
				$entries = mysql_fetch_object($list);
	
				echo "</head><body>\n";

				echo "<form method=\"post\" action=\"main.php\">\n<input type=\"hidden\" name=\"action\" value=\"editsave\">\n<input type=\"hidden\" name=\"ID\" value=\"".$_GET['ID']."\">";
				echo "<center><table>\n";
				echo "<tr><th colspan=\"2\">Edit Wallet entry</th></tr>\n";
				echo "<tr><td class=\"odd\">Entryname: </td><td class=\"even\"><input type=\"text\" name=\"itemname\" size=\"40\" value=\"".de_crypt($entries->itemname,$_SESSION['key'])."\"></td></tr>\n";
				echo "<tr><td class=\"odd\">Host/URL: </td><td class=\"even\"><input type=\"text\" name=\"host\" size=\"40\" value=\"".de_crypt($entries->host,$_SESSION['key'])."\"></td></tr>\n";
				echo "<tr><td class=\"odd\">Login: </td><td class=\"even\"><input type=\"text\" name=\"login\" size=\"40\" value=\"".de_crypt($entries->login,$_SESSION['key'])."\"></td></tr>\n";
				echo "<tr><td class=\"odd\">Password: </td><td class=\"even\"><input type=\"text\" name=\"password\" size=\"40\" value=\"".de_crypt($entries->pw,$_SESSION['key'])."\"></td></tr>\n";
				echo "<tr><td class=\"odd\">Comment: </td><td class=\"even\"><textarea name=\"comment\" cols=\"40\" rows=\"6\">".de_crypt($entries->comment,$_SESSION['key'])."</textarea></td></tr>\n";

				echo "</table>\n";
				echo "<input type=\"submit\" value=\"Save\">\n";
				echo "</form>\n<br /><br />Go back to <a href=\"main.php\">Main Menu</a> without saving.</center>\n";
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