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
  $entries = $db->out_row_object("call get_wallet_entry(". $_GET['id'] .");");
  unset($db);
  
  $wal_item = build_item_array($entries);
  
  $out__ .= <<<OUT
  
    <form method="post" action="$FRM_ACTION">
      <center>
      <input type="hidden" name="action" value="editsave" />
      <input type="hidden" name="ID" value="${_GET['id']}" />
      <table class="action-table" summary="edit entry">
        <tr><th colspan="2">Edit Wallet entry</th></tr>
        <tr><td class="odd">Entryname: </td><td class="even"><input type="text" name="itemname" size="40" value="${wal_item["name"]}" /></td></tr>
        <tr><td class="odd">Host/URL: </td><td class="even"><input type="text" name="host" size="40" value="${wal_item["host"]}" /></td></tr>
        <tr><td class="odd">Login: </td><td class="even"><input type="text" name="login" size="40" value="${wal_item["login"]}" /></td></tr>
        <tr><td class="odd">Password: </td><td class="even"><input type="text" name="password" size="40" value="${wal_item["pw"]}" /></td></tr>
        <tr><td class="odd">Comment: </td><td class="even"><textarea name="comment" cols="40" rows="6">${wal_item["comment"]}</textarea></td></tr>
      </table>
      <input type="submit" value="Save" />
      <p><a href="view.php?id=${wal_item["id"]}">view</a> | <a href="delete.php?id=${wal_item["id"]}">delete</a></p>
OUT;

  $out__ .= write_footer_main_link("without saving.");
  $out__ .= '</center></form>';
  $out__ .= write_footer_timeout_init();
  $out__ .= write_footer_common();  

  echo $out__;
?>