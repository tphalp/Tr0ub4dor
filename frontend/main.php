<?php
  session_cache_limiter('nocache');
  session_start();

  require_once("lib/config.php");
  require_once("lib/common_func.php");
  
  // test if session is ok
  test_session();
  $out__ = write_header_begin("Main List");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common(); 
  $out__ .= write_header_end();
  $out__ .= write_header_counter();
  
  //-----------------------------------------------------------------
  // Delete existing tmp files. This can happen if timout is
  // reached between the upload steps.
  //-----------------------------------------------------------------
  delete_stray_temp_files(TMP_PATH);
  
  //Output the menu
  $out__ .= write_header_menu();

  $db = get_db_conn();

  // Call the stored proc
  $list = $db->out_result_object("call get_all_from_wallet();");
  $header_array = array();
  
  // Loop through to create the array of Entry Names
  while ($entries = $list->fetch_object()) {
    $header_array[$entries->ID] = de_crypt($entries->itemname, $_SESSION['key']);
  }
  
  unset($list);
  natcasesort($header_array);
  reset($header_array);

  if (count($header_array) == 0) {
    $out__ .= '<p>No passwords found. Click <a href="insert.php">here</a> to enter one.</p>';
  }

  $counter = 0;
  while( list($ID, $itemname) = each($header_array)) {
    $counter++;
    $list = $db->out_result_object('call get_wallet_entry(' . $ID . ');');
    $entries = $list->fetch_object();

    // write out the table header during the first loop
    if ($counter == 1) {
      $out__ .= <<<OUT
        <center>
          <table id="main-list" summary="view table">
            <tr><th class="first">Entry Name</th><th>Host/URL</th><th class="mt1">&nbsp;</th><th class="mt1">&nbsp;</th><th class="mt2">&nbsp;</th></tr>
OUT;
    }

    // do odd/even depending on modulus
    if ($counter % 2 == 0) {
      $out__ .= '<tr class="even">';
    } else {
      $out__ .= '<tr class="odd">';
    }

    // set varaibles with additional record info
    $host = create_web_link(de_crypt($entries->host, $_SESSION['key']));
    $login = de_crypt($entries->login, $_SESSION['key']);
    $pw = de_crypt($entries->pw, $_SESSION['key']);
    $comment = de_crypt($entries->comment, $_SESSION['key']);
    
    // output the table cells with the record info created above
    $out__ .= <<<OUT
    
              <td>$itemname</td><td>$host</td><td class="link"><a href="view.php?id=$ID">view</a></td><td class="link"><a href="edit.php?id=$ID">edit</a></td><td class="link"><a href="delete.php?id=$ID">delete</a></td></tr>
OUT;

  } //while loop
  
  // the table closing tag
  if ($counter >= 1) {
    $out__ .= "</table></center>\n";
  }
  
  unset($header_array, $itemname);
  unset($db);

  $out__ .= write_footer_timeout_init();
  $out__ .= write_footer_common();

  //Output the contents
  echo $out__;
?>