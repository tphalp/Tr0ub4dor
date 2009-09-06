<?php 
/* $Id$ */
  session_cache_limiter('nocache');
  session_start();

  require_once("lib/config.php");
  require_once("lib/common_func.php");

  // test if session is ok
  test_session();
  $out__ = write_header_begin("Master Password Change");
  $out__ .= write_header_jquery();
  $out__ .= write_header_common(); 
  $out__ .= write_header_end();
  $out__ .= write_header_counter();

  $sys_name = constant("SYS_NAME");

  $out__ .= <<<OUT
  
    <form method="post" action="$FRM_ACTION">
      <center>
      <input type="hidden" id="action" name="action" value="changepw" />
      <div class="frm-msg"></div>
      <p class="important">Please <a href="export.php">export</a> your wallet entries prior to changing your master password!</p>
      <table class="action-table" summary="change master pw">
      <tr><th colspan="2">$sys_name Master Password Change</th></tr>
      <tr><td class="odd">Old Master Password: </td><td class="even"><input type="password" id="pw" name="pw" size="20" /> <span id="pw-msg" class="frm-msg">*</span></td></tr>
      <tr><td class="odd">New Master Password: </td><td class="even"><input type="password" id="newpw" name="newpw" size="20" /> <span id="newpw-msg" class="frm-msg">*</span></td></tr>
      <tr><td class="odd">Confirm New Master Password: </td><td class="even"><input type="password" id="confirm" name="confirm" size="20" /> <span id="confirm-msg" class="frm-msg">*</span></td></tr>
      </table>
      <input type="submit" id="btn" value="Change Now" />
OUT;

  $out__ .= write_footer_main_link("without changing.");
  $out__ .= "</center></form>";
  $out__ .= write_footer_onload('$("#pw").focus();
    /*$("#btn").click(function(){
      return checkChangePW();
    });*/');
  $out__ .= write_footer_timeout_init();
  $out__ .= write_footer_common();  

  echo $out__;
?>