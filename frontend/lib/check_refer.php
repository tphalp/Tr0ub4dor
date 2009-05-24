<?php
  if(isset($_SERVER['HTTP_REFERER']) && !eregi(BASE_DOMAIN, $_SERVER['HTTP_REFERER'])) {
    go_home();
  }
?>