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
define("STYLE_DEFAULT", "css/style.css");
define("JS_DEFAULT", "js/script.js");
define("PAGE_MAIN", "main.php");
$FRM_ACTION = "lib/process_action.php";
$FRM_LOGIN = "lib/process_login.php";

// Timeout
define("TIMEOUT", 120);
define("TIMEOUT_SHOW", 30);

//Menu
define("MENU_ITEMS_TEXT", "main list|insert|import|export|change master pw|logout");
define("MENU_ITEMS_URLS", "main.php|insert.php|import.php|export.php|chgpass.php|logout.php");
define("MENU_SHOW_MAIN", "0 1 2 3 5");
define("MENU_SHOW_FOOT", "0 1 2 3 4 5");

// Misc
define("SYS_NAME", "w3pw"); // System name that you would like to use;
define("BASE_DOMAIN", "host.yourdomain.com");  // Used when detecting the referrer;
define("TMP_IMPORT_FILE", "w3pw.csv");  // Temp file names;
define("GROUP_BY", "ALPHA");  // Used for grouping
define("TOP_LINK", "{ top }");
define("HEADER_DEFAULT", '<tr class="header"><td class="first">Entry Name</td><td>Host/URL</td><td class="mt1">&nbsp;</td><td class="mt1">&nbsp;</td><td class="mt2">&nbsp;</td></tr>');
define("HEADER_HIDDEN", '<tr class="invis"><th class="first">&nbsp;</th><th>&nbsp;</th><th class="mt1">&nbsp;</th><th class="mt1">&nbsp;</th><th class="mt2">&nbsp;</th></tr>');
define("CSV_DELIM", ";");

// Path to directory for temporary files. From the POV of the OS.
// Be sure that the webserver process has write access!
define("TMP_PATH", "/tmp/");

// String length for random password
// generated when adding a new entry
// 0 = no generation
define("RANDOM_PW_LENGTH", 0);
?>