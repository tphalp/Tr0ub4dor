<?php 
	session_start();
	
	// test if session is ok
	require("include/testsession.php");
	if (test_session() == 1)
	{
		include("include/config.php");
	
		echo "<html>\n<head>\n<title>Import Wallet entries (Step 2)</title>\n";
	
		include("include/css.php"); 
		include("include/headerstuff.php");
	
		echo "</head><body>\n";

		if (is_uploaded_file($HTTP_POST_FILES['csvfile']['tmp_name'])) 
		{
			// upload successful
			copy($HTTP_POST_FILES['csvfile']['tmp_name'], $tmppath."w3pw.csv");
			
			// consistency checks - check number of semicolons in each line
			$linecounter=0;
			$nr_of_delims=0;
			$errorlines="";
			
			$fd = fopen ($tmppath."w3pw.csv", "r");
			while (!feof($fd)) {
				$buffer = fgets($fd, 4096);
				$linecounter++;
				
				if (strlen($buffer)>1)
				{
					// use number of semicolons in the first line as reference
					if ($linecounter == 1)
					{
						$nr_of_delims = substr_count($buffer, ";");
					}
					else
					{
						$nr_of_delims_thisline = substr_count($buffer, ";");

						if (($nr_of_delims_thisline != $nr_of_delims) || ($nr_of_delims_thisline > 5))
						{
							$errorlines.=$linecounter.",";
						}
					}
				}
			}
			fclose ($fd);
			
			if ($errorlines)
			{
				// data inconsistency
				echo "<p>Data Inconsistency: Not enough/Too much delimiters in following lines: ".$errorlines."</p>";
				
			}
			else
			{
				// uploaded file ok - now make filed assignments
				$field_headers = array("Entry name", "Host/URL", "Login", "Password", "Comment");
				
				echo "<form method=\"post\" action=\"main.php\">\n<input type=\"hidden\" name=\"action\" value=\"import\">\n";
				echo "<center><table>\n";
				echo "<tr><th colspan=\"5\">Make field assignments</th></tr>\n";
				echo "<tr class=\"odd\">";
				for ($x=0;$x<=4;$x++)
				{
					echo "<td><select name=\"row[".$x."]\">\n";
					reset ($field_headers);
					while (list ($field_number, $field_name) = each ($field_headers)) 
					{
	    					echo "<option value=\"".$field_number."\"";
						if ($field_number == $x)
						{
							echo " selected";
						}
						echo ">".$field_name."</option>\n";
					}
					echo "</select></td>\n";
				}
				echo "</tr>\n";
				
				// show first two lines of uploaded data
				$linecounter=0;
			
				$fd = fopen ($tmppath."w3pw.csv", "r");
				while ($data = fgetcsv ($fd, 4096, ";"))
				{
					$linecounter++;

					if ($linecounter <= 2)
					{
						echo "<tr class=\"even\">";
						for ($x=0;$x<=4;$x++)
						{
							echo "<td>".$data[$x]."</td>\n";
							
						}
						echo "</tr>\n";
					}
				}
				fclose ($fd);
				
				echo "</table>\n";
				echo "<input type=\"submit\" value=\"Save\"><br /><br />\n";
				
				echo "Only the first two lines of your file are shown here!<br /><br />\n";
			}
			echo "Go back to <a href=\"main.php\">Main Menu</a> without importing the contents of the file.</center>";
		}
		else
		{
			// error while uploading
			echo "Error while uploading file. Try <a href=\"javascript:history.back()\">again</a>.";
		}
		echo "</body>\n";
	}
?>