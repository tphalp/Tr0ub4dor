<?php 
	session_start();
	
	// test if session is ok
	require("include/testsession.php");
	if (test_session() == 1)
	{
		include("include/config.php");
	
		echo "<html>\n<head>\n<title>w3pw Insert new Wallet entry</title>\n";
	
		include("include/css.php"); 
		include("include/headerstuff.php");
	
		// create random password, when enabled
		$initial_pw = "";
		if ($random_pw_length > 0)
		{
			for ($x=0;$x<$random_pw_length;$x++)
			{
				$initial_pw .= chr(rand(33,127));
			}
		}
		
		echo "</head><body>\n";

		echo "<form method=\"post\" action=\"main.php\">\n<input type=\"hidden\" name=\"action\" value=\"save\">\n";
		echo "<center><table>\n";
		echo "<tr><th colspan=\"2\">Insert new Wallet entry</th></tr>\n";
		echo "<tr><td class=\"odd\">Entryname: </td><td class=\"even\"><input type=\"text\" name=\"itemname\" size=\"40\"></td></tr>\n";
		echo "<tr><td class=\"odd\">Host/URL: </td><td class=\"even\"><input type=\"text\" name=\"host\" size=\"40\"></td></tr>\n";
		echo "<tr><td class=\"odd\">Login: </td><td class=\"even\"><input type=\"text\" name=\"login\" size=\"40\"></td></tr>\n";
		echo "<tr><td class=\"odd\">Password: </td><td class=\"even\"><input type=\"text\" name=\"password\" value=\"".$initial_pw."\" size=\"40\"></td></tr>\n";
		echo "<tr><td class=\"odd\">Comment: </td><td class=\"even\"><textarea name=\"comment\" cols=\"40\" rows=\"6\"></textarea></td></tr>\n";
	
		echo "</table>\n";
		echo "<input type=\"submit\" value=\"Save\">\n";
		echo "</form>\n<p>Go back to <a href=\"main.php\">Main Menu</a> without saving.</p></center>\n";		
		echo "</body>\n";
	}
?>