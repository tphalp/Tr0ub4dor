<?php
session_save_path('./tmp');
session_start();
$incpath = "./include/";
// test if session is ok
require ($incpath . "testsession.php");
if (test_session() == 1) {
    include ($incpath . "config.php");
    include ($incpath . "crypt.php");
    echo "<html>\n<head>\n<title>w3pw Edit Wallet entry</title>\n";
    include ($incpath . "css.php");
    include ($incpath . "headerstuff.php");
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            //$id = $_GET['ID'];
            if (ctype_digit($_GET['ID'])) {
                //$id = $_GET['ID'];
                $clean['id'] = $_GET['ID'];
            }
            $mysql = array();
            $mysql['id'] = mysql_real_escape_string($clean['id']);
            $list = mysql_query("SELECT * FROM wallet WHERE ID=" . $mysql['id']);
            $entries = mysql_fetch_object($list);
            echo "</head><body>\n";
            echo "<form method=\"post\" action=\"main.php\">\n<input type=\"hidden\" name=\"action\" value=\"editsave\">\n<input type=\"hidden\" name=\"ID\" value=\"" . $mysql['id'] . "\">";
            echo "<center><table>\n";
            echo "<tr><th colspan=\"2\">Edit Wallet entry</th></tr>\n";
            echo "<tr><td class=\"odd\">Entryname: </td><td class=\"even\"><input type=\"text\" name=\"itemname\" size=\"40\" value=\"" . de_crypt($entries->itemname, $_SESSION['key']) . "\"></td></tr>\n";
            echo "<tr><td class=\"odd\">Host/URL: </td><td class=\"even\"><input type=\"text\" name=\"host\" size=\"40\" value=\"" . de_crypt($entries->host, $_SESSION['key']) . "\"></td></tr>\n";
            echo "<tr><td class=\"odd\">Login: </td><td class=\"even\"><input type=\"text\" name=\"login\" size=\"40\" value=\"" . de_crypt($entries->login, $_SESSION['key']) . "\"></td></tr>\n";
            echo "<tr><td class=\"odd\">Password: </td><td class=\"even\"><input type=\"text\" name=\"password\" size=\"40\" value=\"" . de_crypt($entries->pw, $_SESSION['key']) . "\"></td></tr>\n";
            echo "<tr><td class=\"odd\">Comment: </td><td class=\"even\"><textarea name=\"comment\" cols=\"40\" rows=\"6\">" . de_crypt($entries->comment, $_SESSION['key']) . "</textarea></td></tr>\n";
            // do category here
            // get categories from database
            $sql = "SELECT catid FROM catmap WHERE wid={$mysql['id']}";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            $thecatid = $row['catid'];
            if (ctype_digit($thecatid)) {
                $clean['thecatid'] = $thecatid;
            }
            $sqlcat = "SELECT catid, catname FROM categories ";
            $listcat = mysql_query($sqlcat);
            //$entriescat = mysql_fetch_object($listcat); // this just gets first entry
            while ($entriescat = mysql_fetch_object($listcat)) {
                $cat_array[$entriescat->catid] = de_crypt($entriescat->catname, $_SESSION['key']);
                // the above is for REAL - IF categories are to be encrypted
                // below is for development OR for real if no encryption of categories
                //$cat_array[$entriescat->catid]=$entriescat->catname;
                
            } // while
            //natcasesort($cat_array);
            reset($cat_array);
            // display catnames and catids
            echo "<tr><td class=\"odd\">Choose the category: </td>";
            echo "<td class=\"even\">";
            echo "<select name=\"catid\" size=\"1\" >";
            // put the drop down category list here
            echo "<option value=\"0\" ";
            if (0 == $thecatid) {
                echo " selected=\"selected\" ";
            }
            echo ">\n";
            echo "- none -\n";
            echo "</option>\n";
            while (list($catid, $catname) = each($cat_array)) {
                echo "<option value=\"$catid\" ";
                if ($catid == $thecatid) {
                    echo " selected=\"selected\" ";
                }
                echo ">\n";
                echo htmlentities($catname, ENT_QUOTES) . "\n";
                echo "</option>\n";
            } // while
            echo "</td></tr>\n";
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
