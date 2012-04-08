<?php
session_save_path('./tmp');
session_start();
// CATEGORIES - 
// test if session is ok
$incpath = "./include/";
require ($incpath . "testsession.php");
if (test_session() == 1) {
    include ($incpath . "config.php");
    echo "<html>\n<head>\n<title>w3pw Select Category to edit</title>\n";
    include ($incpath . "css.php");
    include ($incpath . "crypt.php");
    include ($incpath . "headerstuff.php");
    include ($incpath . "mainfunctions.php");
    echo "</head><body>\n";
    echo "<form method=\"post\" action=\"main.php\">\n<input type=\"hidden\" name=\"action\" value=\"selectcat\">\n";
    echo "<center><table>\n";
    echo "<tr><th colspan=\"2\">Select Category to Edit (or Delete)</th></tr>\n";
    // display categories here
    // initially display numbers only, with names,
    // for user to enter number
    // get categories from database
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            $sqlcat = "SELECT catid, catname FROM categories ";
            $listcat = mysql_query($sqlcat);
            //$entriescat = mysql_fetch_object($listcat); // this just gets first entry
            while ($entriescat = mysql_fetch_object($listcat)) {
                $cat_array[$entriescat->catid] = de_crypt($entriescat->catname, $_SESSION['key']);
                // the above is for REAL - IF categories are to be encrypted
                // below is for development OR for real if no encryption of categories
                //$cat_array[$entriescat->catid]=$entriescat->catname;
                
            } // while
            natcasesort($cat_array);
            reset($cat_array);
            //sortcatbyobject($cat_array);
            
        } // if db exists
        else {
            echo "<br />Ooops - <b>Can't connect to the database</b>....\n";
        } // no db
        mysql_close($conn);
    } // if db connect
    else {
        echo "<br />Ooops - <b>Can't connect to the database-server</b>...\n";
    } // no db connect
    // display catnames and catids
    echo "<tr><td class=\"odd\">Choose category: </td>";
    echo "<td class=\"even\">";
    echo "<select name=\"catid\" size=\"1\">";
    // put the drop down category list here
    // it makes no sense to have -none- as an option here
    // since we arae choosing a cagtegory to either delete or edit
    // how to delete -none- or edit -none- ??
    // plus we want to keep -none- as it is the "categorey" for items with no category
    while (list($catid, $catname) = each($cat_array)) {
        echo "<option value=\"$catid\">\n";
        echo "$catname\n";
        echo "</option>\n";
    } // while
    echo "</td></tr>\n";
    echo "</table>\n";
    echo "<input name=\"edit\" type=\"submit\" value=\"Edit Selection\">\n";
    echo "<input name=\"delete\" type=\"submit\" value=\"Delete Selection\">\n";
    echo "</form>\n<p>Go back to <a href=\"main.php\">Main Menu</a> without saving.</p></center>\n";
    echo "</body>\n";
} // if test session - 1 or ok

?>
