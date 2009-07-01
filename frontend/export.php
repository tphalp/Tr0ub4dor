<?php 
  session_start();

  require_once("lib/config.php");
  require_once("lib/common_func.php");

  // Test if session is ok
  test_session();
  $out__ = write_header_begin("Export Wallet Entries");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common(); 
  $out__ .= write_header_end();
  $out__ .= write_header_counter();
    
  $out__ .= <<<OUT
  
    <form enctype="multipart/form-data" action="$FRM_ACTION" method="post">
      <center>
      <input type="hidden" name="action" value="export" />
      <p>This allows you to save your current wallet entries to a semi-colon (;) delimited text file.</p>
      <p class="important">Are you sure you want to export all wallet entries?</p>
      <br />
      <input type="submit" value="Export" />
OUT;

  $out__ .= write_footer_main_link("without exporting.");
        
  $out__ .= '</center></form>';
  
  $out__ .= write_footer_timeout_init();
  $out__ .= write_footer_common();  

  echo $out__;

?>