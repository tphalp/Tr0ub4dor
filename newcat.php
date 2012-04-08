<?php
session_save_path('./tmp');
session_start();
$incpath = "./include/";
// test if session is ok
require ($incpath . "testsession.php");
if (test_session() == 1) {
    include ($incpath . "config.php");
    echo "<html>\n<head>\n<title>w3pw Insert new category entry</title>\n";
    include ($incpath . "css.php");
    include ($incpath . "crypt.php");
    include ($incpath . "headerstuff.php");
    /*		// create random password, when enabled
    $initial_pw = "";
    if ($random_pw_length > 0)
    {
    for ($x=0;$x<$random_pw_length;$x++)
    {
    $initial_pw .= chr(rand(33,127));
    }
    }
    */
    echo "</head><body>\n";
    echo "<form method=\"post\" action=\"main.php\">\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"savenucat\">\n";
    echo "<center><table>\n";
    echo "<tr><th colspan=\"2\">Insert new category entry</th></tr>\n";
    echo "<tr><td class=\"odd\">Category Name: </td><td class=\"even\"><input type=\"text\" name=\"catname\" size=\"40\"></td></tr>\n";
    // parent category planned for later - initial categories are
    // to be 1 level
    // get categories from database
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            $sqlcat = "SELECT catid, catname FROM categories ";
            $listcat = mysql_query($sqlcat);
            while ($entriescat = mysql_fetch_object($listcat)) {
                $cat_array[$entriescat->catid] = de_crypt($entriescat->catname, $_SESSION['key']);
                // the above is for REAL - IF categories are to be encrypted
                // below is for development OR for real if no encryption of categories
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
    // TODO sort categories based on catname, not catid; check if already done?
    // display catnames and catids
    echo "<tr><td class=\"odd\">Parent category: </td>";
    echo "<td class=\"even\">";
    echo "<select name=\"catparent\" size=\"1\">";
    // put the drop down category list here
    echo "<option value=\"0\">\n";
    echo "-none-\n";
    echo "</option>\n";
    while (list($catid, $catname) = each($cat_array)) {
        echo "<option value=\"$catid\">\n";
        echo "$catname\n";
        echo "</option>\n";
    } // while
    echo "</td></tr>\n";
    /*
    echo "<tr><td class=\"odd\">Parent Category: </td><td class=\"even\"><input type=\"text\" name=\"catparent\" size=\"40\"></td></tr>\n";
    */
    echo "<tr><td class=\"odd\">Comment: </td><td class=\"even\"><textarea name=\"comment\" cols=\"40\" rows=\"6\"></textarea></td></tr>\n";
    echo "</table>\n";
    echo "<input type=\"submit\" value=\"Save\">\n";
    echo "</form>\n<p>Go back to <a href=\"main.php\">Main Menu</a> without saving.</p></center>\n";
    echo "</body>\n";
}
?>
