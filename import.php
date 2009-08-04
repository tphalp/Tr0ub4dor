<?php 
	session_start();
	
	// test if session is ok
	require("include/testsession.php");
	if (test_session() == 1)
	{
		include("include/config.php");
	
		echo "<html>\n<head>\n<title>Import Wallet entries (Step 1)</title>\n";
	
		include("include/css.php"); 
		include("include/headerstuff.php");
	
		echo "</head><body>\n";

		echo "<form enctype=\"multipart/form-data\" action=\"import2.php\" method=\"post\">\n";
		echo "<center><table>\n";
		echo "<tr><th colspan=\"2\">Upload CSV File with Wallet entries</th></tr>\n";
		echo "<tr><td class=\"odd\">Filename: </td><td class=\"even\"><input type=\"file\" name=\"csvfile\" size=\"40\"></td></tr>\n";
		echo "</table>\n";
		echo "<input type=\"submit\" value=\"Upload\">\n";
		echo "</form>\n<br /><br />Go back to <a href=\"main.php\">Main Menu</a>.</center>\n";
		
		echo "</body>\n";
	}
?>
