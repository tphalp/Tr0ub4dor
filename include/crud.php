<?php

//  - functions -
//##############################
//###### FUNCTION ##############
//##############################

function delcat($catid)
{
    $sql = 'DELETE from categories where catid = ' . $catid;
    $result = mysql_query($sql);
    return $result;
} // function delcat

//##############################
//###### FUNCTION ##############
//##############################

function setcat($entriesarray, $catid)
{
    /**
     * given an array of items/entries, set their category to catid
     *
     */
    $inlist = implode(', ', $entriesarray);
    $sql = 'UPDATE catmap set catid = ' . $catid . ' where wid in (' . $inlist . ');';
    $result = mysql_query($sql);
    return $result;
} // function setcat

//##############################
//###### FUNCTION ##############
//##############################

function setparentcat($catarray, $catid)
{
    /**
     * given an array of categories, set their parent to catid
     *
     */
    $inlist = implode(', ', $catarray);
    $sql = 'UPDATE categories set catparent = ' . $catid . ' where catid in (' . $inlist . ');';
    $result = mysql_query($sql);
    return $result;
} // function setparentcat

//##############################
//###### FUNCTION ##############
//##############################

function getsubcats($catid)
{
    /**
     * given a category id as intenger, return array of integer
     * category ids of subcats of input catid
     *
     */
    $subcats = array();
    if (ctype_digit($catid)) {
        $sql = 'select catid from categories where catparent = ' . $catid;
        $list = mysql_query($sql);
        while ($row = mysql_fetch_array($list)) {
            $subcats[] = $row['catid'];
        }
    } // if
    return $subcats;
} // function getsubcats

//##############################
//###### FUNCTION ##############
//##############################

function getentries($catid)
{
    /**
     * given a category id as integer, return array of integer
     * ids of entries in input catid
     *
     */
    $entries = array();
    if (ctype_digit($catid)) {
        $sql = 'select wid from catmap where catid = ' . $catid;
        $list = mysql_query($sql);
        while ($row = mysql_fetch_array($list)) {
            $entries[] = $row['wid'];
        }
    } // if
    return $entries;
} // function getentries

//##############################
//###### FUNCTION ##############
//##############################

// save new entry
function savenew($hostname, $database, $dbuser, $dbpasswd)
{
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            $list = mysql_query("INSERT INTO wallet VALUES('','" . mysql_escape_string(en_crypt($_POST['itemname'], $_SESSION['key'])) . "','" . mysql_escape_string(en_crypt($_POST['host'], $_SESSION['key'])) . "','" . mysql_escape_string(en_crypt($_POST['login'], $_SESSION['key'])) . "','" . mysql_escape_string(en_crypt($_POST['password'], $_SESSION['key'])) . "','" . mysql_escape_string(en_crypt($_POST['comment'], $_SESSION['key'])) . "')");
            $wid = mysql_insert_id(); // the entry / wallet ID
            // the above is for UN-encrypted categories
            // for ENcrypted onces, follow the pattern of host, login, etc.
            $list = mysql_query("INSERT INTO catmap VALUES('" . mysql_escape_string($wid) . "','" . mysql_escape_string($_POST['catid']) . "')");
            unset($_POST['itemname'], $_POST['host'], $_POST['login'], $_POST['password'], $_POST['catid'], $_POST['comment']);
        } else {
            echo "<br />Ooops - <b>can't find the database</b>....\n";
        }
        mysql_close($conn);
    } else {
        echo "<br />Ooops - <b>can't connect to the database-server</b>...\n";
    }
} // function savenew

//##############################
//###### FUNCTION ##############
//##############################

// save new category
function savenucat($hostname, $database, $dbuser, $dbpasswd)
{
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            $list = mysql_query("INSERT INTO categories VALUES('','" . mysql_escape_string(en_crypt($_POST['catname'], $_SESSION['key'])) . "','" .
            //mysql_escape_string (en_crypt($_POST['catparent'],$_SESSION['key']))."','".
            mysql_escape_string($_POST['catparent']) . "','" . mysql_escape_string(en_crypt($_POST['comment'], $_SESSION['key'])) . "')");
            unset($_POST['catname'], $_POST['catparent'], $_POST['comment']);
        } else {
            echo "<br />Ooops - <b>can't find the database</b>....\n";
        }
        mysql_close($conn);
    } else {
        echo "<br />Ooops - <b>can't connect to the database-server</b>...\n";
    }
} // function savenucat

