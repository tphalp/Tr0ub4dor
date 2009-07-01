<?php 
  session_start();

  require_once("lib/config.php");
  require_once("lib/common_func.php");

  // Test if session is ok
  test_session();
  $out__ = write_header_begin("Import Wallet entries (Step 2)");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common(); 
  $out__ .= write_header_end();
  $out__ .= write_header_counter();
    
  if(isset($_FILES['csvfile']) && !empty($_FILES['csvfile']['tmp_name'])) { 

    if(is_uploaded_file($_FILES['csvfile']['tmp_name'])) {
      // upload successful
      copy($_FILES['csvfile']['tmp_name'], TMP_PATH . "w3pw.csv");
      
      // consistency checks - check number of semicolons in each line
      $linecounter = 0;
      $nr_of_delims = 0;
      $row = 1;
      $error_msg = '';
      
      // fix for Mac files
      ini_set('auto_detect_line_endings', TRUE);
      
      // open the file
      $fd = fopen(TMP_PATH."w3pw.csv", "r");
      
      // check file
      while (($data = fgetcsv($fd, 4096, CSV_DELIM)) !== FALSE) {
          $num = count($data);
          
          if ($num < 5) {
            $error_msg .= '- Row ' . $row . ' has only ' . $num . ' field(s).<br />';
          }
          
          $row++;
      }

      fclose ($fd);
      
      if (strlen($error_msg) > 0) {
        // data inconsistency
        $sysmsg__ = "There were data inconsistencies:<br /><br />" . $error_msg . "<br /><a href=\"javascript:history.back();\">Try again</a>.";
        show_sys_msg($sysmsg__);
        
      } else {
        // uploaded file ok - now make filed assignments
        $field_headers = array("Entry name", "Host/URL", "Login", "Password", "Comment");
        
        $out__ .= <<<OUT
        
          <form method="post" action="$FRM_ACTION">
            <center>
            <input type="hidden" name="action" value="import" />
            <table class="action-table" summary="import table">
              <tr><th colspan="5">Make field assignments</th></tr>
              <tr class="odd">
OUT;
        
        for ($x=0; $x <= 4; $x++) {
          $out__ .= "<td><select name=\"row[". $x ."]\">\n";
          reset ($field_headers);
          
          while (list ($field_number, $field_name) = each ($field_headers)) {
            $out__ .= "<option value=\"". $field_number ."\"";
            
            if ($field_number == $x) {
              $out__ .= " selected=\"selected\"";
            }
            
            $out__ .= ">". $field_name ."</option>\n";
          }
          $out__ .= "</select></td>\n";
        }
        
        $out__ .= "</tr>\n";
        
        // show first two lines of uploaded data
        $linecounter=0;
      
        $fd = fopen (TMP_PATH . "w3pw.csv", "r");

        while ($data = fgetcsv ($fd, 4096, ";")) {
          $linecounter++;

          if ($linecounter <= 4) {  //only the first 4 lines
            $out__ .= "<tr class=\"even\">";
            
            for ($x=0; $x <= 4; $x++) {
              $out__ .= "<td>". $data[$x] ."</td>\n";
            }
            
            $out__ .= "</tr>\n";
          }
        }
        
        fclose ($fd);
        unset($data, $fd, $field_headers);
        $out__ .= "</table>\n";
        $out__ .= "<input type=\"submit\" value=\"Save\" /><br /><br />\n";
        
        $out__ .= '<span class="note">Note: Only the first four lines of your file are shown here!</span><br />';
      }
      
      //$out__ .= "Go back to <a href=\"main.php\">Main Menu</a> without importing the contents of the file.</center>\n</form>\n";
      $out__ .= write_footer_main_link("without importing the contents of the file.");
      
    } else {
      // error while uploading
      $sysmsg__ = "Error while uploading file.<br /><a href=\"javascript:history.back();\">Try again</a>.";
      show_sys_msg($sysmsg__);      
    }
  } else {
      // error while uploading
      $sysmsg__ = "No file uploaded.<br /><a href=\"import.php\">Try again</a>.";
      show_sys_msg($sysmsg__);          
  }
  
  $out__ .= '</center></form>';
  $out__ .= write_footer_timeout_init();
  $out__ .= write_footer_common();    
  
  echo $out__;

?>