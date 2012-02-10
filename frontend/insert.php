<?php

  session_start();

  require_once("lib/config.php");
  require_once("lib/common_func.php");

  // test if session is ok
  test_session();
  $out__ = write_header_begin("Insert New Wallet Entry");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common();
  $out__ .= write_header_end();
  $out__ .= write_header_counter();

  $initial_pw = htmlspecialchars(create_rand_pw(RANDOM_PW_LENGTH));
  //$initial_pw = "";

  $out__ .= <<<OUT

    <form method="post" action="$FRM_ACTION">
      <center>
      <input type="hidden" name="action" value="save" />
      <table class="action-table" summary="insert table">
        <tr><th colspan="2">Insert new Wallet entry</th></tr>
        <tr><td class="odd">Entryname: </td><td class="even"><input type="text" id="itemname" name="itemname" size="40" /></td></tr>
        <tr><td class="odd">Host/URL: </td><td class="even"><input type="text" id="host" name="host" size="40" /></td></tr>
        <tr><td class="odd">Login: </td><td class="even"><input type="text" id="login" name="login" size="40" /></td></tr>
        <tr><td class="odd">Password: </td><td class="even"><input type="text" id="password" name="password" value="$initial_pw" size="40" /></td></tr>
        <tr><td class="odd">Comment: </td><td class="even"><textarea id="comment" name="comment" cols="40" rows="6"></textarea></td></tr>
      </table>
      <input type="submit" value="Save" />
OUT;

  $out__ .= write_footer_main_link("without inserting a new entry.");

  $out__ .= '</center></form>';

  $out__ .= write_footer_onload('$("#itemname").focus();');
  $out__ .= write_footer_timeout_init();
  $out__ .= write_footer_common();

  echo $out__;

?>