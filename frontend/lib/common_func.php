<?php
/* $Id$ */
  // Necessary includes
  require_once("crypt.php");
  require_once("db.php");

  
  function show_sys_msg($txt) {
  //------------------------------------------------------------------
  // Forward to system message page, passing message text found 
  // in $txt param.
  //------------------------------------------------------------------  
    go_to_url("../sysmsg.php?q=". encode_msg($txt));
  } //show_sys_msg()
  
  
  function encode_msg($txt) {
  //------------------------------------------------------------------
  // Encode with base64 and then urlencode twice. Not sure why, but 
  // this is necessary, or the decoding in decode_msg() does not work.
  //------------------------------------------------------------------  
    return urlencode(urlencode(base64_encode($txt)));
  } //encode_msg()
  
  
  function decode_msg($txt) {
  //------------------------------------------------------------------
  // urldecode and then decode base64 on $txt
  //------------------------------------------------------------------  
    return base64_decode(urldecode($txt));
  } //decode_msg()


  function go_to_url($url) {
  //------------------------------------------------------------------
  // Simple function that utilizes the "header()" function in PHP. 
  // forwards to url in $url param.
  //------------------------------------------------------------------  
    header("Location:$url");
  } //go_to_url()
 	
  
  function test_session($test_only = FALSE)	{
  //------------------------------------------------------------------
  // Tests whether the session exists, and is valid. If not, back to 
  // the login page. If $test_only is 1, then we return 1 is valid, 
  // and 0 if invalid. Some areas of the system only need a test 
  // for session validity, not a forward to the login page after.
  //------------------------------------------------------------------  
    $out__ = FALSE;
    
    if ((!isset($_SESSION['logged_in'])) || (!$_SESSION['logged_in'] == 1))	{
      if ($test_only == FALSE) {
        session_unset();
        session_destroy();
        go_to_url('../' . PAGE_LOGIN);
      }
    } else {
      $out__ = TRUE;
    }

    return $out__;
  } //test_session()

  
  function check_referrer($base_domain) {
  //------------------------------------------------------------------
  // Checks for valid referrer. Must be referred from $base_domain, 
  // which is located in the config file. If not, then goes back to 
  // the login page.
  //------------------------------------------------------------------  
    $bad = TRUE;
    
    if (isset($_SERVER['HTTP_REFERER']) && eregi($base_domain, $_SERVER['HTTP_REFERER'])) {
      $bad = FALSE;
    }

    if ($bad) {
      go_to_url('../' . PAGE_LOGIN);
    }
  } //check_referrer()
  
  
  function no_direct() {
  //------------------------------------------------------------------
  // Redirects if page is called directly.
  //------------------------------------------------------------------  
    if (eregi($_SERVER['REQUEST_URI'], $_SERVER['PHP_SELF'])) {
      go_to_url('../' . PAGE_LOGIN);
    }
  } //no_direct()

  
  function write_header_meta() {
  //------------------------------------------------------------------
  // Returns various meta info for use in the head tag.
  //------------------------------------------------------------------  
    $out__ = <<<OUT
    
    <meta name="robots" content="noindex, nofollow" />
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
OUT;

    return $out__;

  } //write_header_meta()
  

  function write_header_jquery() {
  //------------------------------------------------------------------
  // Returns markup for jquery script inclusion.
  //------------------------------------------------------------------  
    $out__ = <<<OUT
    
    <script type="text/javascript" src="js/jquery-min.js"></script>
OUT;
    
    return $out__;

  } //write_header_jquery()


  function write_header_counter() {
  //------------------------------------------------------------------
  // Returns the timer element, and the info element, used for 
  // displaying the timeout message, and various information 
  // relating to system changes, deletes, inserts, etc.
  //------------------------------------------------------------------  
    $out__ = <<<OUT
    
    <span id="timeout" class="important">&nbsp;</span><span id="info" title="click to close">&nbsp;</span>
OUT;

    return $out__;

  } //write_header_counter()

  
  function write_header_begin($pg_title) {
  //------------------------------------------------------------------
  // Returns the beginning markup for the page, including most of 
  // the head portion.
  //------------------------------------------------------------------  
    $sys_name = SYS_NAME;
    $style = STYLE_DEFAULT;
    $script = JS_DEFAULT;
    
    $out__ = <<<OUT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" id="navtop">
  <head>
    <title>$pg_title | $sys_name</title>
    <link type="text/css" rel="stylesheet" href="$style" />
    <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico" />
    <script type="text/javascript" src="$script"></script>
OUT;

    return $out__;

  } //write_header_begin()
    

  function write_header_end($id = "default") {
  //------------------------------------------------------------------
  // Returns the end of the header tag, and the start of the 
  // body tag.
  //------------------------------------------------------------------  
    $out__ = <<<OUT
    
  </head>
  <body id="$id">
OUT;

    return $out__;

  } //write_header_end()

  
  function write_header_common() {
  //------------------------------------------------------------------
  // Returns common parts of the header. Acts as a wrapper for 
  // other common function.
  //------------------------------------------------------------------  
    $out__ = '';
    $out__ .= write_header_meta();

    return $out__;

  } //write_header_common()

  
  function write_header_menu($footer = FALSE) {
  //------------------------------------------------------------------
  // Returns the menu for both the header and footer, although not 
  // at the same time. They must be called separately. The $footer 
  // param dictates whether or not it is the header or footer menu 
  // that is needed.
  //------------------------------------------------------------------  
    // Gets the menu items from the config  file and puts them in arrays
    $link = explode("|", MENU_ITEMS_TEXT);
    $urls = explode("|", MENU_ITEMS_URLS);
    $out__ = '';
  
    if (!$footer) {

      $out__ .= '<div class="menu-wrap"><div class="menu-container"><ul class="header-menu">';

      foreach ($link as $index => $text) {
        if (strpos(MENU_SHOW_MAIN, strval($index)) !== FALSE) {
          $out__ .= '<li><a href="' . $urls[$index] . '">' . $text . '</a></li>';
        }
      }
      
      $out__ .= '</ul></div></div>';

    } else {
    
      foreach ($link as $index => $text) {
        if (strpos(MENU_SHOW_FOOT, strval($index)) !== FALSE) {
          $out__ .= '<a href="' . $urls[$index] . '">' . $text . '</a> | ';
        }
      }
      
      $out__ = rtrim($out__, " | ");
    }

    return $out__;
    
  } //write_header_menu()


  function write_footer_end() {    
  //------------------------------------------------------------------
  // Returns the ending markup for the page.
  //------------------------------------------------------------------  
    $out__ = <<<OUT
    
  </body>
</html>
OUT;

    return $out__;

  } //write_footer_end()
 
 
  function write_footer_common() {
  //------------------------------------------------------------------
  // Returns common pieces of the footer.
  //------------------------------------------------------------------  
    $out__ = write_footer_copyright();
    $out__ .= write_footer_end();

    return $out__;

  } //write_footer_common()
 
 
  function write_footer_onload($actions) {
  //------------------------------------------------------------------
  // Returns javascirpt that wraps the $actions param inside 
  // the jquery .ready function, in order to spawn processes after 
  // the page finishes loading.
  //------------------------------------------------------------------  
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
  //------------------------------------------------------------------
  // Returns the javascript that initiates the timeout process.
  //------------------------------------------------------------------  
    $timeout = constant("TIMEOUT");
    $timeout_show = constant("TIMEOUT_SHOW");

		$out__ = write_footer_onload("init($timeout, $timeout_show);");
		
		return $out__;
		
	} //write_footer_timeout_init()

  
  function write_footer_copyright() {
  //------------------------------------------------------------------
  // Returns the footer copyright text, as well as the system 
  // version, and the textual menu.
  //------------------------------------------------------------------  
    $menu__ = write_header_menu(TRUE);
    $sys_name = SYS_NAME;
    
    $out__ = <<<OUT
    
    <div id="footer">
      <p class="l">${menu__}</p>
      <p class="r" id="sysinfo">${sys_name} | v${_SESSION['version']} <a href="http://w3pw.sourceforge.net/" target="_blank"><img src="images/logo-icon-16.png" alt="lock icon" title="w3pw on SourceForge" id="logo-icon" /></a></p>
    </div>
OUT;

    return $out__;
    
  } //write_footer_copyright()
  

  function write_footer_main_link($end_text = '') {
  //------------------------------------------------------------------
  // Returns the link to the main list, which resides in the footer
  // of some pages.
  //------------------------------------------------------------------  
    $end_text = strlen($end_text) > 0 ? " " . $end_text : ".";
    
    return '<p>Go back to <a href="' . PAGE_MAIN . '">Main List</a>' . $end_text . '</p>';
  } //write_footer_main_link
  
