<?php
/* $Id$ */
  session_start(c );

  require_once("lib/config.php");
  require_once("lib/common_func.php");

  // Test if session is ok
  test_session();
  $out__ = write_header_begin("Logout");
  $out__ .= write_header_end();

  session_unset();
  session_destroy();

  $out__ .= '<b>Logout successful</b>... You will be automatically redirected to the login page again. If not, click <a href="#" onclick="javascript:go_home();">here</a>.';
  $out__ .= write_footer_end();
  
  go_home();
  
  echo $out__;
?>