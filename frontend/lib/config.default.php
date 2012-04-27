<?php

$CURR_SCRIPT = "config.php";
require("no_direct.php");

// Don't report Database-Errors on Frontend
error_reporting(E_ERROR);

// Don't cache
// TODO: REVISIT THIS NO-CACHE METHOD
header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

//!!!SYSTEM WILL NOT FUNCTION PROPERLY WITHOUT THE FOLLOWING SETUP CORRECTLY!!!
////// BEGIN USER CONFIG - USER MUST CONFIGURE THE FOLLOWING ---
// DATABASE ---
define("DB_HOST", "localhost");
define("DB_NAME", "w3pw");
define("DB_USER", "xxxx");
define("DB_PASS", "xxxx");
define("DB_PORT", 3306);

// Used when detecting the referrer, for added security. Must match the domain used to host this instance of w3pw.
// Do not include sub-directory, if used. e.g. - host.yourdomain.com/w3pw
define("BASE_DOMAIN", "host.yourdomain.com");
////// END USER CONFIG ---

// FILES ---
define("STYLE_DEFAULT", "css/style.css");
define("JS_DEFAULT", "js/script.js");
define("PAGE_MAIN", "main.php");
define("PAGE_LOGIN", "index.php");
$FRM_ACTION = "lib/process_action.php";
$FRM_LOGIN = "lib/process_login.php";

// TIMEOUT ---
// How long before timeout, in seconds.
define("TIMEOUT", 120);
// How long prior to timeout that notice will begin showing.
define("TIMEOUT_SHOW", 30);

//MENU ---
// The text that shows on both menus (header and footer).
define("MENU_ITEMS_TEXT", "main list|insert|import|export|change master pw|logout");
// The links that correspond to the text above.
define("MENU_ITEMS_URLS", "main.php|insert.php|import.php|export.php|chgpass.php|logout.php");
// The following two constants represent the menu items above. If the index is listed, then it will show up in the respective menu.
// Header menu inclusion list
define("MENU_SHOW_MAIN", "0 1 2 3 5");
// Footer menu inclusion list
define("MENU_SHOW_FOOT", "0 1 2 3 4 5");

// MISC ---
// System name that you would like to use. Will show anywhere "w3pw" normally is displayed on the web interface.
define("SYS_NAME", "w3pw");
// Temp file names
define("TMP_IMPORT_FILE", "w3pw.csv");
// Used for grouping. ALPHA give you grouping, and an empty string gives you the regular list, with no grouping.
// ALPHA is currently the only option.
define("GROUP_BY", "ALPHA");
// This is the link that is used with grouping to take you to the top of the page. Change this to whatever you want.
define("TOP_LINK", "{ top }");
// Headers used in the main list when grouping. Not the best solution, but is okay for now.
// Once other grouping options are available (categories, etc), this will be modified.
define("HEADER_DEFAULT", '<tr class="header"><td class="first">Entry Name</td><td>Host/URL</td><td class="mt1">&nbsp;</td><td class="mt1">&nbsp;</td><td class="mt2">&nbsp;</td></tr>');
define("HEADER_HIDDEN", '<tr class="invis"><th class="first">&nbsp;</th><th>&nbsp;</th><th class="mt1">&nbsp;</th><th class="mt1">&nbsp;</th><th class="mt2">&nbsp;</th></tr>');
// Delimiter used for export
// Changing this is NOT advised, as using another delimiter may cause problems
define("CSV_DELIM", ";");
// Show/Hide the message on the login screen about popping w3pw into a separate,
// smaller window.
// 1 = show, 0 = do not show
define("SHOW_POP", 1);
// Show/Hide the username and password on the View page (1 = true, 0 = false).
define("USE_MASK", 1);
// Set this to 1 to use the google-hosted jquery code, or 0 to use the provided jquery code.
// See http://code.google.com/apis/libraries/devguide.html#jquery for more info
define("USE_GOOG_JQUERY", 0);
// GOOG_JQUERY_VER should ONLY BE USED IF USE_GOOG_JQUERY (above) is equal to 1.
// If using the google-hosted jquery, tells the system which version to use? (Make sure it is hosted by the google api before using.)
// See http://code.google.com/apis/libraries/devguide.html#jquery for more info
define("GOOG_JQUERY_VER", "1.5.1");
// SSL port you are using, if applicable. Will be ignored if you are not using SSL.
define("SSL_PORT", 443);

// Path to directory for temporary files. From the POV of the OS.
// Be sure that the webserver process has write access!
define("TMP_PATH", "/tmp/");

// Location of log optional file. Leave blank to forgo writing to a log file.
// If specified, this must be from the POV of the OS.
// Be sure that the webserver process has write access!
define("LOG_FILE", "");

// String length for random password generated when adding a new entry
// 0 = no generation
define("RANDOM_PW_LENGTH", 0);

// Default SQL Queries
define("SQL_WALLET_GET", "select * from wallet;");
define("SQL_WALLET_GET_ID", "select * from wallet where id=");
define("SQL_CAT_GET", "select * from categories;");
?>