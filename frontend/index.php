<?php 
/* $Id$ */
  session_start();

  require_once("lib/config.php");
  require_once("lib/common_func.php");
  
  if (test_session(TRUE)) {
    go_to_url(PAGE_MAIN);
  }
  
  // -----------------------------------------------------------
  // Simple querystring section. Used for handling some 
  // querystrings that are used on the login page.
  // -----------------------------------------------------------
  // $do_upgrade is used by the install/upgrade pages
  $do_upgrade = isset($_GET["upgrade"]) ? '?upgrade' : '';  
  // -----------------------------------------------------------
  
  $out__ = write_header_begin("Login");
  $out__ .= write_header_jquery();
  $out__ .= write_header_meta();
  $out__ .= write_header_end();
  $out__ .= write_header_counter();

  //-----------------------------------------------------------------
  // Delete existing tmp files. This can happen if timout is
  // reached between the upload steps.
  //-----------------------------------------------------------------
  delete_stray_temp_files(TMP_PATH);

  // check if mcrypt libraries are installed
  if (MCRYPT_MODE_ECB == "ecb") {
    // check if updatescript is readable
    $sys_name = SYS_NAME;
    $out__ .= <<<OUT
    
    <form method="post" action="$FRM_LOGIN$do_upgrade">
      <center>
        <div><img src="images/logo-linear.png" alt="w3pw logo" /></div>
        <table class="action-table" summary="login interface">
          <!--<tr><th colspan="2">$sys_name Login</th></tr>-->
          <tr><td class="odd">Password: </td><td class="even"><input type="password" name="password" id="password" size="20" /></td></tr>
        </table>
        <input type="submit" value="Login" />
        <p id="popup">Click <a href="starter.php">here</a> to launch $sys_name in a pop-up window.</p>
      </center>
    </form>
OUT;
  }	else {
    $out__ .= <<<OUT
    
    <b>MCrypt libraries are not installed!</b>
    <br />
    Download mcrypt from <a href="http://mcrypt.sourceforge.net/">http://mcrypt.sourceforge.net/</a>, and follow the included installation instructions.
    <br />
    Both Linux and Windows binaries are usually available at the above link.
OUT;
  }

  $out__ .= write_footer_onload('$("#password").focus();checkpop(self.name, ' . SHOW_POP . ');');
  $out__ .= write_footer_end();

  echo $out__;
  
?>