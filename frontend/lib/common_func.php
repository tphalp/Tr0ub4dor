<?php
  // Necessary includes
  require_once("crypt.php");
  require_once("db.php");

  
  function show_sys_msg($txt) {
    //Forward to sysmessage page
    go_to_url("../sysmsg.php?q=". urlencode(urlencode(base64_encode($txt))));
  } //show_sys_msg()
  
  
  function go_home() {
    go_to_url("/");
  } //go_home()

  
  function go_to_url($url) {
    header("Location:$url");
  } //do_to_url()
 	
  
  function test_session($test_only = 0)	{
    $out__ = 0;
    
    if ((!isset($_SESSION['logged_in'])) || (!$_SESSION['logged_in'] == 1))	{
      if ($test_only == 0) {
        go_home();
      }
    } else {
      $out__ = 1;
    }

    return $out__;
  } //test_session()

  
  function check_referrer($base_domain) {
    $bad = TRUE;
    
    if (isset($_SERVER['HTTP_REFERER']) && eregi($base_domain, $_SERVER['HTTP_REFERER'])) {
      $bad = false;
    }

    if ($bad) {
      go_home();
    }
  } //check_referrer()
  
  
  function no_direct() {
    if (eregi($_SERVER['REQUEST_URI'], $_SERVER['PHP_SELF'])) {
      go_home();
    }
  } //no_direct()

  
  function write_header_meta() {
    $out__ = <<<OUT
    
    <meta name="robots" content="noindex, nofollow" />
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
OUT;

    return $out__;

  } //write_header_meta()
  

  function write_header_jquery() {
    $out__ = <<<OUT
    
    <script type="text/javascript" src="js/jquery-min.js"></script>
OUT;
    
    return $out__;

  } //write_header_jquery()


  function write_header_counter() {
    $out__ = <<<OUT
    
    <span id="timeout" class="important">&nbsp;</span> <span id="frmmsg"></span>
OUT;

    return $out__;

  } //write_header_counter()

  
  function write_header_begin($pg_title) {
    $sys_name = SYS_NAME;
    $style = DEFAULT_STYLE;
    $script = DEFAULT_JS;
    
    $out__ = <<<OUT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>$pg_title | $sys_name</title>
    <link type="text/css" rel="stylesheet" href="$style" />
    <script type="text/javascript" src="$script"></script>
OUT;

    return $out__;

  } //write_header_begin()
    

  function write_header_end($id = "default") {
    $out__ = <<<OUT
    
  </head>
  <body id="$id">
OUT;

    return $out__;

  } //write_header_end()

  
  function write_header_common() {
    $out__ = '';
    $out__ .= write_header_meta();

    return $out__;

  } //write_header_common()

  
  function write_header_menu($footer = false) {
    $link = explode("|", MENU_ITEMS_TEXT);
    $urls = explode("|", MENU_ITEMS_URLS);
    $out__ = '';
  
    if (!$footer) {

      $out__ .= '<div class="menu-wrap"><div class="menu-container"><ul class="header-menu">';

      foreach ($link as $index => $text) {
        if (strpos(MENU_SHOW_MAIN, strval($index)) !== false) {
          $out__ .= '<li><a href="' . $urls[$index] . '">' . $text . '</a></li>';
        }
      }
      
      $out__ .= '</ul></div></div>';

    } else {
    
      foreach ($link as $index => $text) {
        if (strpos(MENU_SHOW_FOOT, strval($index)) !== false) {
          $out__ .= '<a href="' . $urls[$index] . '">' . $text . '</a> | ';
        }
      }
      
      $out__ = rtrim($out__, " | ");
    }

    return $out__;
    
  } //write_header_menu()


  function write_footer_end() {    
    $out__ = <<<OUT
    
  </body>
</html>
OUT;

    return $out__;

  } //write_footer_end()
 
 
  function write_footer_common() {
    $out__ = write_footer_copyright();
    $out__ .= write_footer_end();

    return $out__;

  } //write_footer_common()
 
 
  function write_footer_onload($actions) {
  
    $out__ = <<<OUT
    
    <script type="text/javascript">
      /* <![CDATA[ */
      $(document).ready(function(){
         $actions
       });
      /* ]]> */
    </script>
OUT;
  
    return $out__;
  
  } //write_footer_onload()

  
  function write_footer_timeout_init() {
    $timeout = constant("TIMEOUT");
    $timeout_show = constant("TIMEOUT_SHOW");

		$out__ = write_footer_onload("init($timeout, $timeout_show);");
		
		return $out__;
		
	} //write_footer_timeout_init()

  
  function write_footer_copyright() {
    $menu__ = write_header_menu(TRUE);
    $sys_name = SYS_NAME;
    
    $out__ = <<<OUT
    
    <div id="footer">
      <p class="l">$menu__</p>
      <p class="r">$sys_name | v${_SESSION['version']}</p>
    </div>
OUT;

    return $out__;
    
  } //write_footer_copyright()
  

  function write_footer_main_link($end_text = '') {
    $end_text = strlen($end_text) > 0 ? " " . $end_text : ".";
    
    return "<p>Go back to <a href=\"/main.php\">Main List</a>$end_text</p>";
  } //write_footer_main_link
  
  
  function get_params() {
    $db = new Data;
    
    // Call the stored proc
    $param = $db->in_query_out_array("call get_paramss();", DB_HOST, DB_NAME, DB_USER, DB_PASS);

    unset($db);
  
  } //get_params()
  
  
  function set_https() {
    $port = "http";
    
    if ($_SERVER['HTTPS'] = "on") {
      $port .= "s";
    }
  
    return $port;
  } //set_https()
  
  
  function delete_stray_temp_files($tmp) {
    if (is_file($tmp.TMP_IMPORT_FILE))	{
      unlink($tmp.TMP_IMPORT_FILE);
    }
  } //delete_stray_temp_files()
    
  
  function build_item_array($obj) {

    $out__ = array( "id"      => $obj->ID,
                    "name"    => html_entity_decode(de_crypt($obj->itemname, $_SESSION['key'])),
                    "host"    => html_entity_decode(de_crypt($obj->host, $_SESSION['key'])),
                    "login"   => html_entity_decode(de_crypt($obj->login, $_SESSION['key'])),
                    "pw"      => html_entity_decode(de_crypt($obj->pw, $_SESSION['key'])),
                    "comment" => html_entity_decode(str_replace("\n", "<br />", de_crypt($obj->comment, $_SESSION['key'])))
                    );
  
    return $out__;
  
  } //build_item_array()
  
  
  function create_web_link($link, $target = "_blank") {

    if (strlen($link) > 0) { 
      // Add proper protocol entity
      if ((!strstr($link, 'http://')) 
        && (!strstr($link, 'https://')) 
        && (!strstr($link, 'ftp://'))) {
          $hosturl = "http://" . $link;
      }

      $target = ' target="' . $target . '"';
      
      $out__ = <<<OUT
<a href="$hosturl"$target>$link</a>
OUT;
      return $out__;
    }
    
    return $link;
  } //create_web_link()
  
  
  function create_rand_pw($pw_length) {
  // Create random password, with given length (set in config.php)
    $out__ = '';

    for ($x=0; $x < $pw_length; $x++) {
      $out__ .= chr(mt_rand(33, 127));
    }
    
    return $out__;
  
  } //create_rand_pw()
	
  
  function get_db_conn() {
    $db = Data_MySQLi::get_instance(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($db) {
      return $db;
    }

    $sysmsg__ = "<br />Ooops - <b>can't connect to the database-server</b>...\n";
    show_sys_msg($sysmsg__);

  } //get_db_conn()
  
?>