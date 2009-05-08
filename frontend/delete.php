<?php
  session_cache_limiter('private_no_expire, must-revalidate');
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
  $db = new Data;
  
  // Call the stored proc
  $entries = $db->out_rs_object("call get_wallet_entry(". $_GET['id'] .");", DB_HOST, DB_NAME, DB_USER, DB_PASS);
  unset($db);
  
  $wal_item = build_item_array($entries);
  
  $out__ .= <<<OUT

    <form method="post" action="$FRM_ACTION">
      <input type="hidden" name="action" value="reallydelete" />
      <input type="hidden" name="ID" value="${_GET['id']}" />
      <center>
        <table summary="delete entry">
          <tr><th colspan="2">Delete Wallet entry</th></tr>
          <tr><td class="odd">Entryname: </td><td class="even">${wal_item["name"]}</td></tr>
          <tr><td class="odd">Host/URL: </td><td class="even">${wal_item["host"]}</td></tr>
          <tr><td class="odd">Login: </td><td class="even">${wal_item["login"]}</td></tr>
          <!--<tr><th colspan="2">Really delete this entry?</th></tr>-->
        </table>
        <span id="confirm-del" class="important">Really delete this entry?</span>
        <br />
        <input type="submit" value="Confirm Delete" /> | <a href="#" onclick="javascript:go_to('main.php');">Cancel</a>
OUT;

  $out__ .= write_footer_main_link("without deleting.");
  
  $out__ .= <<<OUT
        <p><a href="view.php?id=${_GET['id']}">view</a> | <a href="edit.php?id=${_GET['id']}">edit</a></p>
      </center>
    </form>
OUT;
  
  //$out__ .= write_footer_onload('init();');
  $out__ .= write_footer_timeout_init();
  $out__ .= write_footer_common();  

  echo $out__;
  
?>