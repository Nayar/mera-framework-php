<?php

abstract class Mera_Entity {
	public static $TABLE_NAME = '';
	// We define the items forming our form
	public $form_items = array();
	
	public $attributes = array();
	
	
	public function __construct($attributes){
		$this->attributes = $attributes;
	}
	
	public static function getAll(){
		global $db;
		$all = array();
		
		$sql = 'SELECT * FROM '. static::$TABLE_NAME;
		$results = $db->query($sql);
		while ($result = mysql_fetch_array($results)) {
			$all[] = new static($result);
		}
		return $all;
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
		echo $sql;
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
		$sql = 'SELECT * FROM '.static::$TABLE_NAME.' WHERE id ='.$id.';';
		$results = $db->query($sql);
		while ($result = mysql_fetch_array($results)) {
			$all[] = new static($result);
		}
		if(isset($all[0]))
			return $all[0]; // Return first record if found
		return null;	
	}
		



}