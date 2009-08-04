<?php
if (eregi('testsession.php', $_SERVER['PHP_SELF']))
{
    die ("You can't access this file directly...");
}

	function test_session()
	{
		if ((isset($_SESSION['logged_in'])) && ($_SESSION['logged_in'] == 1))
		{
			// session is ok
			return(1);
		}
		else
		{
			// invalid/expired session
			echo "<html>\n<head>\n<meta http-equiv=\"refresh\" content=\"0; URL=logout.php\">\n</head>\n<body></body>\n</html>";
			return(0);
		}
	}
?>
