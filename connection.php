<?php
/**
* 
*/
require_once "constants.php";


class DB {
	

	private static $instance = NULL;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance() {
    	if (!isset(self::$instance)) {
			self::$instance = new mysqli(Con::$SERVER_NAME, Con::$USERNAME, Con::$PASSWORD, Con::$DB_NAME);
			if(self::$instance -> connect_error){
				die("connection failed: ".self::$instance -> connect_error);
			}
      	}
      	return self::$instance;
    }

    //create a comma separated string of col or table names 
    //eg. "Fname, Mname, Lname" 
    private static function parseToStr($arr, $quotation=false) {
    	$str = "";
    	$arrLength = sizeof($arr);
    	for($i=0;$i<$arrLength;$i++) {
    		if($quotation){
    			$str .= "'".$arr[$i]."'";
    		} else{
    			$str .= $arr[$i];
    		}

    		
    		if($i<$arrLength - 1){
    			$str .= ", ";
    		} else{
    			$str .= " ";
    		}	
    	}
    	return $str;
    }

    // concatenate table name with columnName eg. Student.Fname
    // use parseToStr to create comma separated string
    public static function parseColTab($columnName) {
    	$tabName = array();
    	$colName = array();
    	foreach ($columnName as $key => $value) {
    		array_push($tabName, $key);
    		for ($i=0; $i < sizeof($value); $i++) { 
    			array_push($colName, $key.".".$value[$i]);
    		}
    	}
    	return array(self::parseToStr($colName), self::parseToStr($tabName));
    }

    /*
    selects a record from the database
	$tableName - array -  multiple tables
	$columnName - array - multiple columns
	$criteria - string
    */

    public static function select($tableColName, $criteria="", $extraSql="", $outputMethod=0, $deleted=0) {
    	$result = array();
    	if(sizeof($tableColName)>0){
    		$parsed = self::parseColTab($tableColName);
    		$sql = "SELECT ";
	    	$sql .= $parsed[0];
            $sql .= $extraSql;
	    	$sql .= "FROM ";
	    	$sql .= $parsed[1];

	    	if(strlen($criteria) > 0){
	    		$sql .= "WHERE " . $criteria . " AND Deleted=".$deleted ;
	    	}
	    	
            $result = self::querySelect($sql, $outputMethod);
    	} 
    	return $result;    	
    }

    public function execQuery($sql){
        return self::getInstance()->query($sql);
    }

    public static function querySelect($sql, $outputMethod=0){
        $result = array();
        $conn = self::getInstance();
        $res = $conn->query($sql);
        if($res && $res->num_rows > 0){
            $counter = 0;
            while($row = $res-> fetch_assoc()){
                if($outputMethod == 1){
                    array_push($result, $row);
                } else if ($outputMethod == 0) {
                    if($counter == 0){
                        array_push($result, array_keys($row));  // take the column names only once
                    }
                    array_push($result, array_values($row));    //the rest data will be only the values
                    $counter++;
                }

                
            }
        }/* else{
            trigger_error("Invalid query: " . $conn->error);
        }*/
        return $result;
    }

    /*
    inserts a record into the database
	$tableName - String - one table
	$colValuePair - Associative array
    */

    public static function insert($tableName, $colValuePair) {
    	$sql = "INSERT INTO ".$tableName." (";

    	$colName = array_keys($colValuePair);
    	$values = array_values($colValuePair);

    	$sql .= self::parseToStr($colName).") VALUES (";
    	$sql .= self::parseToStr($values, true).")";
    	return boolval(self::getInstance()->query($sql));
    }


    /*
    updates a record in the database
	$tableName - String - one table
    $colValuePair - Associative array
    $criteria - String
    */
    public static function update($tableName, $colValuePair, $criteria) {
    	$sql = "UPDATE ".$tableName." SET ";
    	$colValPair = array();
    	foreach ($colValuePair as $key => $value) {
    		array_push($colValPair, $key."='".$value."'");
    	}
    	$sql .= self::parseToStr($colValPair)."WHERE ".$criteria;

    	return boolval(self::getInstance()->query($sql));
    }
    
    /*
    deletes a record from the database
	$tableName - string - single table
	$criteria - string - complete criteria string
    */

    public static function delete($tableName, $criteria) {
    	$sql = "DELETE FROM ".$tableName." WHERE ".$criteria;
    	return boolval(self::getInstance()->query($sql));
    }


}

?>
