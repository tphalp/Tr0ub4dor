<?php

//##############################
//###### FUNCTION ##############
//##############################

function mainMenuHeader()
{
    echo "<center><table width=\"100%\" 
		style=\" table-layout:fixed\">\n<tr class=menu>\n";
    echo "<td><a href=\"main.php\" class=\"menu\">list</a></td>";
    echo "<td><a href=\"insert.php\" class=\"menu\">new entry</a></td>";
    echo "<td><a href=\"newcat.php\" class=\"menu\">new category</a></td>";
    echo "<td><a href=\"editcatsel.php\" class=\"menu\">edit (del) category</a></td>";
    echo "<td><a href=\"import.php\" class=\"menu\">import</a></td>";
    echo "<td><a href=\"logout.php\" class=\"menu\">logout</a></td>\n";
    echo "</tr></table></center><p>\n";
} // function mainMenuHeader

//##############################
//###### FUNCTION ##############
//##############################

function displayCatEntries($all)
{
    $clean = array();
    echo "<center><table width=\"100%\" 
       style=\"table-layout:fixed; \"> \n";
    $sql = "SELECT ID, itemname FROM wallet ";
    if ($all == 'all') {
        drawEntriesHeader();
        drawMainTable($sql);
    } else {
        foreach ($_POST['catlist'] as $cat) {
            if (ctype_digit($cat)) {
                $clean['catlist'][] = $cat;
            }
        }
        $cat = null;
        foreach ($clean['catlist'] as $cat) {
            $sql = "SELECT ID, itemname FROM wallet, catmap ";
            $sql.= " WHERE wallet.ID = catmap.wid AND catid=" . $cat;
            drawMainTable($sql);
        } // foreach
        
    } // else
    // table footer
    echo "<tr><td colspan=\"6\" 
        style=\"background-color:blue; height:8px;\" 
        ></td></tr>\n";
    echo "</table></center>";
    echo "<p>w3pw v" . $_SESSION['version'] . "</p>";
} // function displayCatEntries

//##############################
//###### FUNCTION ##############
//##############################

function drawMainTable($sql)
{
    $list = mysql_query($sql);
    $header_array = array();
    while ($entries = mysql_fetch_object($list)) {
        $header_array[$entries->ID] = de_crypt($entries->itemname, $_SESSION['key']);
    }
    natcasesort($header_array);
    reset($header_array);
    $entriesArray = getCatInfo($ID, $itemname, $header_array);
    mainTable($entriesArray);
    unset($header_array, $itemname, $entriesArray);
} // function

//##############################
//###### FUNCTION ##############
//##############################

function makeMenuString($catarr)
{
    foreach ($catarr as $cat) {
    }
    return $menustring;
} // function

//##############################
//###### FUNCTION ##############
//##############################

function catlist($catarr)
{
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    echo "
		<form action=\"" . $_SERVER['PHP_SELF'] . " \" method=\"post\" >
		<select name=\"catlist[ ]\" ";
    print " multiple=\"multiple\" ";
    // below is for opera, since opera shows only
    // a single row of categories without it
    if (substr_count($useragent, 'pera') > 0) {
        print " style=\" height: 180px;\" ";
    } // if
    print " >\n";
    $count = 0;
    $obarr = array();

    foreach ($catarr as $cat) {
        $cat->catname = de_crypt($cat->catname, $_SESSION['key']);
    } // foreach

    sortcatbyobject($catarr);

    foreach ($catarr as $cat) {
        echo "
	  <option value=\"" . $cat->catid . "\" >
	  " . $cat->catid . " " . $cat->catname . "
	  </option>
      ";
    } // foreach
    echo "
		</select>
		<input type=\"submit\" name=\"sbmtcatmain\" value=\"Display Category\" />
		<input type=\"submit\" name=\"sbmtallcatmain\" value=\"Display All\" />
		</form>
		";
} // function catlist

//##############################
//###### FUNCTION ##############
//##############################

function sortcatbyobject(&$oa)
{
    usort($oa, "cmpcat");
} // function

//##############################
//###### FUNCTION ##############
//##############################

