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
	
	public $mysqli;
	
	// Constructor or initialisator. 
	function __construct() {
 		global $dbsettings,$settings;
 		
 		// We now connect to the database
 		//$con = @mysql_connect($servername,$username,$password);
 		
 		$this->mysqli = new mysqli($dbsettings['db_host'],$dbsettings['db_username'],$dbsettings['db_password'],$dbsettings['db_name']);
 		if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
        }
	}
	
	function query($string) {
        global $settings;
		//echo $string;
		$query = $this->mysqli->query($string);
		if(!$query) {
			//echo "Failed Query: .\n\n";
			while(@!mysqli_ping($this->mysqli)){
                echo "sleeping\n";
                //sleep(30);
                $this->mysqli = new mysqli($settings['db_host'],$settings['db_username'],$settings['db_password'],$settings['db_name']);
			}
			//echo "Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
			
			$query = $this->mysqli->query($string);
			//die($string.mysql_error());
		}
		return $query;
	}
	
	function insert($table,$array)
	{
		$fields = '';
		$values = '';
		foreach($array as $key=>$value) {
			$fields .= '`'.$key.'`';
			$fields .= ',';
			$values .= "'".$this->mysqli->real_escape_string($value)."'"; // Escape any quote so as to avoid SQL injections
			$values .= ',';
		}
		$fields = substr_replace($fields,"",-1);
		$values = substr_replace($values,"",-1);
		//echo "INSERT INTO {$table} ({$fields}) VALUES ({$values})" ;
		$this->query("INSERT INTO {$table} ({$fields}) VALUES ({$values}) ");
	}
	
	function insert_batch($table,$items){
		$fields = '';
		$values = '';
		if(!isset($items[0])){
			return false;
		}
		foreach($items[0] as $key=>$value) {
			$fields .= '`'.$key.'`';
			$fields .= ',';
		}
		foreach($items as $item){
            if($item == null){
                continue;
            }
			$values .= '(';
			foreach($item as $key=>$value) {
				$values .= "'".$this->mysqli->real_escape_string($value)."',";
			}
			$values = substr_replace($values,"",-1);
			$values .= '),';
		}
		$fields = substr_replace($fields,"",-1);
		$values = substr_replace($values,"",-1);
		//echo "INSERT INTO {$table} ({$fields}) VALUES {$values}\n" ;
		$this->query("INSERT INTO {$table} ({$fields}) VALUES {$values} ");
	}
	
	function update($table,$id,$array,$field = 'id'){
		//print_r($array);
		$sets = '';
		foreach($array as $key=>$value) {
			$sets .= "{$key} = '".$this->mysqli->real_escape_string($value)."'"; // Escape any quote so as to avoid SQL injections
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
		while ($result = $results->fetch_assoc()) {
			$all[] = new Client($result);
		}
		return $all;
	}
} 
