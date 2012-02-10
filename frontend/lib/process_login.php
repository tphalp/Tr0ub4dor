<?php
/* $Id$ */
session_start();

require_once("config.php");
require_once("common_func.php");  
check_referrer(BASE_DOMAIN);

// Session is active
if (test_session(TRUE)) {
  go_to_url(PAGE_MAIN, 1);
}

// Require POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { go_to_url(PAGE_LOGIN, 1); }

// Set db object
$db = get_db_conn();
$cleartext_pw = "";

// encrypt the pw given at logon
if (isset($_POST['password'])) {
  $cleartext_pw = $_POST['password'];
}

$crypt_pw = sha1($cleartext_pw);

// check pw
$entries = $db->out_row_object("SELECT version, pw FROM main");
$db_pw = $entries->pw;

if ($crypt_pw == $db_pw) {
  // passwords match - proceed
  $_SESSION['logged_in'] = 1;
  $_SESSION['key'] = md5("%dJ9&" . strtolower($cleartext_pw) . "(/&k.=" . strtoupper($cleartext_pw) . "1x&%");
  $_SESSION['version'] = $entries->version;

  // delete cleartext pw in memory
  unset($cleartext_pw);
  unset($entries, $db);
  
  //Forward to main page
  if (isset($_GET["upgrade"])) {
    go_to_url('~/upgrade.php', 1);
  } else {
    go_to_url(PAGE_MAIN, 1);
  }

} else {
  unset($db);
  session_unset();
  session_destroy();
  
  // Pass upgrade bit, if applicable
  $qs = '';
  if (isset($_GET["upgrade"])) {
    $qs = '?upgrade';
  }
  
  $sysmsg__ = '<b>Invalid credentials</b>....Please <a href="' . PAGE_LOGIN . $qs . '">try again</a>.';
  show_sys_msg($sysmsg__);
}
?>