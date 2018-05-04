<?php

class MyMongoClient
{
	var $connection;
	var $ini_array;
	var $dbname;
	var $collection;
	
	public function __construct($mongo_url,$ini_array)
	{
		$this->connection = new MongoClient($mongo_url);
		$this->ini_array = $ini_array;
	}
	
	public function selectDB($dbname)
	{
		$this->dbname = $this->connection->selectDB($this->ini_array['Mongo'][$dbname]);
		$collection_name = $this->ini_array['Mongo']['collection'];
		$this->collection = $this->dbname->$collection_name;
	}
	
	public function find($query, $fields=null){
		if(!is_null($fields))
			return $this->collection->find($query,$fields);
		return $this->collection->find($query);
	}
	
	public function find_with_projection($query, $fields=null)
	{
		return $this->find($query,$fields);
	}
}


?>