/*  
  function get_param($id) {
    $db = new Data;
    
    // Call the stored proc
    $param = $db->out_array("call get_params($id);");

    unset($db);
  
  } //get_param()
*/
  
  function set_https() {
  //------------------------------------------------------------------
  // Used to attach the secure "s" in https. Very simple method.
  //------------------------------------------------------------------
    $port = "http";
    
    if ($_SERVER['HTTPS'] = "on") {
      $port .= "s";
    }
  
    return $port;
  } //set_https()
  
  
  function delete_stray_temp_files($tmp) {
  //------------------------------------------------------------------
  // Delete any temp files that may have been left behind from 
  // an import.
  //------------------------------------------------------------------
    if (is_file($tmp.TMP_IMPORT_FILE))	{
      unlink($tmp.TMP_IMPORT_FILE);
    }
  } //delete_stray_temp_files()
    
  
  function build_item_array($obj, $br = FALSE) {
  //------------------------------------------------------------------
  // Returns an array of all wallet entries. Some massaging is done
  // to the data here as well.
  //------------------------------------------------------------------
    $out__ = array( "id"      => $obj->ID,
                    "name"    => stripslashes( htmlentities( de_crypt($obj->itemname, $_SESSION['key'] ) ) ),
                    "host"    => stripslashes( htmlentities( de_crypt($obj->host, $_SESSION['key'] ) ) ),
                    "login"   => stripslashes( htmlentities( de_crypt($obj->login, $_SESSION['key'] ) ) ),
                    "pw"      => stripslashes( htmlentities( de_crypt($obj->pw, $_SESSION['key'] ) ) ),
                    "comment" => stripslashes( htmlentities( de_crypt( $obj->comment, $_SESSION['key'] ) ) )
                  );

    //------------------------------------------
    // This handles the need for \n conversion 
    // to <br /> for certain situations
    //------------------------------------------
    if ($br) {
      $out__["comment"] = nl2br($out__["comment"]);
    }
            
    return $out__;
  
  } //build_item_array()
  
  
  function create_web_link($link, $target = "_blank") {
  //------------------------------------------------------------------
  // Returns a proper link for the URL portion of the wallet entry.
  //------------------------------------------------------------------
    if (strlen($link) > 0) { 
      // Add proper protocol entity
      if ((!strstr($link, 'http://')) 
        && (!strstr($link, 'https://')) 
        && (!strstr($link, 'ftp://'))) {
          $hosturl = "http://" . $link;
      } else {
        $hosturl = $link;
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
  //------------------------------------------------------------------
  // Create random password, with given length (set in config.php)
  //------------------------------------------------------------------
    $out__ = '';

    for ($x=0; $x < $pw_length; $x++) {
      $out__ .= chr(mt_rand(33, 127));
    }
    
    return $out__;
  
  } //create_rand_pw()
	
  
  function get_db_conn() {
  //------------------------------------------------------------------
  // Returns a connection to the database, given the creds below.
  //------------------------------------------------------------------
    $db = Data_MySQLi::get_instance(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    if ($db) {
      return $db;
    }

    $sysmsg__ = "<br />Ooops - <b>can't connect to the database-server</b>...\n";
    show_sys_msg($sysmsg__);

  } //get_db_conn()
  
  
  function build_nav_links($text = 'Jump to: ') {
  //------------------------------------------------------------------
  // Build the list of First Chars that you can use to "jump" to the beginning
  // of a group.
  //------------------------------------------------------------------
    $out__ = '<tr><td colspan="5" id="navlinks">' . $text . '@@NAV_LINKS</td></tr>';
    
    return $out__;
  
  } //build_nav_links()
  
  
  function build_nav_link_anchor($ID, $first_char) {
  //------------------------------------------------------------------
  // Adds $first_char to the nav links at the top of the page.
  //------------------------------------------------------------------
    $out__ = '<a href="#nav' . $ID . '">' . $first_char . '</a>&nbsp;';
    
    return $out__;
  
  } //build_nav_link_anchor()


  function build_group_header($ID, $first_char, $top_link = TOP_LINK) {
  //------------------------------------------------------------------
  // This will output a Grouping Header for the passed params
  //------------------------------------------------------------------
    $out__ = '<tr><td colspan="5" class="group-set"><span id="nav' . $ID . '">' . $first_char . '</span><a title="back to top" href="#navtop">' . $top_link . '</a></td></tr>';
    
    return $out__;
  
  } //build_group_header()
  
  
  function mask_data($to_mask) {
  //------------------------------------------------------------------
  // adds $first_char to the nav links at the top of the page.
  //------------------------------------------------------------------
    $out__ = '<div class="sensitive">' . $to_mask . '</div>';
    
    return $out__;
  } //mask_data()
  
?>