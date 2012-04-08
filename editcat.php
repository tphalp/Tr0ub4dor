<?php
session_save_path('./tmp');
session_start();
$incpath = "./include/";
// CATEGORIES - 
// test if session is ok
require ($incpath . "testsession.php");
if (test_session() == 1) {
    include ($incpath . "config.php");
    include ($incpath . "crypt.php");
    include ($incpath . "css.php");
    include ($incpath . "headerstuff.php");
    echo "<html>\n<head>\n<title>w3pw Edit Category</title>\n";
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            //$id = $_GET['ID'];
            if (ctype_digit($_SESSION['selectedcatid'])) {
                //$id = $_GET['ID'];
                $clean['catid'] = $_SESSION['selectedcatid'];
            }
            $mysql = array();
            $mysql['catid'] = mysql_real_escape_string($clean['catid']);
            $list = mysql_query("SELECT * FROM categories WHERE catid=" . $mysql['catid']);
            $entries = mysql_fetch_object($list);
            echo "</head><body>\n";
            echo "<form method=\"post\" action=\"main.php\">\n<input type=\"hidden\" name=\"action\" value=\"editsavecat\">\n<input type=\"hidden\" name=\"CATID\" value=\"" . $mysql['catid'] . "\">";
            echo "<center><table>\n";
            echo "<tr><th colspan=\"2\">Edit Category</th></tr>\n";
            echo "<tr><td class=\"odd\">Category Name: </td><td class=\"even\"><input type=\"text\" name=\"catname\" size=\"40\" 
				value=\"" . de_crypt($entries->catname, $_SESSION['key']) . "\"></td></tr>\n";
            // store parent cat and comment for future use
            $catparent = $entries->catparent; // not encrypted
            $comment = de_crypt($entries->comment, $_SESSION['key']);
            // get categories from database
            if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
                if (mysql_select_db($database, $conn)) {
                    $sqlcat = "SELECT catid, catname FROM categories ";
                    $listcat = mysql_query($sqlcat);
                    //$entriescat = mysql_fetch_object($listcat); // this just gets first entry
                    while ($entriescat = mysql_fetch_object($listcat)) {
                        $cat_array[$entriescat->catid] = de_crypt($entriescat->catname, $_SESSION['key']);
                        // the above is for REAL - IF category names are to be encrypted
                        // below is for development OR for real if no encryption of category names
                        //$cat_array[$entriescat->catid]=$entriescat->catname;
                        
                    } // while
                    natcasesort($cat_array);
                    reset($cat_array);
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
            echo "<tr><td class=\"odd\">Parent category: </td>";
            //echo "<tr><td class=\"odd\">Choose the category: </td>";
            echo "<td class=\"even\">";
            echo "<select name=\"catparent\" size=\"1\">";
            //echo "<select name=\"catid\" size=\"1\" >";
            // put the drop down category list here
            echo '<option value="0" \n';
            if ($catparent == 0) echo " selected = \"selected\"";
            echo "> - none - \n";
            echo "</option>\n";
            while (list($catid, $catname) = each($cat_array)) {
                echo "<option value=\"$catid\"\n";
                // here if catname = $catparent, then select in this list
                if ($catparent == $catid) echo " selected = \"selected\"";
                //echo ">\n";
                //echo htmlentities($catname, ENT_QUOTES) . "\n";
                echo ">$catname\n";
                echo "</option>\n";
            } // while
            echo "</td></tr>\n";
            echo "<tr><td class=\"odd\">Comment: </td><td class=\"even\"><textarea name=\"comment\" cols=\"40\" rows=\"6\">" . de_crypt($entries->comment, $_SESSION['key']) . "</textarea></td></tr>\n";

            echo "</table>\n";
            echo "<input type=\"submit\" value=\"Save\">\n";
            echo "</form>\n<br /><br />Go back to <a href=\"main.php\">Main Menu</a> without saving.</center>\n";
        } else {
            echo "<br />Ooops - <b>Can't find the database</b>....\n";
        }
        mysql_close($conn);
    } else {
        echo "<br />Ooops - <b>Can't connect to the database-server</b>...\n";
    }
    echo "</body>\n";
}
?>