//##############################
//###### FUNCTION ##############
//##############################

// save edited category
function saveoldcat($hostname, $database, $dbuser, $dbpasswd)
{
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            $list = mysql_query("UPDATE  categories SET 
			catname='" . mysql_escape_string(en_crypt($_POST['catname'], $_SESSION['key'])) .
            //"',catparent='".mysql_escape_string(en_crypt($_POST['catparent'],$_SESSION['key'])).
            "',catparent='" . mysql_escape_string($_POST['catparent']) . "', comment='" . mysql_escape_string(en_crypt($_POST['comment'], $_SESSION['key'])) . "' WHERE CATID=" . $_POST['CATID']);
            unset($_POST['catname'], $_POST['catparent'], $_POST['comment']);
        } else {
            echo "<br />Ooops - <b>can't find the database</b>....\n";
        }
        mysql_close($conn);
    } else {
        echo "<br />Ooops - <b>can't connect to the database-server</b>...\n";
    }
} // function saveoldcat

//##############################
//###### FUNCTION ##############
//##############################

function saveold($hostname, $database, $dbuser, $dbpasswd)
{
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            $list = mysql_query("UPDATE wallet SET itemname='" . mysql_escape_string(en_crypt($_POST['itemname'], $_SESSION['key'])) . "', host='" . mysql_escape_string(en_crypt($_POST['host'], $_SESSION['key'])) . "', login='" . mysql_escape_string(en_crypt($_POST['login'], $_SESSION['key'])) . "', pw='" . mysql_escape_string(en_crypt($_POST['password'], $_SESSION['key'])) . "', comment='" . mysql_escape_string(en_crypt($_POST['comment'], $_SESSION['key'])) . "' WHERE ID=" . $_POST['ID']);
            $wid = $_POST['ID']; // the entry / wallet ID
            $catid = $_POST['catid'];
            echo "<h1>id is $wid, catid is $catid</h1>";
            $list = mysql_query("UPDATE catmap SET 
			catid=" . mysql_escape_string($catid) . " WHERE wid=" . mysql_escape_string($wid));
            unset($_POST['itemname'], $_POST['host'], $_POST['login'], $_POST['password'], $_POST['comment'], $_POST['catid']);
        } else {
            echo "<br />Ooops - <b>Can't find the database</b>....\n";
        }
        mysql_close($conn);
    } else {
        echo "<br />Ooops - <b>Can't connect to the database-server</b>...\n";
    }
} // function saveold

//##############################
//###### FUNCTION ##############
//##############################

function reallydelete($hostname, $database, $dbuser, $dbpasswd)
{
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            $list = mysql_query("DELETE FROM wallet WHERE ID=" . $_POST['ID']);
        } else {
            echo "<br />Ooops - <b>Can't find the database</b>....\n";
        }
        mysql_close($conn);
    } else {
        echo "<br />Ooops - <b>Can't connect to the database-server</b>...\n";
    }
} // function reallydelete

//##############################
//###### FUNCTION ##############
//##############################

function imprtfile($hostname, $database, $dbuser, $dbpasswd)
{
    echo "<h1> Importing needs to be modified to work with categories</h1>\n";
    return;
    $row = $_POST['row'];
    // check that each header field is used only once in import2.php
    // sort header_fields by occurence
    asort($row);
    if ($conn = mysql_connect($hostname, $dbuser, $dbpasswd)) {
        if (mysql_select_db($database, $conn)) {
            // finally import the data
            $fd = fopen($tmppath . "w3pw.csv", "r");
            while ($data = fgetcsv($fd, 4096, ";")) {
                if (count($data) > 1) {
                    $mysql_string = "INSERT INTO wallet VALUES(''";
                    reset($_POST['row']);
                    while (list($index, $val) = each($_POST['row'])) {
                        $mysql_string.= ",'" . mysql_escape_string(en_crypt($data[$val], $_SESSION['key'])) . "'";
                    } // while
                    $mysql_string.= ")";
                    mysql_query($mysql_string);
                    unset($mysql_string);
                } // if count
                
            } // while data
            fclose($fd);
            unset($row);
            unset($data);
        } // if select-db
        else {
            echo "<br />Ooops - <b>Can't find the database</b>....\n";
        } // else
        mysql_close($conn);
    } // if connect-db
    else {
        echo "<br />Ooops - <b>Can't connect to the database-server</b>...\n";
    } // else
    
} // function imprtfile

//##############################
//###### FUNCTION ##############
//##############################

?>
