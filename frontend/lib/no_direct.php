<?php
/* $Id$ */
  if (eregi($CURR_SCRIPT, $_SERVER['PHP_SELF'])) {
    header("Location:../logout.php");
  }
?>