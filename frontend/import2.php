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
      copy($_FILES['csvfile']['tmp_name'], $TMP_PATH."w3pw.csv");
      
      // consistency checks - check number of semicolons in each line
      $linecounter = 0;
      $nr_of_delims = 0;
      $errorlines = "";
      
      $fd = fopen($TMP_PATH."w3pw.csv", "r");
      
      while (!feof($fd)) {
        $buffer = fgets($fd, 4096);
        $linecounter++;
        
        if (strlen($buffer)>1)
        {
          // use number of semicolons in the first line as reference
          if ($linecounter == 1)
          {
            $nr_of_delims = substr_count($buffer, ";");
          }
          else
          {
            $nr_of_delims_thisline = substr_count($buffer, ";");

            if (($nr_of_delims_thisline != $nr_of_delims) || ($nr_of_delims_thisline > 5))
            {
              $errorlines .= $linecounter . ",";
            }
          }
        }
      }
      
      fclose ($fd);
      
      if ($errorlines) {
        // data inconsistency
        $sysmsg__ = "Data Inconsistency: Not enough/Too many delimiters in following lines: ". rtrim($errorlines, ",") .".<br /><a href=\"javascript:history.back();\">Try again</a>.";
        show_sys_msg($sysmsg__, $SYSMSG_KEY);
        
      } else {
        // uploaded file ok - now make filed assignments
        $field_headers = array("Entry name", "Host/URL", "Login", "Password", "Comment");
        
        $out__ .= <<<OUT
        
          <form method="post" action="$FRM_ACTION">
            <input type="hidden" name="action" value="import" />
            <center>
              <table summary="import table">
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
      
        $fd = fopen ($TMP_PATH."w3pw.csv", "r");

        while ($data = fgetcsv ($fd, 4096, ";")) {
          $linecounter++;

          if ($linecounter <= 2) {
            $out__ .= "<tr class=\"even\">";
            
            for ($x=0; $x <= 4; $x++) {
              $out__ .= "<td>". $data[$x] ."</td>\n";
            }
            
            $out__ .= "</tr>\n";
          }
        }
        
        fclose ($fd);
        
        $out__ .= "</table>\n";
        $out__ .= "<input type=\"submit\" value=\"Save\" /><br /><br />\n";
        
        $out__ .= "<b>Only the first two lines of your file are shown here!</b><br />\n";
      }
      
      //$out__ .= "Go back to <a href=\"main.php\">Main Menu</a> without importing the contents of the file.</center>\n</form>\n";
      $out__ .= write_footer_main_link("without importing the contents of the file");
      
    } else {
      // error while uploading
      $sysmsg__ = "Error while uploading file.<br /><a href=\"javascript:history.back();\">Try again</a>.";
      show_sys_msg($sysmsg__, $SYSMSG_KEY);      
    }
  } else {
      // error while uploading
      $sysmsg__ = "No file uploaded.<br /><a href=\"import.php\">Try again</a>.";
      show_sys_msg($sysmsg__, $SYSMSG_KEY);          
  }
  
  $out__ .= "</center></form>";
  //$out__ .= write_footer_onload('init();');
  $out__ .= write_footer_timeout_init();
  $out__ .= write_footer_common();    
  
  echo $out__;

?>