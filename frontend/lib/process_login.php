<?php
/* $Id$ */
  session_start();
  
  require_once("config.php");
  require_once("common_func.php");  
  check_referrer(BASE_DOMAIN);

  // session active?
  if (!isset($_SESSION['logged_in'])){

      // Require POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { go_home(); }

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
          if (isset($_GET["upgrade"])) {
            go_to_url("../_upgrade.php");
          } else {
            go_to_url("../" . PAGE_MAIN);
          }
        }
        else
        {      
          session_unset();
          session_destroy();
          $sysmsg__ = '<b>Invalid credentials</b>....Please <a href="/">try again</a>.';
          show_sys_msg($sysmsg__);
        }
      }
      else
      {
        // can't connect to database
        session_unset();
        session_destroy();	
        $sysmsg__ = '<br />Ooops - <b>Can\'t connect to the database</b>....Please <a href="/">try again</a>.';
        show_sys_msg($sysmsg__);
      }
      mysql_close($conn);
    }
    else
    {
      // can't connect to the server
      session_unset();
      session_destroy();
      $sysmsg__ = '<br />Ooops - <b>Can\'t connect to the database-server</b>...Please <a href="/">try again</a>.';
      show_sys_msg($sysmsg__);
    }
  } else {
    go_to_url("../" . PAGE_MAIN);
  }
?>