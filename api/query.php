<?php

header('Content-Type: application/json');

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

$connection = new MongoClient($mongo_url);
if(isset($_REQUEST['category']))
{
	$category = $_REQUEST['category'];
	if(isset($ini_array['Mongo']['db_'.$category]))
	{
		$dbname = $connection->selectDB($ini_array['Mongo']['db_'.$category]);
		$collection = $dbname->$ini_array['Mongo']['collection'];
		
		$query = array();
		if(isset($_REQUEST['region']))
			$query['region'] = $_REQUEST['region'];
		if(isset($_REQUEST['province']))
			$query['province'] = $_REQUEST['province'];
		min_max_field('beds', $query);
		min_max_field('latitude', $query);
		min_max_field('longitude', $query);
		
		$fields_list = $ini_array['Sources'][$category."_field"];
		$excluded_fields = array('province', 'region','beds');
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