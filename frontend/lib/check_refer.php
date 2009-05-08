<?php
  if (!eregi("w3pw.ring0ffire.com", $_SERVER['HTTP_REFERER'])) {
    header("Location:/");
  }
?>