<?php 
  session_start();

  require_once("lib/config.php");
  require_once("lib/common_func.php");

  // Test if session is ok
  test_session();
  $out__ = write_header_begin("Import Wallet Entries (Step 1)");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common(); 
  $out__ .= write_header_end();
  $out__ .= write_header_counter();
    
  $out__ .= <<<OUT
  
    <form enctype="multipart/form-data" action="import2.php" method="post">
      <center>
      <p class="note">Note: The import engine requires a semi-colon (;) delimited text file.</p>
      <table class="action-table" summary="import table">
        <tr><th colspan="2">Upload CSV File with Wallet entries</th></tr>
        <tr><td class="odd">Filename: </td><td class="even"><input type="file" name="csvfile" size="40" /></td></tr>
      </table>
      <input type="submit" value="Begin" />
OUT;

  $out__ .= write_footer_main_link("without importing.");
        
  $out__ .= '</center></form>';
  
  $out__ .= write_footer_timeout_init();
  $out__ .= write_footer_common();  

  echo $out__;

?>
