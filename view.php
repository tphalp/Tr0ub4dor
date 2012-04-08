<?php
session_save_path('./tmp');
session_start();
$incpath = "./include/";
$clean = array(); // to store filtered - clean - input
// test if session is ok
require ($incpath . "testsession.php");
if (test_session() == 1) {
    include ($incpath . "config.php");
    include ($incpath . "crypt.php");
    echo "<html>\n<head>\n<title>w3pw Wallet entry</title>\n";
    include ($incpath . "css.php");
    include ($incpath . "headerstuff.php");
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            //$list = mysql_query ("SELECT * FROM wallet WHERE ID=".$_GET['ID']);
            // if below messes up, use above line
            // and replace {$clean['ID']} with $_GET['ID'] or just $id.
            if (ctype_digit($_GET['ID'])) {
                //$id = $_GET['ID'];
                $clean['id'] = $_GET['ID'];
            }
            // this works...
            $mysql = array();
            $mysql['id'] = mysql_real_escape_string($clean['id']);
            $sql = "SELECT ID, itemname, host, login, pw, comment ";
            $sql.= " FROM wallet ";
            //$sql .= " WHERE ID=".$id;
            $sql.= " WHERE ID=" . $mysql['id'];
            //$sql = "SELECT ID, itemname, host, login, pw, comment, wid, categories.catid, catname ";
            //$sql .= " FROM wallet, catmap, categories ";
            //$sql .= " WHERE wid=ID and categories.catid=catmap.catid and ID=".$_GET['ID'];
            // this works - only if category, etc exists
            // to make it work for all, more robust,
            // let basic entry info NOT
            // depend on existence of category -
            // so separate them into 2 swl commands
            // 1 to get the entry info as in orig code,
            // and 1 to get the category
            $sqlcat = "SELECT catname FROM categories, catmap ";
            $sqlcat.= "WHERE catmap.wid={$mysql['id']} and catmap.catid=categories.catid ";
            $list = mysql_query($sql);
            //echo $sql; // debug
            $entries = mysql_fetch_object($list);
            $listcat = mysql_query($sqlcat);
            $entriescat = mysql_fetch_object($listcat);
            echo "</head><body>\n";
            //print_r($entries); // debug
            echo "<center><table>\n";
            echo "<tr><th colspan=\"2\">View Wallet entry</th></tr>\n";
            echo "<tr><td class=\"odd\">Entryname: </td><td class=\"even\">" . de_crypt($entries->itemname, $_SESSION['key']) . "</td></tr>\n";
            echo "<tr><td class=\"odd\">Host/URL: </td><td class=\"even\">";
            if (de_crypt($entries->host, $_SESSION['key'])) {
                // create link if there is a host url
                echo "<a href=\"";
                // if host does not start with http:// add this
                if ((!strstr(de_crypt($entries->host, $_SESSION['key']), 'http://')) && (!strstr(de_crypt($entries->host, $_SESSION['key']), 'https://')) && (!strstr(de_crypt($entries->host, $_SESSION['key']), 'ftp://'))) {
                    echo "http://";
                }
                echo de_crypt($entries->host, $_SESSION['key']);
                echo "\" target=\"newwin\">";
            }
            echo de_crypt($entries->host, $_SESSION['key']);
            if (de_crypt($entries->host, $_SESSION['key'])) {
                echo "</a>";
            }
            echo "</td></tr>\n";
            echo "<tr><td class=\"odd\">Login: </td><td class=\"even\">" . htmlentities(de_crypt($entries->login, $_SESSION['key'])) . "</td></tr>\n";
            echo "<tr><td class=\"odd\">Password: </td><td class=\"even\">" . htmlentities(de_crypt($entries->pw, $_SESSION['key'])) . "</td></tr>\n";
            echo "<tr><td class=\"odd\">Category: </td><td class=\"even\">" . htmlentities(de_crypt($entriescat->catname, $_SESSION['key'])) . "</td></tr>\n";
            // use the above instead of below IF categoryname is encrypted
            //echo "<tr><td class=\"odd\">Category: </td><td class=\"even\">".htmlentities(($entriescat->catname))."</td></tr>\n";
            // change cr's to <br>
            echo "<tr><td class=\"odd\">Comment: </td><td class=\"even\">" . str_replace("\n", "<br />", htmlentities(de_crypt($entries->comment, $_SESSION['key']))) . "</td></tr>\n";
            echo "</table>\n";
            //echo "<p><a href=\"edit.php?ID=".$e->id."\">edit</a></p>\n";
            echo "<p><a href=\"edit.php?ID=" . $mysql['id'] . "\">Edit This Entry</a></p>\n";
            echo "<p>Go back to <a href=\"main.php\">Main Menu</a>.</p></center>\n";
        } else {
            echo "<br />Ooops - <b>Can't connect to the database</b>....\n";
        }
        mysql_close($conn);
    } else {
        echo "<br />Ooops - <b>Can't connect to the database-server</b>...\n";
    }
    echo "</body>\n";
}
?>
