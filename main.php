<?php
	session_start();
	include("include/config.php"); 
?>
<html>
<head>
<title>w3pw Main</title>
<?php 
	include("include/css.php"); 
	include("include/headerstuff.php");
	include("include/crypt.php");
?>
</head>
<body>

<?php

	// session active?
	if (!isset($_SESSION['logged_in']))
	{
		// no session active - check pw
		if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd))
		{
			if (mysql_select_db($database,$conn))
			{
				$cleartext_pw = "";
				// encrypt the pw given at logon
				if (isset($_POST['password']))
				{
					$cleartext_pw = $_POST['password'];
				}
				$crypt_pw = sha1($cleartext_pw);

				// check pw
				$list = mysql_query ("SELECT version, pw FROM main");
				$entries = mysql_fetch_object($list);
				$db_pw=$entries->pw;
				if ($crypt_pw == $db_pw)
				{
					// password match - proceed
					$_SESSION['logged_in'] = 1;
					$_SESSION['key'] = md5("%dJ9&".strtolower($cleartext_pw)."(/&k.=".strtoupper($cleartext_pw)."1x&%");
					// delete cleartext pw in memory
					unset($cleartext_pw);
					$_SESSION['version']=$entries->version;
				}
				else
				{
					session_unset();
					session_destroy();
					echo "<body><b>Wrong Password</b>....<br />try <a href=\"index.php\">again</a>\n";
				}
			}
			else
			{
				// cant connect to database
				session_unset();
				session_destroy();	
				echo "<br />Ooops - <b>Can't connect to the database</b>....<br />Please try <a href=\"index.php\">again</a>\n";
			}
			mysql_close($conn);
		}
		else
		{
			// cant connect to the server
			session_unset();
			session_destroy();
			echo "<br />Ooops - <b>Can't connect to the database-server</b>...<br />Please try <a href=\"index.php\">again</a>\n";
		}
	}
	
	if ((isset($_SESSION['logged_in'])) && ($_SESSION['logged_in'] == 1))
	{
		// session is active
		
		// any actions to perform?
		if (isset($_POST['action']))
		{
			// save new entry
			if ($_POST['action'] == "save")
			{
				if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd))
				{
					if (mysql_select_db($database,$conn))
					{
						$list = mysql_query ("INSERT INTO wallet VALUES('','".
						mysql_escape_string (en_crypt($_POST['itemname'],$_SESSION['key']))."','".
						mysql_escape_string (en_crypt($_POST['host'],$_SESSION['key']))."','".
						mysql_escape_string (en_crypt($_POST['login'],$_SESSION['key']))."','".
						mysql_escape_string (en_crypt($_POST['password'],$_SESSION['key']))."','".
						mysql_escape_string (en_crypt($_POST['comment'],$_SESSION['key']))."')");
						
						unset($_POST['itemname'], $_POST['host'], $_POST['login'], $_POST['password'], $_POST['comment']);
					}
					else
					{
						echo "<br />Ooops - <b>can't find the database</b>....\n";
					}
					mysql_close($conn);
				}
				else
				{
					echo "<br />Ooops - <b>can't connect to the database-server</b>...\n";
				}
				
			}
			
			// save edited entry
			if ($_POST['action'] == "editsave")
			{
				if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd))
				{
					if (mysql_select_db($database,$conn))
					{
						$list = mysql_query ("UPDATE wallet SET itemname='".mysql_escape_string(en_crypt($_POST['itemname'],$_SESSION['key'])).
						"', host='".mysql_escape_string(en_crypt($_POST['host'],$_SESSION['key'])).
						"', login='".mysql_escape_string(en_crypt($_POST['login'],$_SESSION['key'])).
						"', pw='".mysql_escape_string(en_crypt($_POST['password'],$_SESSION['key'])).
						"', comment='".mysql_escape_string(en_crypt($_POST['comment'],$_SESSION['key'])).
						"' WHERE ID=".$_POST['ID']);
						
						unset($_POST['itemname'], $_POST['host'], $_POST['login'], $_POST['password'], $_POST['comment']);
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
			}
			
			// delete entry
			if ($_POST['action'] == "reallydelete")
			{
				if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd))
				{
					if (mysql_select_db($database,$conn))
					{
						$list = mysql_query ("DELETE FROM wallet WHERE ID=".$_POST['ID']);
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
			}
			
			// import uploaded file
			if ($_POST['action'] == "import")
			{
	
				$row = $_POST['row'];
	
				// check that each header field is used only once in import2.php
				
				// sort header_fields by occurence
				asort($row);
				
				if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd))
				{
					if (mysql_select_db($database,$conn))
					{
						// finally import the data
						
						$fd = fopen ($tmppath."w3pw.csv", "r");
						while ($data = fgetcsv ($fd, 4096, ";"))
						{
							if (count($data)>1)
							{
								$mysql_string="INSERT INTO wallet VALUES(''";
	
								reset($_POST['row']);
								while (list ($index, $val) = each ($_POST['row'])) 
								{
									$mysql_string.=",'".mysql_escape_string(en_crypt($data[$val],$_SESSION['key']))."'";
								}
								$mysql_string.=")";
								mysql_query ($mysql_string);
								unset($mysql_string);
							}
						}
						fclose ($fd);
						
						unset($row);	
						unset($data);
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
				
			}
		}
		
		// check if there is an uploaded file still in the tmp directory -> delete
		if (is_file($tmppath."w3pw.csv"))
		{
			unlink ($tmppath."w3pw.csv");
		}
		
		// menu header
		echo "<center><table width=\"100%\" style=\"table-layout:fixed\">\n<tr class=menu>\n";
		echo "<td><a href=\"main.php\" class=\"menu\">list</a></td><td><a href=\"insert.php\" class=\"menu\">new entry</a></td><td><a href=\"import.php\" class=\"menu\">import</a></td><td><a href=\"logout.php\" class=\"menu\">logout</a></td>\n";
		echo "</tr></table></center><p>\n";
		
		if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd))
		{
			if (mysql_select_db($database,$conn))
			{
				$list = mysql_query ("SELECT ID, itemname FROM wallet");
				$header_array = array();
				while ($entries = mysql_fetch_object($list))
				{
					$header_array[$entries->ID]=de_crypt($entries->itemname,$_SESSION['key']);
				}
				
				natcasesort($header_array);
				reset($header_array);
				
				$counter=0;
				while (list ($ID, $itemname) = each ($header_array)) 
				{
					$counter++;
					$list = mysql_query ("SELECT host FROM wallet WHERE ID=".$ID);
					$entries = mysql_fetch_object($list);
					
					// table header
					if ($counter == 1)
					{
						echo "<center><table width=\"100%\" style=\"table-layout:fixed\"><tr><th style=\"width:140px\">Entryname</th><th>Host/URL</th><th style=\"width:32px\">&nbsp;</th><th style=\"width:32px\">&nbsp;</th><th style=\"width:45px\">&nbsp;</th></tr>\n";
					}
					
					// show entries
					if ($counter % 2 == 0)
					{
						echo "<tr class=\"even\">";
					}
					else
					{
						echo "<tr class=\"odd\">";
					}					
					echo "<td>".$itemname."</td><td>".de_crypt($entries->host,$_SESSION['key'])."</td><td>&nbsp;<a href=\"view.php?ID=".$ID."\">view</a>&nbsp;</td><td>&nbsp;<a href=\"edit.php?ID=".$ID."\">edit</a>&nbsp;</td><td>&nbsp;<a href=\"delete.php?ID=".$ID."\">delete</a>&nbsp;</td></tr>\n";
					
				}
				
				// table footer
				if ($counter >= 1)
				{
					echo "</table></center>";
				}
				
				unset($header_array,$itemname);
				echo "<p>w3pw v".$_SESSION['version']."</p>";
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
	}
?>
</body>
</html>