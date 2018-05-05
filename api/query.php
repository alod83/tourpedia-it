<?php

header('Content-Type: application/json');

//require('../vendor/autoload.php');
include('../libraries/mongodb.php');
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
		
		if(isset($_REQUEST['region']))
			$query['region'] = $_REQUEST['region'];
		if(isset($_REQUEST['province']))
			$query['province'] = $_REQUEST['province'];
		if(isset($_REQUEST['city']))
			$query['city'] = new MongoDB\BSON\Regex($_REQUEST['city'], 'mi');
			
		if(!isset($_REQUEST['region']) && !isset($_REQUEST['city']) && !isset($_REQUEST['province']) && !isset($_REQUEST['_id']) && isset($_REQUEST['place']))
		{
			$place = new MongoDB\BSON\Regex('^'.$_REQUEST['place'].'( )*$', 'mi');
			$query['$or'] = array(array('region' => $place), array('city' => $place));
		}
		min_max_field('beds', $query);
		min_max_field('latitude', $query);
		min_max_field('longitude', $query);
		not_null('latitude', $query);
		not_null('longitude', $query);
		
		$fields_list = $ini_array['Sources'][$category."_field"];
		$excluded_fields = array('province', 'region','beds','city');
		$lon_field=count($fields_list);
		for($i = 0; $i < $lon_field; $i++)
			if(isset($_REQUEST[$fields_list[$i]]) && ! in_array($fields_list[$i], $excluded_fields))
				$query[$fields_list[$i]] = exists_field($_REQUEST[$fields_list[$i]]);
		//var_dump($query);
		//$result=iterator_to_array($collection->find($query));//
		//$result=iterator_to_array($collection->find($query, $options));
		if(isset($_REQUEST['_id'])){
			$query['_id'] = $_REQUEST['_id'];
			$fields = array('name' => 1);
			$result=iterator_to_array($connection->find_with_sort($query, $fields));
		}else{
			$fields = array('name' => 1, 'latitude' => 1, 'longitude' => 1, 'address' => 1);
			$result=iterator_to_array($connection->find_with_projection_and_sort($query, $fields));
		}
		$lunghezza=count($result);
		for($i=0; $i<$lunghezza; $i++){
			foreach($result[$i] as $k=>$v){
				if($k=="number of stars"){
					$result[$i]["number of stars"] = intval(trim(preg_replace('/[^0-9]/', '',$v)));
				}
				if($k=="lat"){
					if (strpos($v, '.') !== false) {
						$result[$i]["latitude"] = floatval($v);
					}else{
						$result[$i]["latitude"] = floatval(substr_replace(trim(preg_replace('/[^A-Za-z0-9\-]/','',$v)), '.', 2, 0));
					}
				}
				if($k=="lon" or $k=="lng"){
					if (strpos($v, '.') !== false) {
						$result[$i]["longitude"] = floatval($v);
					}else{
						$result[$i]["longitude"] = floatval(substr_replace(trim(preg_replace('/[^A-Za-z0-9\-]/','',$v)), '.', 2, 0));
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
				if($k=="region"){
					
					if($v=="Lombardia"){
						if($category == "attraction"){
							$result[$i]["name"] = $result[$i]["description"]." di ".$result[$i]["city"];
						}
					}
				}
				if($k=="address"){
					$result[$i][$k] = str_replace("ï¿½ï¿½","U'",$v);
				}
				if($k=="name"){
					if($v =="Biblioteca Comunale"){
						$result[$i]["name"] = "Biblioteca Comunale di ".$result[$i]["city"];
					}else{
						$result[$i]["name"]= ucwords(strtolower(str_replace("Ä__","'",$v)));
					}
				}else{
					if($k=="province" or $k=="city"){
						$result[$i][$k] = substr(strtoupper(trim(preg_replace('/[^ .A-Za-z0-9\-]/', '',$v))),0,1).substr(strtolower(trim(preg_replace('/[^ .A-Za-z0-9\-\']/', '',$v))),1);
					}else if ($k=="web site"){
						$result[$i][$k] = trim(str_replace('<p>',"",str_replace('<a target="_blank">',"",str_replace("</a>","",str_replace("</p>","",$v)))));
					}
				}
			}
		}
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
