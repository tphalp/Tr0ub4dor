<?php
/* $Id$ */
  session_start();

  require_once("lib/config.php");
  require_once("lib/common_func.php");

  if (isset($_GET['q']) && strlen($_GET['q']) > 0) {
    $sysmsg__ = decode_msg($_GET['q']);
  } else {
    go_to_url(PAGE_LOGIN);
  }

  $out__ = write_header_begin("System Message");
  
  if (test_session(TRUE)) {
    $out__ .= write_header_jquery();
    $out__ .= write_header_common();
  }
  
  $out__ .= write_header_meta();
  $out__ .= write_header_end("sysmsg");
  $out__ .= write_header_counter();
  
  $out__ .= <<<OUT
  
    <h2>System Message</h2>
    <p>$sysmsg__</p>
OUT;
  
  if (test_session(TRUE)) {
    $out__ .= write_footer_main_link();
    $out__ .= write_footer_timeout_init();
  }
  
  $out__ .= write_footer_end();

  echo $out__;

?>