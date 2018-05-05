<?php

header('Content-Type: application/json');

//require('../vendor/autoload.php');
include('../libraries/mongodb.php');
$ini_array = parse_ini_file("../update/config.ini", true);

function min_max_field($field, &$query)
{
	if(isset($_REQUEST['min_'.$field]))
		$query[$field]['$gte'] = floatval($_REQUEST['min_'.$field]);
	if(isset($_REQUEST['max_'.$field]))
		$query[$field]['$lte'] = floatval($_REQUEST['max_'.$field]);
}

function not_null($field, &$query)
{
	if(isset($_REQUEST['not_null_'.$field]))
		$query[$field]['$ne'] = null;
}

$mongo_url = $ini_array['Mongo']['url'];

//$connection = new MongoClient($mongo_url);
//$connection = new MongoDB\Client($mongo_url);
$connection = new MyMongoClient($mongo_url, $ini_array);
if(isset($_REQUEST['category']))
{
	$category = $_REQUEST['category'];
	if(isset($ini_array['Mongo']['db_'.$category]))
	{
		//$dbname = $connection->selectDB($ini_array['Mongo']['db_'.$category]);
		//$db_name = $ini_array['Mongo']['db_'.$category];
		//$collection_name = $ini_array['Mongo']['collection'];
		//$collection = $dbname->$ini_array['Mongo']['collection'];
		//$collection = $connection->$db_name->$collection_name;
		$connection->selectDB('db_'.$category);
		$query = array();
		
		if(isset($_REQUEST['country'])){
			$query['country'] = $_REQUEST['country'];
			min_max_field('latitude', $query);
			min_max_field('longitude', $query);
			not_null('latitude', $query);
			not_null('longitude', $query);
		}
		
		$result=$connection->collection->count($query);//
		
		echo json_encode($result);
	}
	else 
	{
		echo json_encode(array('status' => 'error', 'details' => "Unrecognized category $category"));
	}
}
else
	echo json_encode(array('status' => 'error', 'details' => 'Missing parameter category. Category can be one of the following: accommodation'));
	


?>