function cmpcat(&$o1, &$o2)
{
    return strnatcasecmp($o1->catname, $o2->catname);
} // function

//##############################
//###### FUNCTION ##############
//##############################

function drawEntriesHeader()
{
    echo "
        <tr><th style=\"width:80px\">Category</th>
        <th style=\"width:140px\">Entryname</th>
        <th>Host/URL</th>
        <th style=\"width:45px\">&nbsp;</th>
        <th style=\"width:45px\">&nbsp;</th>
        <th style=\"width:55px\">&nbsp;</th>
        </tr>\n";    
} // function

//##############################
//###### FUNCTION ##############
//##############################

function cmp(&$o1, &$o2)
{
    return strnatcasecmp($o1->itemname, $o2->itemname);
} // function

//##############################
//###### FUNCTION ##############
//##############################

function sortbyobject(&$oa)
{
    usort($oa, "cmp"); // this WORKS
} // function

//##############################
//###### FUNCTION ##############
//##############################

function mainTable($entriesArray)
{
    $counter = 0;
    $prevCatid = - 1;
    /* idea on how to alpha sort display on entryname / itemname
        
        in loop below, 1st store all fields in multi-d array
        
        then sort array, moving field to pos 0, 
    and printing out in diff order if need be,
    then print array with formatting, etc....
        
    */
    sortbyobject($entriesArray);
    foreach ($entriesArray as $e) {
        $counter++;
        $top = false;
        // table header
        if ($counter == 1) {
        } else $top = false;
        // space between heading and first row of entries
        if ($prevCatid != $e->catid && !$top) {
            echo "
            <tr><td style=\"background-color:rgb(90,50,200); 
            height:8px;\" colspan=\"6\"></td></tr>
            ";
            echo "
            <tr><td colspan=\"6\" > <b>Category: 
            <span style=\" padding: 4px; font-size: 18px; 
            text-align: center; border-style: inset; border-color: blue; \">
            {$e->catname}</span> </b><br /></td></tr>
            ";
            echo "
            <tr><td style=\"background-color:rgb(90,50,200); 
            height:8px;\" colspan=\"6\"></td></tr>
            ";
            drawEntriesHeader();
        }
        // show entries
        if ($counter % 2 == 0) {
            echo "<tr class=\"even\">";
        } else {
            echo "<tr class=\"odd\">";
        }
        echo "<td>" . $e->catname . "</td>" . "<td>" . $e->itemname . "</td><td>" . $e->host . "</td><td>&nbsp;<a href=\"view.php?ID=" . $e->id . "\">view</a>&nbsp;</td><td>&nbsp;<a href=\"edit.php?ID=" . $e->id . "\">edit</a>&nbsp;</td><td>&nbsp;<a href=\"delete.php?ID=" . $e->id . "\">delete</a>&nbsp;</td></tr>\n";
        $prevCatid = $e->catid;
    } // foreach
    
} // function mainTable

//##############################
//###### FUNCTION ##############
//##############################

function getCatInfo($ID, $itemname, $header_array)
{ 
    $counter = 0;
    while (list($ID, $itemname) = each($header_array)) {
        $counter++;
        $sqlentry = "SELECT catmap.catid, catname, host FROM wallet, catmap, categories ";
        $sqlentry.= "WHERE ID=" . $ID . " AND catmap.catid=categories.catid AND ID=catmap.wid";
        if ($list = mysql_query($sqlentry)) {
        } else {
            mysql_error(" mysql error");
        }
        $entries2 = mysql_fetch_object($list);
        $entries2->catname = de_crypt($entries2->catname, $_SESSION['key']);
        $entries2->itemname = $itemname;
        $entries2->host = de_crypt($entries2->host, $_SESSION['key']);
        $entries2->id = $ID;
        // $entries2->catid is already there in the sql object!
        //$wrapentry['catid']=$entries2->catid;
        //$wrapentry['catname']=$entries2->catname;
        //$wrapentry['object']=$entries2;
        $entriesArray[] = $entries2;
    } // while
    // new category sort field has index
    // catsortkey = itemid and is unique
    // catsortvalue = category name
    // then sort by catsortkey
    asort($entriesArray);
    reset($entriesArray);
    return $entriesArray;
} // function getCatInfo

