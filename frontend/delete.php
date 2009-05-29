<?php
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
      <input type="hidden" name="action" value="reallydelete" />
      <input type="hidden" name="ID" value="${_GET['id']}" />
      <center>
        <table class="action-table" summary="delete entry">
          <tr><th colspan="2">Delete Wallet entry</th></tr>
          <tr><td class="odd">Entryname: </td><td class="even">${wal_item["name"]}</td></tr>
          <tr><td class="odd">Host/URL: </td><td class="even">${wal_item["host"]}</td></tr>
          <tr><td class="odd">Login: </td><td class="even">${wal_item["login"]}</td></tr>
          <!--<tr><th colspan="2">Really delete this entry?</th></tr>-->
        </table>
        <span id="confirm-del" class="important">Really delete this entry?</span>
        <br />
        <input type="submit" value="Confirm Delete" /> | <a href="#" onclick="javascript:go_to('main.php');">Cancel</a>
        <p><a href="view.php?id=${_GET['id']}">view</a> | <a href="edit.php?id=${_GET['id']}">edit</a></p>
OUT;

  $out__ .= write_footer_main_link("without deleting.");
  
  $out__ .= <<<OUT
      </center>
    </form>
OUT;
  
  $out__ .= write_footer_timeout_init();
  $out__ .= write_footer_common();  

  echo $out__;
  
?>