<?php 
        session_save_path('./tmp'); 
	session_start();
	// CATEGORIES - 
	// test if session is ok
	$incpath = "./include/";

	require($incpath."testsession.php");
	if (test_session() == 1)
	{
		include($incpath."config.php");
	
		echo "<html>\n<head>\n<title>w3pw Insert new Wallet entry</title>\n";
	
		include($incpath."css.php"); 
		include($incpath."crypt.php"); 
		include($incpath."headerstuff.php");
		include($incpath."mainfunctions.php"); 
	
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
	// display categories here
	
	// initially display numbers only, with names, 
	// for user to enter number
	
	// get categories from database
		if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd))
		{
			if (mysql_select_db($database,$conn))
			{
				$sqlcat = "SELECT catid, catname FROM categories ";

				$listcat = mysql_query($sqlcat);
				//$entriescat = mysql_fetch_object($listcat); // this just gets first entry
				while ($entriescat = mysql_fetch_object($listcat))
				{
					$cat_array[$entriescat->catid]=de_crypt($entriescat->catname,$_SESSION['key']);
					
					// the above is for REAL - IF categories are to be encrypted
					// below is for development OR for real if no encryption of categories
					
					//$cat_array[$entriescat->catid]=$entriescat->catname;
				} // while

				natcasesort($cat_array);
				reset($cat_array);
				//sortcatbyobject($cat_array);


			} // if db exists
			else
			{
				echo "<br />Ooops - <b>Can't connect to the database</b>....\n";
			} // no db
			mysql_close($conn);
		} // if db connect
		else
		{
			echo "<br />Ooops - <b>Can't connect to the database-server</b>...\n";
		} // no db connect

	// display catnames and catids
	
		echo "<tr><td class=\"odd\">Choose the category: </td>";
		echo "<td class=\"even\">";

		echo "<select name=\"catid\" size=\"1\">";
		// put the drop down category list here

		echo "<option value=\"0\">\n";
		echo "- none -\n";
		echo "</option>\n";

		while (list ($catid, $catname) = each ($cat_array))
		{
			echo "<option value=\"$catid\">\n";
			echo "$catname\n";
			echo "</option>\n";
		} // while

		echo "</td></tr>\n";	
		echo "</table>\n";
		echo "<input type=\"submit\" value=\"Save\">\n";
		echo "</form>\n<p>Go back to <a href=\"main.php\">Main Menu</a> without saving.</p></center>\n";		
		echo "</body>\n";
	} // if test session - 1 or ok
?>
