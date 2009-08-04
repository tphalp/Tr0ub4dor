<?php
	session_start();

	// test if session is ok
	require("include/testsession.php");
	if (test_session() == 1)
	{
		include("include/config.php");	
		include("include/crypt.php");
	
		echo "<html>\n<head>\n<title>w3pw Wallet entry</title>\n";
		
		include("include/css.php"); 
		include("include/headerstuff.php");
		
		if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd))
		{
			if (mysql_select_db($database,$conn))
			{
				$list = mysql_query ("SELECT * FROM wallet WHERE ID=".$_GET['ID']);
				$entries = mysql_fetch_object($list);
				
				echo "</head><body>\n";

				echo "<center><table>\n";
				echo "<tr><th colspan=\"2\">View Wallet entry</th></tr>\n";
				echo "<tr><td class=\"odd\">Entryname: </td><td class=\"even\">".de_crypt($entries->itemname,$_SESSION['key'])."</td></tr>\n";
				echo "<tr><td class=\"odd\">Host/URL: </td><td class=\"even\">";
				if (de_crypt($entries->host,$_SESSION['key'])) { 
					// create link if there is a host url
					echo "<a href=\"";
					
					// if host does not start with http:// add this
					if ((!strstr (de_crypt($entries->host,$_SESSION['key']), 'http://')) && (!strstr (de_crypt($entries->host,$_SESSION['key']), 'https://')) && (!strstr (de_crypt($entries->host,$_SESSION['key']), 'ftp://')))
					{
						echo "http://";
					}
					echo de_crypt($entries->host,$_SESSION['key']);
					echo "\" target=\"newwin\">"; 
				}
				echo de_crypt($entries->host,$_SESSION['key']);
				if (de_crypt($entries->host,$_SESSION['key'])) { echo "</a>"; }
				echo "</td></tr>\n";
		
				echo "<tr><td class=\"odd\">Login: </td><td class=\"even\">".htmlentities(de_crypt($entries->login,$_SESSION['key']))."</td></tr>\n";
				echo "<tr><td class=\"odd\">Password: </td><td class=\"even\">".htmlentities(de_crypt($entries->pw,$_SESSION['key']))."</td></tr>\n";
				
				// change cr's to <br>
				echo "<tr><td class=\"odd\">Comment: </td><td class=\"even\">".str_replace("\n", "<br />", htmlentities(de_crypt($entries->comment,$_SESSION['key'])))."</td></tr>\n";
				echo "</table>\n";
				echo "<p>Go back to <a href=\"main.php\">Main Menu</a>.</p></center>\n";
				
			}
			else
			{
				echo "<br />Ooops - <b>Can't connect to the database</b>....\n";
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
