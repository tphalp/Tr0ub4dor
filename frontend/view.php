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
    <center><table summary="view entry">
      <tr><th colspan="2">View Wallet entry</th></tr>
      <tr><td class="odd">Entryname: </td><td class="even">${wal_item["name"]}</td></tr>
      <tr><td class="odd">Host/URL: </td><td class="even">
OUT;

  $out__ .= create_web_link($wal_item["host"]);

  $out__ .= "</td></tr>\n";

  $out__ .= <<<OUT
      <tr><td class="odd">Login: </td><td class="even">${wal_item["login"]}</td></tr>
      <tr><td class="odd">Password: </td><td class="even">${wal_item["pw"]}</td></tr>
      <tr><td class="odd">Comment: </td><td class="even">${wal_item["comment"]}</td></tr>
      </table>
      <p><a href="edit.php?id=${wal_item["id"]}">edit</a> | <a href="delete.php?id=${wal_item["id"]}">delete</a></p>
OUT;

  $out__ .= write_footer_main_link();
  $out__ .= "</center>";
  //$out__ .= write_footer_onload('init();');
  $out__ .= write_footer_timeout_init();
  $out__ .= write_footer_common();  

  echo $out__;
?>