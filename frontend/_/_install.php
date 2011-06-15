<?php
/* $Id$ */
session_cache_limiter('nocache');
session_start();

require_once("lib/config.php");
require_once("lib/common_func.php");

if ((isset($_POST['step2'])) && ($_POST['step2'] == "true")) {
  // STEP 2 -------------------------------------------------------

	// second step of the installation, ask 
	// for the password of the previous installation
  $out__ = write_header_begin("Install Step 2 of 3");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common(); 
  $out__ .= write_header_end();
	$out__ .= '<h1 class="upgrade">Install Step 2 of 3</h1>';
	
	// first, check if previous DB Name is not the same as
	// the name for the new installation
	if (DB_NAME == $_POST['dbname']) {
		$out__ .= <<<OUT
      <p>You've entered the database name of the current, new installation. This install routine does not support install into the same database.</p>
      <p>Please go back and either choose a different database name for your previous installation (you also have to rename your existing database) or choose a different database name for your new installation in the file <b>lib/config.php</b>.</p>
      <p>Please refere to the INSTALL file in the root of your w3pw installation for preliminary steps required prior to coming this install script.</p>
      <a href="javascript:history.back();">&lArr; go to previous step</a>
OUT;

	} else {
		$dberror=0;
		// check if the database name given exists
		if ($conn = mysql_connect(DB_HOST, DB_USER, DB_PASS))	{
			if (mysql_select_db($_POST['dbname'], $conn))	{
				// ask for the password
				$out__ .= <<<OUT

          <p>Please enter the password from your previous wp3w installation. This is needed, because the decryption and encryption routines have changed and this install process needs to re-encrypt all entries. The password itself will remain unchanged.</p>
          <form action="__install.php" method="post">
            <input type="hidden" name="step3" value="true" />
            <input type="hidden" name="dbname" value="{$_POST['dbname']}" />
          <label for="pw">Password:</label><br />
          <input type="password" name="pw" id="pw" value="" size="20" /><br />
          <input type="submit" />
          </form>
OUT;
      }	else {
				$dberror=1;
			}
		}	else {
			$dberror=1;
		}
		
		if ($dberror == 1) {
			$out__ .= '<p>Can\'t access the database <strong>'. $_POST['dbname'] .'</strong>. Please go to the previous step and check the database name and also the mysql authorization variables in the file <strong>lib/config.php</strong>.</p>';
			$out__ .= '<a href="javascript:history.back();">&lArr; go to previous step</a>';
		}
	}
	
  $out__ .= write_footer_end();  
  
  //Output the contents
  echo $out__;  
  // STEP 2 -------------------------------------------------------
  
} elseif ((isset($_POST['step3'])) && ($_POST['step3'] == "true")) {
  
  // STEP 3 -------------------------------------------------------
	// this step of the installation, read entries from previous
	// installation and encrypt with new routines

  $out__ = write_header_begin("Install Step 3 of 3");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common(); 
  $out__ .= write_header_end();	
	$out__ .= '<h1 class="upgrade">Install Step 3 of 3</h1>';

	$dberror=0;
	if ($conn = mysql_connect(DB_HOST, DB_USER, DB_PASS))	{
		if (mysql_select_db($_POST['dbname'], $conn)) {
			// use old pw encryption
			$crypt_pw = crypt($_POST['pw'], "c7");
			$list = mysql_query ("SELECT version, pw FROM main");
			$entries = mysql_fetch_object($list);
			$db_pw=$entries->pw;
			if ($crypt_pw == $db_pw) {
				// try to connect to the 1.40 database
				if ($conn_new = mysql_connect(DB_HOST, DB_USER, DB_PASS, TRUE))	{
					if (mysql_select_db(DB_NAME, $conn_new)) {
						$copy_db_error=0;
						// insert data into main table
						$db_passwd = sha1($_POST['pw']);
						mysql_query ("DELETE FROM main", $conn_new);
						$result = mysql_query ("INSERT INTO main VALUES ('1.5.0','".$db_passwd."')", $conn_new);
            
						if (!$result)	{
							$copy_db_error=1;
							// try to delete so that the table remains clean
							mysql_query ("DELETE FROM main", $conn_new);
						}	else {	
							$key_130 = crypt($_POST['pw'],"56");
							$key_140 = md5("%dJ9&".strtolower($_POST['pw'])."(/&k.=".strtoupper($_POST['pw'])."1x&%");
							
							// now, read & decrypt wallet from previous installation
							$list = mysql_query ("select * from wallet;", $conn);
							while ($entries = mysql_fetch_object($list)) {
								// and insert into new db
								$result = mysql_query("INSERT INTO wallet VALUES (".
								$entries->ID.",'".
								addslashes(encrypt140(decrypt130($entries->itemname,$key_130),$key_140))."','".
								addslashes(encrypt140(decrypt130($entries->host,$key_130),$key_140))."','".
								addslashes(encrypt140(decrypt130($entries->login,$key_130),$key_140))."','".
								addslashes(encrypt140(decrypt130($entries->pw,$key_130),$key_140))."','".
								addslashes(encrypt140(decrypt130($entries->comment,$key_130),$key_140))."')",$conn_new);
								
								if ($result == FALSE)	{
									$copy_db_error=1;
								}	
							} //while
						}
            
						if ($copy_db_error == 0) {
							$out__ .= '<p>Install was successful. Please remove the file <b>__install.php</b> from the Document Root and <a href="index.php">restart w3pw</a>.</p>';
						}	else {
							$out__ .= '<p>Install failed. Please try again.';
							
							// try to delete so that the tables remain clean
							mysql_query ("DELETE FROM wallet", $conn_new);
							mysql_query ("DELETE FROM main", $conn_new);
						}
					}	else {
						$out__ .= '<p>Can\'t connect to the database <b>'. DB_NAME .'</b> defined in the configuration file <b>lib/config.php</b>. Please check and try again.</p>';
						$out__ .= '<a href="javascript:history.back();">&lArr; go to previous step</a>';
					}
				}
			}	else {
				// wrong password entered
				$out__ .= '<p>The password you have entered does not match the password stored in the database. Please go to the previous step and try again.</p>';
				$out__ .= '<a href="javascript:history.back();">&lArr; go to previous step</a>';
			}
		} else {
			$dberror=1;
		}
	} else {
		$dberror=1;
	}
	
	if ($dberror == 1) {
		$out__ .= '<p>Can\'t access the database <b>'. $_POST['dbname'] .'</b>. Please go to the previous step and try again.</p>';
		$out__ .= '<a href="javascript:history.back();">&lArr; go to previous step</a>';
	}

  $out__ .= write_footer_end();
  
  //Output the contents
  echo $out__;  
  // STEP 3 -------------------------------------------------------
  
} else {

  // STEP 1 -------------------------------------------------------
  $out__ = write_header_begin("Installation Wizard");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common(); 
  $out__ .= write_header_end();

  $out__ .= <<<OUT
    <h1 class="upgrade">Welcome to the w3pw installation wizard</h1>
    <p class="note important">IMPORTANT: Once you have completed the installation, delete the file, <strong>_install.php</strong>, in the document root of this w3pw installation.</p>
    <p>Welcome to the w3pw installation script.</p>
    <p>Please enter the database name that you would like to use for your w3pw installation, and then press the button labeled <strong>Begin</strong>.</p>
    <form action="_install.php" method="post">
      <div id="upgrade-controls">
        <h2>Database Information:</h2>
        <input type="hidden" name="step2" value="true" />
        <label for="dbhost">Host Name:</label><br />
        <input type="text" name="dbhost" id="dbhost" value="localhost" /> host on which the w3pw database will be created<br />
        <label for="dbname">New Database name:</label><br />
        <input type="text" name="dbname" id="dbname" value="w3pw" /> The new database that you would like to create for use with w3pw<br />
        <label for="dbuname">DB Username:</label><br />
        <input type="text" name="dbuname" id="dbuname" value="" /> The username that has create-rights on your MySQL database<br />
        <label for="dbpw">DB Password:</label><br />
        <input type="text" name="dbpw" id="dbpw" value="" /> The password for the username given in the field above<br />

        <input type="submit" value="Begin" />
      </div>
    </form>
OUT;

  $out__ .= write_footer_end();

  //Output the contents
  echo $out__;
  // STEP 1 -------------------------------------------------------
}

function decrypt130($data, $key) {
	$iv = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB), MCRYPT_RAND);
	return trim(mcrypt_decrypt (MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv));	
}

function encrypt140($data, $key) {

	$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$encrypted_data = mcrypt_generic($td, $data);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
    
	return $encrypted_data;	
}
?>