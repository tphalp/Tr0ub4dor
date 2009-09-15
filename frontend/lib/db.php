<?php
/* $Id$ */
//----------------------------------------------------------
// Description: MySQLi connector class for w3pw
// Author: tphalp (tphalp at ring0ffire dot com)
// Original Date: 2009-05-21
// Modification:
// 
// TODO: clean up the error handling using try/catch.
//----------------------------------------------------------
class Data_MySQLi {

  private static $instance;
  public $error;
  public $conn;


  private function __construct($host, $user, $pass, $name, $port = 3306) {
    try {
      $this->conn = new mysqli($host, $user, $pass, $name, $port);

      if (mysqli_connect_error()) {
        //throw new Exception('Database error:' . mysqli_connect_error());
        $this->error = 'Database error:' . mysqli_connect_error();
      }
    } catch( Exception $e ) {
      $this->error = $e->getMessage();
    }
  } //__construct

  
	public static function get_instance($host, $user, $pass, $name, $port = 3306) {
    if (!isset(self::$instance)) {
      $class = __CLASS__;
      self::$instance = new $class($host, $user, $pass, $name, $port);
    }
    return self::$instance;
  } //get_instance()


  public function __destruct() {
    if (isset(self::$instance)) {
      @$this->conn->close();
    }
  } //__destruct()

  
  public final function __clone() {
    throw new BadMethodCallException("Clone is not allowed");
  } //__clone()
    
        
  public function out_array($query, $out_type = "object", $array_type = MYSQL_ASSOC) {
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
    $db_link = $this->conn;
    
    if (!$db_link) {
      die("Can't connect to database");
      exit;
    }

    $rs = $db_link->query($query) or die($db_link->error);

    //------------------------------------------------------
    // Each row in the result set will be packaged as
    // an array and put in an array
    //------------------------------------------------------
    if ($out_type == "object") {
      while($row = $rs->fetch_object()) {
        array_push($arr, $row);
      }
    } else {
      while($row = $rs->fetch_array($type)) {
        array_push($arr, $row);
      }    
    }
      
    //------------------------------------------------------
    // Clean up
    //------------------------------------------------------
    $this->clear_results($db_link);
    unset($rs, $row, $db_link);
    
    return $arr;
  } //out_array()


  public function out_row_object($query) {
  //---------------------------------------------------
  // Returns a row object from a query
  //---------------------------------------------------
    //------------------------------------------------------
    // Execute the query
    //------------------------------------------------------
    $out__;
    $qry_res;
    $db_link = $this->conn;
    
    if (!$db_link) {
      die("Can't connect to database");
      exit;
    }
    
    $qry_res = $db_link->query($query) or die($db_link->error);
    $rs = $qry_res->fetch_object();

    $out__ = $rs;

    //------------------------------------------------------
    // Clean up
    //------------------------------------------------------
    $this->clear_results($db_link);
    unset($qry_res, $rs, $row, $db_link);
    
    return $out__;
  } //out_row_object()


  public function out_result_object($query) {
  //---------------------------------------------------
  // Returns a result object from a query
  //---------------------------------------------------
    //------------------------------------------------------
    // Execute the query
    //------------------------------------------------------
    $db_link = $this->conn;
    
    if (!$db_link) {
      die("Can't connect to database");
      exit;
    }
    
    $qry = $db_link->query($query) or die($db_link->error);
    
    $out__ = $qry;
    
    //------------------------------------------------------
    // Clean up
    //------------------------------------------------------
    $this->clear_results($db_link);
    unset($qry, $row, $db_link);
    
    return $out__;
  } //out_result_object()

  
  public function in_sql_no_data($query) {
    //------------------------------------------------------
    // Execute the query that does not return data. 
    // Returns the number of rows affected.
    //------------------------------------------------------
    $db_link = $this->conn;
    
    if (!$db_link) {
      return "Unable to connect to the database";
    }
    
    $db_link->query($query) or die($db_link->error);
    $num_rows = $db_link->affected_rows;
    
    //------------------------------------------------------
    // Clean up
    //------------------------------------------------------
    $this->clear_results($db_link);
  
    unset($db_link);
    
    return $num_rows;
  } //in_sql_no_data()
  
  
  public function field_name_array($query) {
  //------------------------------------------------------
  // Pass in a query result object, and it will return
  // an array with all the field names.
  //------------------------------------------------------

    $fields = $this->conn->fetch_fields($query);
    
    foreach($fields as $fi => $f) {
      $names[] = $f->name;
    }
     
    return $names;
 
  } //field_name_array()

  
  protected function clear_results($link) {
    while ($link->next_result()) {
      $link->store_result();
    }
  } //clear_results()
  
} //class Data_MySQLi
?>