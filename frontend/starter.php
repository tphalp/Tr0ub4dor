<?php

  require_once("lib/config.php");
  require_once("lib/common_func.php");

  $out__ = write_header_begin("Starter");
  $out__ .= write_header_meta();
  $out__ .= write_header_end();

  $sys_name = constant("SYS_NAME");

  $out__ .= <<<OUT

    <p>If the $sys_name Login page does not open in a new window immediately, click <a onclick="javascript:starter();return false;" href="#">here</a>. After that, you may close this window.</p>
    <p>Or, click <a href="index.php">here</a> to go back to the normal login.</p>
    <script type="text/javascript">
      /* <![CDATA[ */
      function starter() {
        var win__ = window.open("index.php", "tr0ub4dor_win", "width=650,height=400,left=0,top=0,scrollbars=yes,status=yes,resizable=yes");
      }

      starter();
      /* ]]> */
    </script>
OUT;

  $out__ .= write_footer_end();

  echo $out__;

?>