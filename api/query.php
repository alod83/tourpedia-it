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
			$place = new MongoDB\BSON\Regex('^'.$_REQUEST['place'].'( )*$', 'mi');
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
		$result=iterator_to_array($collection->find($query));
		for($i=0; $i<count($result); $i++){
			foreach($result[$i] as $k=>$v){
				if($k=="number of stars"){
					$result[$i]["stars"] = (int)trim(preg_replace('/[^0-9]/', '',$v));
					unset($result[$i][$k]);
				}
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
				if($k=="region"){
					if($v=="Trentino"){
						$result[$i]["region"]="Trentino-alto adige";
					}
					if($v=="Lombardia"){
						if($category == "attraction"){
							$result[$i]["name"] = $result[$i]["description"]." di ".$result[$i]["city"];
						}
					}
				}
				if($k=="latitude"){
					if(($k>48) or ($k <35)){
						if(($result[$i]["longitude"]>24) or ($result[$i]["longitude"]<6)){
							$temp=$result[$i]["latitude"];
							$result[$i]["latitude"]=$result[$i]["longitude"];
							$result[$i]["longitude"]=$temp;
						}
					}
				}
				if($k=="name"){
					if($v =="Biblioteca Comunale"){
						$result[$i]["name"] = "Biblioteca Comunale di ".$result[$i]["city"];
					}else{
						$result[$i]["name"]= ucwords(strtolower(str_replace("Ä__","'",$v)));
					}
				}else{
					if($k=="province" or $k=="city"){
						$result[$i][$k]=trim(preg_replace('/[^ .A-Za-z0-9\-]/', '',$v));
					}else if ($k=="web site"){
						$result[$i][$k]=trim(str_replace('<p>',"",str_replace('<a target="_blank">',"",str_replace("</a>","",str_replace("</p>","",$v)))));
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
