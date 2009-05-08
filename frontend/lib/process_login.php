<?php
  session_start();

  require_once("check_refer.php");
  require_once("config.php");
  require_once("common_func.php");  

  // session active?
	if (!isset($_SESSION['logged_in'])){

    // no session active - check pw
		if ($conn = mysql_connect(DB_HOST, DB_USER, DB_PASS))	{

			if (mysql_select_db(DB_NAME, $conn)) {
				$cleartext_pw = "";

				// encrypt the pw given at logon
				if (isset($_POST['password'])) {
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

          //Forward to main page
          go_to_url("../main.php");
          //header("Location:/main.php");
				}
				else
				{      
					session_unset();
					session_destroy();
					$sysmsg__ = '<b>Invalid credentials</b>....Please <a href="/">try again</a>';
          show_sys_msg($sysmsg__, $SYSMSG_KEY);
				}
			}
			else
			{
				// cant connect to database
				session_unset();
				session_destroy();	
				$sysmsg__ = '<br />Ooops - <b>Can\'t connect to the database</b>....Please <a href="/">try again</a>';
        show_sys_msg($sysmsg__, $sysmsg_key);
			}
			mysql_close($conn);
		}
		else
		{
			// cant connect to the server
			session_unset();
			session_destroy();
			$sysmsg__ = '<br />Ooops - <b>Can\'t connect to the database-server</b>...Please try <a href="/">again</a>';
      show_sys_msg($sysmsg__, $SYSMSG_KEY);
		}
	} else {
    go_to_url("../main.php");
    //header("Location:/main.php");
  }
?>