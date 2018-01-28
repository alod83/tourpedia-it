<?php

header('Content-Type: application/json');

require('../vendor/autoload.php');

$ini_array = parse_ini_file("../update/config.ini", true);

$mongo_url = $ini_array['Mongo']['url'];

//$connection = new MongoClient($mongo_url);
$connection = new MongoDB\Client($mongo_url);
if(isset($_REQUEST['category']))
{
	$category = $_REQUEST['category'];
	if(isset($ini_array['Mongo']['db_'.$category]))
	{
		//$dbname = $connection->selectDB($ini_array['Mongo']['db_'.$category]);
		$db_name = $ini_array['Mongo']['db_'.$category];
		$collection_name = $ini_array['Mongo']['collection'];
		//$collection = $dbname->$ini_array['Mongo']['collection'];
		$collection = $connection->$db_name->$collection_name;
		$query = array();
		
		if(isset($_REQUEST['country']))
			$query['country'] = $_REQUEST['country'];
		
		
		$result=$collection->count($query);//
		
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