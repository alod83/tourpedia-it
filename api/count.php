<?php

header('Content-Type: application/json');

require('../vendor/autoload.php');

$ini_array = parse_ini_file("../update/config.ini", true);

function exists_field($field)
{
	$bool = $field === 'false' ? false: true;
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
		
		if(isset($_REQUEST['country']))
			$query['country'] = $_REQUEST['country'];
		
		min_max_field('beds', $query);
		min_max_field('latitude', $query);
		min_max_field('longitude', $query);
		
		$fields_list = $ini_array['Sources'][$category."_field"];
		$excluded_fields = array('country','province', 'region','beds','city');
		for($i = 0; $i < count($fields_list); $i++)
			if(isset($_REQUEST[$fields_list[$i]]) && ! in_array($fields_list[$i], $excluded_fields))
				$query[$fields_list[$i]] = exists_field($_REQUEST[$fields_list[$i]]);
		//var_dump($query);
		
		$result=iterator_to_array($collection->find($query));//
		$counter=0;
		for($i=0; $i<count($result); $i++){
			foreach($result[$i] as $k=>$v){
				if($k=="lat"){
					if (strpos($v, '.') !== false) {
						$result[$i]["latitude"] = (float)$v;
					}else{
						$result[$i]["latitude"] = (float)substr_replace(trim(preg_replace('/[^A-Za-z0-9\-]/','',$v)), '.', 2, 0);
					}
				}
				if($k=="lon" or $k=="lng"){
					if (strpos($v, '.') !== false) {
						$result[$i]["longitude"] = (float)$v;
					}else{
						$result[$i]["longitude"] = (float)substr_replace(trim(preg_replace('/[^A-Za-z0-9\-]/','',$v)), '.', 2, 0);
					}
				}
				if($k=="Italy"){
					if(($result[$i]["latitude"] >48) or ($result[$i]["latitude"] <35)){
						if(($result[$i]["longitude"]>24) or ($result[$i]["longitude"]<6)){
							$temp=$result[$i]["latitude"];
							$result[$i]["latitude"]=$result[$i]["longitude"];
							$result[$i]["longitude"]=$temp;
						}
					}
				}
				if($k=="latitude"){
					if(!empty($k)){
						if($result[$i]["longitude"]){
							if(!empty($result[$i]["longitude"])){
								$counter+=1;
							}
						}
					}
				}
			}
		}
		echo json_encode($counter);
	}
	else 
	{
		echo json_encode(array('status' => 'error', 'details' => "Unrecognized category $category"));
	}
}
else
	echo json_encode(array('status' => 'error', 'details' => 'Missing parameter category. Category can be one of the following: accommodation'));
	


?>