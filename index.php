<html>
<head>
<title>w3pw Login</title>
<?php
$incpath = "./include/";
include ($incpath . "css.php");
include ($incpath . "config.php");
// delete existing tmp files
// this can happen if timout is reached between the upload steps
if (is_file($tmppath . "w3pw.csv")) {
    unlink($tmppath . "w3pw.csv");
}
?>
</head>
<body>
<?php
// check if mcrypt libraries are installed
if (MCRYPT_MODE_ECB == "ecb") {
    // check if updatescript is readable
    if (is_readable("update.php")) {
        include ("update.php");
    } else {
?>
	<form method="post" action="main.php">
	<center><table>
	<tr><th colspan="2">w3pw Login</th></tr>
	<tr><td class="odd">Password: </td><td class="even"><input type="password" name="password" size="20"></td></tr>
	</table>
	<input type="submit" value="Login">
	</form>
	</center>
<?php
    }
} else {
?>
	<b>mcrypt libraries are not installed!</b><br />
	Download libmcrypt-x.x.tar.gz from <a href="http://mcrypt.sourceforge.net/">http://mcrypt.sourceforge.net/</a> 
	for a linux installation and follow the included installation instructions.<br />
	Windows users will find all the needed compiled mcrypt binaries at 
	<a href="http://ftp.emini.dk/pub/php/win32/mcrypt/">http://ftp.emini.dk/pub/php/win32/mcrypt/</a>.
<?php
}
?>
</body>
</html>
