<?php

class Data {

  public $err;
  
	function out_array($query, $host, $db_name, $un, $pw, $out_type = "object", $array_type = MYSQL_ASSOC) {
  //---------------------------------------------------
  // Returns an array with data from a query
  //---------------------------------------------------
		//------------------------------------------------------
		// Array variable
		//------------------------------------------------------
		$arr = array();

		//------------------------------------------------------
		// Execute the query
		//------------------------------------------------------
		$db_link = mysqli_connect($host, $un, $pw, $db_name);
		
		if (!$db_link) {
			die("Can't connect to database");
			exit;
		}
		
		$rs = mysqli_query($db_link, $query) or die(mysqli_error($db_link));
    
		//------------------------------------------------------
		// Each row in the result set will be packaged as
		// an array and put in an array
		//------------------------------------------------------
    if ($out_type == "object") {
      while($row = mysqli_fetch_object($rs)) {
        array_push($arr, $row);
      }
    } else {
      while($row = mysqli_fetch_array($rs, $type)) {
        array_push($arr, $row);
      }    
    }
      
		//------------------------------------------------------
		// Clean up
		//------------------------------------------------------
    unset($rs);
		unset($row);
		unset($db_link);
		
		return $arr;
	} //out_array()


  function out_rs_object($query, $host, $db_name, $un, $pw) {
  //---------------------------------------------------
  // Returns an array with data from a query
  //---------------------------------------------------
		//------------------------------------------------------
		// Execute the query
		//------------------------------------------------------
		$out__;
    $qry_res;
    $db_link = mysqli_connect($host, $un, $pw, $db_name);
		
		if (!$db_link) {
			die("Can't connect to database");
			exit;
		}
		
		$qry_res = mysqli_query($db_link, $query);
    $rs = mysqli_fetch_object($qry_res);

    $out__ = $rs;
		//------------------------------------------------------
		// Clean up
		//------------------------------------------------------
    unset($qry_res);
    unset($rs);
    unset($row);
		unset($db_link);
		
		return $out__;
	} //out_rs_object()


  function out_result_object($query, $host, $db_name, $un, $pw) {
  //---------------------------------------------------
  // Returns an array with data from a query
  //---------------------------------------------------
		//------------------------------------------------------
		// Execute the query
		//------------------------------------------------------
		$out__;
    $db_link = mysqli_connect($host, $un, $pw, $db_name);
		
		if (!$db_link) {
			die("Can't connect to database");
			exit;
		}
		
		$qry = mysqli_query($db_link, $query);

    $out__ = $qry;
		//------------------------------------------------------
		// Clean up
		//------------------------------------------------------
    unset($qry);
    unset($row);
		unset($db_link);
		
		return $out__;
	} //out_result_object()

  
	function in_sql_no_data($query, $host, $db_name, $un, $pw) {
		//------------------------------------------------------
		// Execute the query that does not return data
		//------------------------------------------------------
		$db_link = mysqli_connect($host, $un, $pw, $db_name);
		
		if (!$db_link) {
      return "Unable to connect to the database";
			//die("Can't connect to database");
			//exit;
		}
		
		mysqli_query($db_link, $query) or die(mysqli_error($db_link));
		
		//------------------------------------------------------
		// Clean up
		//------------------------------------------------------
		unset($db_link);
    
    return "";
	} //in_sql_no_data()
	
  
    function field_name_array($query) {
    //------------------------------------------------------
    // Pass in a query result object, and it will return
    // an array with all the field names.
    //------------------------------------------------------

      $fields = mysqli_fetch_fields($query);
      
      foreach($fields as $fi => $f) {
        $names[] = $f->name;
      }
       
      return $names;
   
    }

    
  } //class Data
?>