<?php
/**
	Copyright 2012 by Nayar Joolfoo

	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, version 3 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
//require 'local_config.php';
class MySQL {
	
// 	// Constructor or initialisator. 
// 	function __construct() {
//  		global $dbsettings;
//  		// Store the connection details in variables
//  		$servername = $dbsettings['servername'];
//  		$username = $dbsettings['username'];
//  		$password = $dbsettings['password'];
//  		$dbname = $dbsettings['dbname'];
//  		
//  		// We now connect to the database
//  		$con = @mysql_connect($servername,$username,$password);
//  		
//  		// if connection failed
//  		if (!$con){
//  			// To allow running on Nayar laptop wihtout need to change password everytime
//  			$con = @mysql_connect($servername,$username,"admin");
//  			if (!$con) {
//  				die("Cannot connect to server".mysql_error());
//  			}
//  		}
//  		
//  		// Select the database
//  		$db_selected = mysql_select_db($dbname,$con);
//  		
//  		// If database not present
//  		if (!$db_selected) {
//  			//Create the database
//  			//mysql_query("CREATE DATABASE {$dbname}",$con);
//  			//header('Location: '.ROOTURL.'inc/install.php');
//  		}
// 	}
	
	function query($string) {
		//echo $string;
		$query = mysql_query($string);
		if(!$query) {
			echo "<br />";
			die($string.mysql_error());
		}
		return $query;
	}
	
	function insert($table,$array)
	{
		$fields = '';
		$values = '';
		foreach($array as $key=>$value) {
			$fields .= $key;
			$fields .= ',';
			$values .= "'".mysql_real_escape_string($value)."'"; // Escape any quote so as to avoid SQL injections
			$values .= ',';
		}
		$fields = substr_replace($fields,"",-1);
		$values = substr_replace($values,"",-1);
		//echo "INSERT INTO {$table} ({$fields}) VALUES ({$values})" ;
		$this->query("INSERT INTO {$table} ({$fields}) VALUES ({$values}) ");
	}
	
	function update($table,$id,$array,$field = 'id'){
		//print_r($array);
		$sets = '';
		foreach($array as $key=>$value) {
			$sets .= "{$key} = '".mysql_real_escape_string($value)."'"; // Escape any quote so as to avoid SQL injections
			$sets .= ',';
		}
		$sets = substr_replace($sets,"",-1);
		$sql = "UPDATE {$table} SET {$sets} WHERE ". ((isset($field)) ? $field : 'id') . " = {$id};";
		//echo $sql;
		$this->query($sql);
	}
	
	function select_all($table){
		global $db;
		$all = array();
		$sql = "SELECT * FROM {$table}";
		$results = $db->query($sql);
		while ($result = mysql_fetch_array($results)) {
			$all[] = new Client($result);
		}
		return $all;
	}
}