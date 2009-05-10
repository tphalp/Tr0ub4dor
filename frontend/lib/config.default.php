<?php
$CURR_SCRIPT = "config.php";
require("no_direct.php"); 

// don't report Database-Errors on Frontend
error_reporting(E_ERROR); //E_ALL

//Don't cache
//TODO: REVISIT THIS NO-CACHE METHOD
header("Cache-Control: no-cache, must-revalidate");
header("Expires: -1");

// Database Access
define("DB_HOST", "localhost");
define("DB_NAME", "w3pw");
define("DB_USER", "xxxx");
define("DB_PASS", "xxxx");
define("DB_PORT", 3306);

// Misc Constants
define("SYS_NAME", "w3pw");
define("DEFAULT_STYLE", "css/style.css");
define("DEFAULT_JS", "js/script.js");
define("TMP_IMPORT_FILE", "w3pw.csv");
define("TIMEOUT", 120);
define("TIMEOUT_SHOW", 30);

// Misc Variables
$FRM_ACTION = "lib/process_action.php";
$FRM_LOGIN = "lib/process_login.php";
$SYSMSG_KEY = md5("%dJ9&".strtolower('test')."(/&k.=".strtoupper('test')."1x&%");

// Path to directory for temporary files. From the POV of the OS.
// Be sure that the webserver process has write access!
$TMP_PATH = "/tmp/";

// String length for random password
// generated when adding a new entry
$RANDOM_PW_LENGTH = 0;
?>