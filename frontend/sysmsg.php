<?php
	session_start();

  require_once("lib/config.php");
  require_once("lib/common_func.php");

  if (strlen($_GET['q']) > 0) {
    $sysmsg__ = base64_decode(urldecode($_GET['q']), $SYSMSG_KEY);
  } else {
    go_home();
  }

  $out__ = write_header_begin("System Message");
  $out__ .= write_header_jquery();
  if (test_session(true)) {
    $out__ .= write_header_timeout();
  }
  $out__ .= write_header_meta();
  $out__ .= write_header_end("sysmsg");
  $out__ .= write_header_counter();
  
  $out__ .= <<<OUT
  
    <h2>System Message</h2>
    <p>$sysmsg__</p>
OUT;
  
  if (test_session(true)) {
    $out__ .= write_footer_main_link();
    $out__ .= write_footer_onload('init();');
  }
  
  $out__ .= write_footer_end();

  echo $out__;

?>