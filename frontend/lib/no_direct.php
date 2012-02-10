<?php
/* $Id$ */
  if (!stripos($_SERVER['PHP_SELF'], $CURR_SCRIPT) === FALSE) {
    header("Location:../logout.php");
  }
?>