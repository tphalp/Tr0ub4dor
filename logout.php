<?php session_start(); ?>
<html>
<head>
<title>logout</title>
<?php include("include/css.php"); ?>
<meta http-equiv="refresh" content="0; URL=index.php">
</head>
<body>
	<?php
	session_unset();
	session_destroy();
	?>
	
	<b>Logout successfull</b>... You will be automatically redirected to the logon page again. If not, try this <a href="index.php">link</a>.
	
</body>
</html>         