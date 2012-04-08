<?php
    /*
    before deleting categories, check to see if any entries remain in the cagtegory
    
    also, display list of cagtegories with a delete link - like the netries displsay, with the view, edit, delete links
    model after this
    
    to delee cat - idea -
    
    TBD:
    1- find if cat empty
    
    TBD:
    2- find if there are subcats
    
    TBD:
    3- if empty AND if no subcats, just delete it
    
    TBD:
    4- if subcats, store them in array
    - get parent of cat-2-b-deleted
    - loop thru array and change parent from the curr 2-b-deleted cat to the parent of the cat-2-b-deleted
    - if no parent of curr cat-2-b-deleted, then swet all parents of cats in array to 0
    
    TBD:
    5- if empty, and step 4 completed, then just del cat
    
    TBD:
    6- if non-empty, then store items of this cat in arrray
    
    TBD:
    7- loop thru items array and change their cat to parent of curr-cat-2-b-deleted
    
    TBD:
    8 - if step 7 complete, then deleet cat
    
    */
session_save_path('./tmp');
session_start();
$incpath = "./include/";
// test if session is ok
require ($incpath . "testsession.php");
if (test_session() == 1) {
    include ($incpath . "config.php");
    include ($incpath . "css.php");
    include ($incpath . "crypt.php");
    include ($incpath . "headerstuff.php");
    include ($incpath . "crud.php");
    echo "<html>\n<head>\n<title>w3pw Delete category</title>\n";
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {

            // get catid to be deleted
            if (ctype_digit($_SESSION['selectedcatid'])) {
                //$id = $_GET['ID'];
                $clean['catid'] = $_SESSION['selectedcatid'];
            } 

            // get catparent
            $mysql = array();
            $mysql['catid'] = mysql_real_escape_string($clean['catid']);
            $list = mysql_query("SELECT catparent FROM categories WHERE catid=" . $mysql['catid']);
            $parents = mysql_fetch_object($list);
            // $catparent = de_crypt($parents->catparent, $_SESSION['key']);
            $catparent = $parents->catparent;
            $catid = $mysql['catid'];

            // get subcats, if any
            $subcats = array(); // of ints
            $subcats = getsubcats($catid);

            // get entries in cat, if any
            $entries = array();
            $entries = getentries($catid);

            // if subcats exist, change their parent to catparent
            if (count($subcats) > 0) { // move subcats to parent cat
                setparentcat($subcats, $catparent);
            }

            // if entries exist, change their catid to catparent
            if (count($entries) > 0) { // move entries to parent cat
                setcat($entries, $catparent);
            }

            // now delete category
            delcat($catid);
        } // mysql sel db
        else {
            echo "<br />Ooops - <b>Can't find the database</b>....\n";
        }
        mysql_close($conn);
    } // mysql connect
    else {
        echo "<br />Ooops - <b>Can't connect to the database-server</b>...\n";
    }
    echo "</body>\n";
    ?>
    <script type="text/javascript">
        <!--
            location.replace("main.php");
        -->
    </script>
    <?php
} // test_session

?>