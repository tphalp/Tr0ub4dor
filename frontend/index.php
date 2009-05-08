<?php 
	session_start();

  require_once("lib/config.php");
  require_once("lib/common_func.php");
  
  if (test_session(true)) {
    go_to_url("main.php");
    //header("Location:/main.php");
  }
  
  $out__ = write_header_begin("Login");
  $out__ .= write_header_jquery();
  $out__ .= write_header_meta();
  $out__ .= write_header_end();
  $out__ .= write_header_counter();

  //-----------------------------------------------------------------
	// Delete existing tmp files. This can happen if timout is
  // reached between the upload steps.
  //-----------------------------------------------------------------
  delete_stray_temp_files($TMP_PATH);

	// check if mcrypt libraries are installed
	if (MCRYPT_MODE_ECB == "ecb") {
		// check if updatescript is readable
		if (is_readable("update.php")) {
			include("update.php");
		}	else {
      $sys_name = constant("SYS_NAME");
      $out__ .= <<<OUT
      
			<form method="post" action="$FRM_LOGIN">
        <center>
          <table summary="login interface">
            <tr><th colspan="2">$sys_name Login</th></tr>
            <tr><td class="odd">Password: </td><td class="even"><input type="password" name="password" id="password" size="20" /></td></tr>
          </table>
          <input type="submit" value="Login" />
          <p id="popup">Click <a href="starter.php">here</a> to launch $sys_name in a pop-up window.</p>
        </center>
      </form>
OUT;

      //$out__ .= write_footer_popup_check();

		}
	}	else {
    $out__ .= <<<OUT
    
		<b>mcrypt libraries are not installed!</b>
    <br />
		Download libmcrypt-x.x.tar.gz from <a href="http://mcrypt.sourceforge.net/">http://mcrypt.sourceforge.net/</a>, for a linux installation, and follow the included installation instructions.
    <br />
		Windows users will find all the needed compiled mcrypt binaries at <a href="http://ftp.emini.dk/pub/php/win32/mcrypt/">http://ftp.emini.dk/pub/php/win32/mcrypt/</a>.
OUT;
	}

  $out__ .= write_footer_onload('$("#password").focus();checkpop(self.name);');
  $out__ .= write_footer_end();

  echo $out__;
  
?>