//##############################
//###### FUNCTION ##############
//##############################

function performAction($hostname, $database, $dbuser, $dbpasswd)
{
    // any actions to perform?
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {

            // save new entry
            case 'save':
                savenew($hostname, $database, $dbuser, $dbpasswd);
                break;

            // save edited entry
            case 'editsave':
                saveold($hostname, $database, $dbuser, $dbpasswd);
                break;

            // delete entry
            case 'reallydelete':
                reallydelete($hostname, $database, $dbuser, $dbpasswd);
                break;

            // NOT YET IMPLEMENTED - for categories
            // import uploaded file
            case 'import':
                imprtfile($hostname, $database, $dbuser, $dbpasswd);
                break;

            // new category stuff
            case 'savenucat':
                savenucat($hostname, $database, $dbuser, $dbpasswd);
                break;
 
            // NOT YET IMPLEMENTED
            // UPDATE - IMPLEMENTED UNDER case 'selectcat': BELOW
            // delete category
            case 'delcat':
                delcat($hostname, $database, $dbuser, $dbpasswd);
                break;
                // NOT YET IMPLEMENTED
                // UPDATE - IMPLEMENTED UNDER case 'selectcat': BELOW

            // select category for editing or deletion
            case 'selectcat':
                // store selected cat in sess var, then use that sess var ...
                $_SESSION['selectedcatid'] = $_POST['catid'];

                if (isset($_POST['edit'])) {
                ?>
                    <script type="text/javascript">
                        <!--
                            location.replace("editcat.php");
                        -->
                    </script>
                <?php }

                if (isset($_POST['delete'])) {
                ?>
                <script type="text/javascript">
                    <!--
                        location.replace("deletecat.php");
                    -->
                </script>
                <?php }
                break;

            // save edited category
            case 'editsavecat':
                saveoldcat($hostname, $database, $dbuser, $dbpasswd);
                break;
            } // end switch
            
        } // if action
        
} // function performAction

//##############################
//###### FUNCTION ##############
//##############################

// no session or not logged in

//function nosesslogin($hostname, $database, $dbuser, $dbpasswd) // modified function
function nosesslogin($hostname, $database, $dbuser, $dbpasswd, $cleartext_pw)
{ // no session active - check pw
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            //$cleartext_pw = "";
            // encrypt the pw given at logon
            //if (isset($_POST['password']))
            //{
            //$cleartext_pw = $_POST['password'];
            //$cleartext_pw = $userpw;
            //} // if got a password try from user
            $crypt_pw = sha1($cleartext_pw);
            // check pw
            $list = mysql_query("SELECT version, pw FROM main");
            $entries = mysql_fetch_object($list);
            $db_pw = $entries->pw;
            if ($crypt_pw == $db_pw) {
                // password match - proceed
                $_SESSION['logged_in'] = 1;
                $_SESSION['key'] = md5("%dJ9&" . strtolower($cleartext_pw) . "(/&k.=" . strtoupper($cleartext_pw) . "1x&%");
                // delete cleartext pw in memory
                unset($cleartext_pw);
                $_SESSION['version'] = $entries->version;
            } // if pw match
            else {
                session_unset();
                session_destroy();
                echo "<body><b>Wrong Password</b>....<br />try <a href=\"index.php\">again</a>\n";
            } // else pw not match
            
        } // if selected db
        else {
            // cant connect to database
            session_unset();
            session_destroy();
            echo "<br />Ooops - <b>Can't connect to the database</b>....<br />Please try <a href=\"index.php\">again</a>\n";
        } // else select db fails
        mysql_close($conn);
    } // if connect to mysql server
    else {
        // cant connect to the server
        session_unset();
        session_destroy();
        echo "<br />Ooops - <b>Can't connect to the database-server</b>...<br />Please try <a href=\"index.php\">again</a>\n";
    } // else mysql connect fails
    
} // function nosesslogin

//##############################
//###### FUNCTION ##############
//##############################

?>
