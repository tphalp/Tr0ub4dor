<?php

if ((isset($_POST['step2'])) && ($_POST['step2'] == "true"))
{
	// second step of the installation, ask 
	// for the password of the previous installation
	
	echo "<html>\n<head>\n";
	include("include/config.php"); 
	include("include/css.php"); 
	include("include/crypt.php");
	echo "</head>\n<body>\n";
	echo "<h1 class=\"update\">Update Step 2</h1>\n";
	
	// first, check if previous DB Name is not the same as
	// the name for the new installation
	if ($database == $_POST['dbname'])
	{
		echo "<p>You've entered the database name of the current, new installation. This upgrade routine does not support upgrade into the same database. Please go back and either choose a different database name for your previous installation (you also have to rename your existing database) or choose a different database name for your new installation in the file <b>include/config.php</b>.</p>\n";
		echo "<a href=\"javascript:history.back()\">go to previous step</a>\n";
	}
	else
	{
		$dberror=0;
		// check if the database name given exists
		if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd))
		{
			if (mysql_select_db($_POST['dbname'],$conn))
			{
				// ask for the password
				echo "<p>Please enter the password from your previous wp3w installation. This is needed, because the de- and encryption routines have changed and this upgrade process needs to re-encrypt all entries. The password itself remains unchanged!</p>\n";
				echo "<form action=\"update.php\" method=\"post\"><input type=\"hidden\" name=\"step3\" value=\"true\"><input type=\"hidden\" name=\"dbname\" value=\"".$_POST['dbname']."\">\n";
				echo "Password: <input type=\"password\" name=\"pw\" value=\"\" size=\"20\">&nbsp; <input type=\"submit\">\n";
				echo "</form>\n";
			}
			else
			{
				$dberror=1;
			}
		}
		else
		{
			$dberror=1;
		}
		
		if ($dberror == 1)
		{
			echo "<p>Can't access the database <b>".$_POST['dbname']."</b>. Please go to the previous step and check the database name and also the mysql authorization variables in the file <b>include/config.php</b>.</p>\n";
			echo "<a href=\"javascript:history.back()\">go to previous step</a>\n";
		}
	}
	echo "</body>\n</html>";
}
elseif ((isset($_POST['step3'])) && ($_POST['step3'] == "true"))
{
	// thirs step of the installation, read entries from previous
	// installation and encrypt with new routines
	
	echo "<html>\n<head>\n";
	include("include/config.php"); 
	include("include/css.php"); 
	include("include/crypt.php");
	echo "</head>\n<body>\n";
	echo "<h1 class=\"update\">Update Step 3</h1>\n";

	$dberror=0;
	if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd))
	{
		if (mysql_select_db($_POST['dbname'],$conn))
		{
			// use old pw encryption
			$crypt_pw = crypt($_POST['pw'],"c7");
			$list = mysql_query ("SELECT version, pw FROM main");
			$entries = mysql_fetch_object($list);
			$db_pw=$entries->pw;
			if ($crypt_pw == $db_pw)
			{
				// try to connect to the 1.40 database
				if ($conn_new = mysql_connect($hostname, $dbuser, $dbpasswd, true))
				{
					if (mysql_select_db($database,$conn_new))
					{
						$copy_db_error=0;
						// insert data into main table
						$db_passwd = sha1($_POST['pw']);
						mysql_query ("DELETE FROM main", $conn_new);
						$result = mysql_query ("INSERT INTO main VALUES ('1.40','".$db_passwd."')", $conn_new);
						if (!$result)
						{
							$copy_db_error=1;
							// try to delete so that the table remains clean
							mysql_query ("DELETE FROM main", $conn_new);
						}
						else
						{	
							$key_130 = crypt($_POST['pw'],"56");
							$key_140 = md5("%dJ9&".strtolower($_POST['pw'])."(/&k.=".strtoupper($_POST['pw'])."1x&%");
							
							// now, read & decrypt wallet from previous installation
							$list = mysql_query ("SELECT * FROM wallet", $conn);
							while ($entries = mysql_fetch_object($list))
							{
								// and insert into new db
								$result = mysql_query("INSERT INTO wallet VALUES (".
								$entries->ID.",'".
								addslashes(encrypt140(decrypt130($entries->itemname,$key_130),$key_140))."','".
								addslashes(encrypt140(decrypt130($entries->host,$key_130),$key_140))."','".
								addslashes(encrypt140(decrypt130($entries->login,$key_130),$key_140))."','".
								addslashes(encrypt140(decrypt130($entries->pw,$key_130),$key_140))."','".
								addslashes(encrypt140(decrypt130($entries->comment,$key_130),$key_140))."')",$conn_new);
								
								if ($result == FALSE)
								{
									$copy_db_error=1;
								}	
							}
						}
						if ($copy_db_error == 0)
						{
							echo "Update was successful. Please remove the file <b>update.php</b> from the Document Root and <a href=\"index.php\">restart w3pw</a>.";
						}
						else
						{
							echo "Update failed. Please try again.";
							
							// try to delete so that the tables remain clean
							mysql_query ("DELETE FROM wallet", $conn_new);
							mysql_query ("DELETE FROM main", $conn_new);
						}
					}
					else
					{
						echo "<p>Can't connect to the database <b>".$database."</b> defined in the configuration file <b>include/config.php</b>. Please check and try again.</p>\n";
						echo "<a href=\"javascript:history.back()\">go to previous step</a>\n";
					}
				}
			}
			else
			{
				// wrong password entered
				echo "<p>The password you have entered does not match the password stored in the database. Please go to the previous step and try again.</p>\n";
				echo "<a href=\"javascript:history.back()\">go to previous step</a>\n";
			}
		}
		else
		{
			$dberror=1;
		}
	}
	else
	{
		$dberror=1;
	}
	
	if ($dberror == 1)
	{
		echo "<p>Can't access the database <b>".$_POST['dbname']."</b>. Please go to the previous step and try again.</p>\n";
		echo "<a href=\"javascript:history.back()\">go to previous step</a>\n";
	}
	echo "</body>\n</html>";
}
else
{
	// we don't need an HTML header & body, because
	// update.php is included from index.php
	echo "<h1 class=\"update\">Update Step 1</h1>\n";
	echo "<p>If you don't want to update from a previous version of w3pw, please delete the file <b>update.php</b> in the document root of this w3pw installation and reload this page in your browser.</p>";
	
	echo "<p>Otherwise, please enter the database name of the <b>previous</b> w3pw installation an press the submit button to proceed.</p>\n";
	echo "<form action=\"update.php\" method=\"post\"><input type=\"hidden\" name=\"step2\" value=\"true\">\n";
	echo "Database name: <input type=\"text\" name=\"dbname\" value=\"\">&nbsp; <input type=\"submit\">\n";
	echo "</form>\n";
}

function decrypt130($data, $key)
{
	$iv = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB), MCRYPT_RAND);
	return trim(mcrypt_decrypt (MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv));	
}

function encrypt140($data, $key)
{

	$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$encrypted_data = mcrypt_generic($td, $data);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
    
	return $encrypted_data;	
}
?>