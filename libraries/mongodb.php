<?php

require('../vendor/autoload.php');

class MyMongoClient
{
	var $connection;
	var $ini_array;
	var $dbname;
	var $collection;
	
	public function __construct($mongo_url,$ini_array)
	{
		$this->connection = new MongoDB\Client($mongo_url);
		$this->ini_array = $ini_array;
	}
	
	public function selectDB($dbname)
	{
		$this->dbname = $this->ini_array['Mongo'][$dbname];
		$db_name = $this->dbname;
		$collection_name = $this->ini_array['Mongo']['collection'];
		/*$this->collection = $connection->$dbname->$collection_name;*/
		$this->collection = $this->connection->$db_name->$collection_name;
	}
	
	public function find($query, $fields=null){
		if(!is_null($fields))
			return $this->collection->find($query,$fields);
		return $this->collection->find($query);
	}
	
	public function find_with_projection($query, $fields_to_include=null)
	{
		$fields = array('projection' => $fields_to_include);
		return $this->find($query, $fields);
	}
	
	public function find_with_sort($query, $fields_to_include=null)
	{
		$fields = array('sort' => $fields_to_include);
		return $this->find($query, $fields);
	}
	
	public function find_with_projection_and_sort($query, $fields_to_include=null)
	{
		$fields = array('projection' => $fields_to_include, 'sort' => array('name' => 1));
		return $this->find($query, $fields);
	}
	
	public function build_mongo_regex($string, $option)
	{
		return new MongoDB\BSON\Regex($string, $option);
	}
}


?>