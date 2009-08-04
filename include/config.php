<?php
if (eregi('config.php', $_SERVER['PHP_SELF']))
{
    die ("You can't access this file directly...");
}

// Database Access
$dbuser="root";
$database="w3pw";
$hostname="localhost";
$dbpasswd="";
$port=3306;

// timout counter (sec)
$session_timeout=120;

// path to directory for temporary files
// be sure that the webserver process has write access!
$tmppath = "/tmp/";

// String length for random password
// generated when adding a new entry
$random_pw_length = 12;

// don't report Database-Errors on Frontend
error_reporting(E_ERROR);
?>
