<?php
session_cache_limiter('nocache');
session_start();

require_once("lib/config.php");
require_once("lib/common_func.php");

if (!test_session(TRUE)) {
  show_sys_msg('You must login to run the upgrade script. Please <a href="index.php?upgrade">login</a>.');
}

if ((isset($_POST['step2'])) && ($_POST['step2'] == "true")) {
  // STEP 2 -------------------------------------------------------

	// second step of the installation, ask 
	// for the password of the previous installation
  $out__ = write_header_begin("Upgrade Step 2 of 3");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common(); 
  $out__ .= write_header_end();
	$out__ .= '<h1 class="upgrade">Upgrade Step 2 of 3</h1>';
	
	// first, check if previous DB Name is not the same as
	// the name for the new installation
	if (DB_NAME == $_POST['dbname']) {
		$out__ .= <<<OUT
      <p>You've entered the database name of the current, new installation. This upgrade routine does not support upgrade into the same database.</p>
      <p>Please go back and either choose a different database name for your previous installation (you also have to rename your existing database) or choose a different database name for your new installation in the file <b>lib/config.php</b>.</p>
      <p>Please refere to the INSTALL file in the root of your w3pw installation for preliminary steps required prior to coming this upgrade script.</p>
      <a href="javascript:history.back();">&lArr; go to previous step</a>
OUT;

	} else {
		$dberror=0;
		// check if the database name given exists
		if ($conn = mysql_connect(DB_HOST, DB_USER, DB_PASS))	{
			if (mysql_select_db($_POST['dbname'], $conn))	{
				// ask for the password
				$out__ .= <<<OUT

          <p>Please enter the password from your previous wp3w installation. This is needed, because the decryption and encryption routines have changed and this upgrade process needs to re-encrypt all entries. The password itself will remain unchanged.</p>
          <form action="_upgrade.php" method="post">
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

  $out__ = write_header_begin("Upgrade Step 3 of 3");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common(); 
  $out__ .= write_header_end();	
	$out__ .= '<h1 class="upgrade">Upgrade Step 3 of 3</h1>';

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
							$list = mysql_query ("call get_all_from_wallet();", $conn);
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
							$out__ .= '<p>Upgrade was successful. Please remove the file <b>_upgrade.php</b> from the Document Root and <a href="index.php">restart w3pw</a>.</p>';
						}	else {
							$out__ .= '<p>Upgrade failed. Please try again.';
							
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
  $out__ = write_header_begin("Upgrade Step 1 of 3");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common(); 
  $out__ .= write_header_end();

  $out__ .= <<<OUT
    <h1 class="upgrade">Upgrade Step 1 of 3</h1>
    <p>If you don't want to upgrade from a previous version of w3pw, please delete the file <strong>_upgrade.php</strong> in the document root of this w3pw installation, and then click <a href="index.php">here</a>.</p>
    <p>Otherwise, please enter the database name of the <strong>previous</strong> w3pw installation, and then press the button labeled <strong>Begin</strong>.</p>
    <form action="_upgrade.php" method="post">
      <div id="upgrade-controls">
        <input type="hidden" name="step2" value="true" />
        <label for="dbname">Database name:</label><br />
        <input type="text" name="dbname" id="dbname" value="" /><br />
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