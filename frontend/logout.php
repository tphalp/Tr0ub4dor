<?php
/* $Id$ */
  session_start();

  require_once("lib/config.php");
  require_once("lib/common_func.php");

  // Test if session is ok
  test_session();
  $out__ = write_header_begin("Logout");
  $out__ .= write_header_end();

  session_unset();
  session_destroy();

  $out__ .= '<b>Logout successful</b>... You will be automatically redirected to the login page again. If not, click <a href="#" onclick="javascript:go_to(\'' . PAGE_LOGIN . '\');">here</a>.';
  $out__ .= write_footer_end();
  
  go_to_url(PAGE_LOGIN);
  
  echo $out__;
?>