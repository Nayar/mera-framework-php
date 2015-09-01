<?php
/**
	Copyright 2015 by Nayar Joolfoo
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

if(!defined ('ROOTDIR'))
	define('ROOTDIR',dirname(dirname(dirname(__FILE__)))."/");

abstract class MeraEntity {
	public static $TABLE_NAME = '';
	public static $PRIMARY_KEY = 'id';
	// We define the items forming our form
	private static $form_items = array();
	
	public $attributes = array();
	
	
	public function __construct($attributes = null){
		if($attributes)
			$this->attributes = $attributes;
	}
	
	public static function getAll($fields = '*', $extra = ''){
		global $db;
		$all = array();
		$sql = 'SELECT '.$fields.' FROM '. static::$TABLE_NAME. ' ' . $extra;
		$results = $db->query($sql);
		while ($result = $results->fetch_assoc()) {
			$all[] = new static($result);
		}
		return $all;
	}
	
	public static function totalCount($fields = '*', $extra = ''){
		global $db;
		$all = array();
		
		$sql = 'SELECT count('.$fields.') as c FROM '. static::$TABLE_NAME. ' ' . $extra;
		$results = $db->query($sql);
		while ($result = $results->fetch_assoc()) {
			return $result['c'];
		}
		return 0;
	}
	
	public static function add($entity){
		global $db;
		//print_r($entity);
		$db->insert(static::$TABLE_NAME,$entity->attributes);
	}

	public static function delete($current){
		global $db;
		$sql = 'DELETE FROM '.static::$TABLE_NAME.' WHERE id = '.$current.';';
		$db->query($sql);
		//echo $sql;
	}
	
	public static function update($id , $arr){
		self::edit($id,$arr);
	}

	public static function edit($id , $arr){
		global $db;
		$db->update(static::$TABLE_NAME,$id,$arr);
	}
	
	public static function getById($id){
		global $db;
		$all = array();
		$sql = 'SELECT * FROM '.static::$TABLE_NAME.' WHERE '.static::$PRIMARY_KEY.' ='.$id.';';
		$results = $db->query($sql);
		while ($result = $results->fetch_assoc()) {
			$all[] = new static($result);
		}
		if(isset($all[0]))
			return $all[0]; // Return first record if found
		return null;	
	}
    
    public static function getByField($key,$value){
        global $db;
		$all = array();
		$sql = 'SELECT * FROM '.static::$TABLE_NAME.' WHERE '.$key.' = \''.$value.'\';';
		$results = $db->query($sql);
		while ($result = $results->fetch_assoc()) {
			$all[] = new static($result);
		}
        return $all; // Return first record if found
    }
    
    public static function getByFields($fields){
        global $db;
        //print_r($fields);
        $all = array();
        $sql = 'SELECT * FROM '.static::$TABLE_NAME.' WHERE ';
        foreach($fields as $key => $value){
            $sql .= $key.' = \''.$value.'\' AND ' ;
        }
        $sql = rtrim($sql,'AND ');
        $sql .= ';';
        $results = $db->query($sql);
        while ($result = $results->fetch_assoc()) {
                $all[] = new static($result);
        }
        return $all; // Return first record if found
    }
    
    public static function getInstallSQL(){
        if(!isset(static::$TEMPLATE)){
            return null;
        }
        $sql = '
        CREATE TABLE '.static::$TABLE_NAME . ' (
        ';
        foreach(static::getTemplate() as $item){
            $sql .= $item['name'] . ' ' . $item['mysql_type'] . ',';
        }
        $sql = rtrim($sql,',');
        $sql .= ');';
        
        return $sql;
    }
		



}