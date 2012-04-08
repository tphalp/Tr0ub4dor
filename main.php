<?php
session_save_path('./tmp'); 
session_start();
$incpath = "./include/";
include ($incpath . "config.php");
include ($incpath . "crud.php");
include ($incpath . "mainfunctions.php");
?>
<html>
<head>

<title>w3pw Main</title>

<?php
include ($incpath . "css.php");
include ($incpath . "headerstuff.php");
include ($incpath . "crypt.php");
?>
</head>
<body>

<?php
// session active?
if (!isset($_SESSION['logged_in'])) {
    // do some checking here
    // 1st get user pw
    // explode around '-'
    // then 2 parts - send 1 to nosess... after checking other part with $pwsalt in config
    // 1 get user pw
    if (isset($_POST['password'])) {
        $userpw = $_POST['password'];
    } // if got a password try from user
    // 2 explode
    $pwarr = explode('_', $userpw);
    // 3 check last part with $pwsalt
    if ($pwarr[1] != $pwsalt) $pwarr[0] = 'restricted'; // if no match, then mess up first part so login fails
    // 3 pass other part to nosess
    nosesslogin($hostname, $database, $dbuser, $dbpasswd, $pwarr[0]); // modification
    
} // if no session or not logged in
if ((isset($_SESSION['logged_in'])) && ($_SESSION['logged_in'] == 1)) {
    // session is active
    performAction($hostname, $database, $dbuser, $dbpasswd);
    // check if there is an uploaded file still in the tmp directory -> delete
    if (is_file($tmppath . "w3pw.csv")) {
        unlink($tmppath . "w3pw.csv");
    }
    // menu header
    mainMenuHeader();
    /*
    so we need to put together the strings
    and use array of strings
    each string is a top level cat, plus subs...
    1st get all categories whose parent is toplevel
    foreach of these find all subcats
    foreach of these find all subcats
    etc.
    is there a data structure like a tree in php?
    */
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            /*
            new take
            
            1st get all cats catparent etc from db
            store in objects or arrays for dropdown list of cats
            then select a cat to choose which entries to display
            */
            $catarr = array(); // array of categories
            $sql = "select * from categories ";
            $list = mysql_query($sql);
            while ($entries = mysql_fetch_object($list)) {
                //$catarr[$entries->catid] = $entries;
                $catarr[] = $entries;
            }
            // create menu string of categories
            //$menustring = makeMenuString($catarr); // this does nothing - see fn def in include/mainfunctions.php
            // this (above) was probably left over from experimenting with other ways of dealing with categories
            catlist($catarr);
            if (isset($_POST['sbmtallcatmain'])) {
                displayCatEntries('all');
            } // if isset sbmtallcatmain
            if (isset($_POST['sbmtcatmain'])) {
                displayCatEntries('cat');
            } else {
                // below is getting data for main table on page
                //displayCatEntries('all');
                //$sql = "SELECT ID, itemname FROM wallet";
                //drawMainTable($sql);
                
            } // else
            
        } // if db exists / found
        else {
            echo "<br />Ooops - <b>Can't find the database</b>....\n";
        } // else
        mysql_close($conn);
    } // if connect to db
    else {
        echo "<br />Ooops - <b>Can't connect to the database-server</b>...\n";
    } // else
    
} // if logged in and active session

?>
</body>
</html>
