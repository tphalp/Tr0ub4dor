<?php
/* $Id$ */
  session_cache_limiter('nocache');
  session_start();

  require_once("lib/config.php");
  require_once("lib/common_func.php");

  // test if session is ok
  test_session();
  $out__ = write_header_begin("View Wallet Entry");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common(); 
  $out__ .= write_header_end();
  $out__ .= write_header_counter();  
  $db = get_db_conn();
  
  // Call the stored proc
  $entries = $db->out_row_object("SELECT * FROM wallet WHERE ID=" . $_GET['id'] . ";");
  unset($db);

  $wal_item = build_item_array($entries, TRUE);
       
  $out__ .= <<<OUT
    <center><table class="action-table" summary="view entry">
      <tr><th colspan="2">View Wallet entry</th></tr>
      <tr><td class="odd">Entryname: </td><td class="even">${wal_item["name"]}</td></tr>
      <tr><td class="odd">Host/URL: </td><td class="even">
OUT;

  $out__ .= create_web_link($wal_item["host"]);
  $out__ .= "</td></tr>\n";
  
  // Check for USE_MASK
  if (USE_MASK == 1) {
    $wal_item["login"] = mask_data($wal_item["login"]);
    $wal_item["pw"] = mask_data($wal_item["pw"]);
  }
  
  $out__ .= <<<OUT
      <tr><td class="odd">Login: </td><td class="even">${wal_item["login"]}</td></tr>
      <tr><td class="odd">Password: </td><td class="even">${wal_item["pw"]}</td></tr>
      <tr><td class="odd">Comment: </td><td class="even">${wal_item["comment"]}</td></tr>
      </table>
      <p><a href="edit.php?id=${wal_item["id"]}">edit</a> | <a href="delete.php?id=${wal_item["id"]}">delete</a></p>
OUT;

  $out__ .= write_footer_main_link();
  $out__ .= "</center>";
  $out__ .= write_footer_timeout_init();
  $out__ .= write_footer_common();  

  echo $out__;
?>