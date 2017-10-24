<?php

header('Content-Type: application/json');

require('../vendor/autoload.php');

$ini_array = parse_ini_file("../update/config.ini", true);

function exists_field($field)
{
	$bool = $field === 'true' ? true: false;
	return array('$exists' => $bool);
}

function min_max_field($field, &$query)
{
	if(isset($_REQUEST['min_'.$field]))
		$query[$field]['$gte'] = floatval($_REQUEST['min_'.$field]);
	if(isset($_REQUEST['max_'.$field]))
		$query[$field]['$lte'] = floatval($_REQUEST['max_'.$field]);
}

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
		
		if(isset($_REQUEST['region']))
			$query['region'] = $_REQUEST['region'];
		if(isset($_REQUEST['province']))
			$query['province'] = $_REQUEST['province'];
		if(isset($_REQUEST['city']))
			$query['city'] = new MongoDB\BSON\Regex($_REQUEST['city'], 'mi');
			
		if(!isset($_REQUEST['region']) && !isset($_REQUEST['city']) && !isset($_REQUEST['province']) && isset($_REQUEST['place']))
		{
			$place = new MongoDB\BSON\Regex($_REQUEST['place'], 'mi');
			$query['$or'] = array(array('region' => $place), array('city' => $place));
		}
		min_max_field('beds', $query);
		min_max_field('latitude', $query);
		min_max_field('longitude', $query);
		
		$fields_list = $ini_array['Sources'][$category."_field"];
		$excluded_fields = array('province', 'region','beds','city');
		for($i = 0; $i < count($fields_list); $i++)
			if(isset($_REQUEST[$fields_list[$i]]) && ! in_array($fields_list[$i], $excluded_fields))
				$query[$fields_list[$i]] = exists_field($_REQUEST[$fields_list[$i]]);
		//var_dump($query);
			
		echo json_encode(iterator_to_array($collection->find($query)));
	}
	else 
	{
		echo json_encode(array('status' => 'error', 'details' => "Unrecognized category $category"));
	}
}
else
	echo json_encode(array('status' => 'error', 'details' => 'Missing parameter category. Category can be one of the following: accommodation'));
	


?>
