<?php
$CURR_SCRIPT = "config.php";
require("no_direct.php"); 

// don't report Database-Errors on Frontend
error_reporting(E_ERROR);

//Don't cache
//TODO: REVISIT THIS NO-CACHE METHOD
header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Database
define("DB_HOST", "localhost");
define("DB_NAME", "w3pw");
define("DB_USER", "xxxx");
define("DB_PASS", "xxxx");
define("DB_PORT", 3306);

// Files
define("DEFAULT_STYLE", "css/style.css");
define("DEFAULT_JS", "js/script.js");
$FRM_ACTION = "lib/process_action.php";
$FRM_LOGIN = "lib/process_login.php";

// Timeout
define("TIMEOUT", 120);
define("TIMEOUT_SHOW", 30);

//Menu
define("MENU_ITEMS_TEXT", "main list|insert|import|change master pw|logout");
define("MENU_ITEMS_URLS", "main.php|insert.php|import.php|chgpass.php|logout.php");
define("MENU_SHOW_MAIN", "0 1 2 4");
define("MENU_SHOW_FOOT", "0 1 2 3 4");

// Misc
define("SYS_NAME", "w3pw"); // System name that you would like to use;
define("BASE_DOMAIN", "host.yourdomain.com");  // Used when detecting the referrer;
define("TMP_IMPORT_FILE", "w3pw.csv");  // Temp file names;
define("GROUP_BY", "ALPHA");  // Used for grouping
define("TOP_LINK", "{ top }");
define("HEADER_HIDDEN", '<tr class="invis"><th class="first">&nbsp;</th><th>&nbsp;/URL</th><th class="mt1">&nbsp;</th><th class="mt1">&nbsp;</th><th class="mt2">&nbsp;</th></tr>');
define("HEADER_DEFAULT", '<tr class="header"><td>Entry Name</td><td>Host/URL</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>');

// Path to directory for temporary files. From the POV of the OS.
// Be sure that the webserver process has write access!
define("TMP_PATH", "/tmp/");

// String length for random password
// generated when adding a new entry
$RANDOM_PW_LENGTH = 0;
?>