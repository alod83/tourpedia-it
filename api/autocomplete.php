<?php
//CORREGGERE
header('Content-Type: application/json; charset=utf-8');
$array=array();
//require('../vendor/autoload.php');
include('../libraries/mongodb_old.php');
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
$mongo_obj_acco = new MyMongoClient($mongo_url, $ini_array);
$mongo_obj_acco->selectDB('db_accommodation');

$mongo_obj_attr = new MyMongoClient($mongo_url, $ini_array);
$mongo_obj_attr->selectDB('db_attraction');

//$connection = new MongoClient($mongo_url);
////$connection = new MongoDB\Client($mongo_url);
//$dbname = $connection->selectDB($ini_array['Mongo']['db_accommodation']);
////$db_name = $ini_array['Mongo']['db_accommodation'];
////$db_name2 = $ini_array['Mongo']['db_attraction'];


//$collection_name = $ini_array['Mongo']['collection'];
//$collection = $dbname->$ini_array['Mongo']['collection'];
//$collection2 = $dbname2->$ini_array['Mongo']['collection'];

//$collection = $connection->$db_name->$collection_name;
//$collection2 = $connection->$db_name2->$collection_name;
$query = array();
min_max_field('latitude', $query);
min_max_field('longitude', $query);
not_null('latitude', $query);
not_null('longitude', $query);
/////$fields = array('projection' => array('region' => 1, 'city' => 1));
$fields = array('region' => 1, 'city' => 1);
////$result=iterator_to_array($collection->find($query ,$fields));
////$result2=iterator_to_array($collection2->find($fields));

$result = iterator_to_array($mongo_obj_acco->find_with_projection($query, $fields));
$result2 = iterator_to_array($mongo_obj_attr->find($fields));
$r=array_merge($result,$result2);
$array=array();
$regioni=array();
$citta=array();
foreach($r as $record)
{
	$regioni[$record['region']] = true;
	if(isset($record['city'])){
		$citta[$record['city']] = true;
	}
}
foreach ($regioni as $j=>$v){
	array_push($array, substr(strtoupper(trim(preg_replace('/[^ .A-Za-z0-9\-]/', '',$j))),0,1).substr(strtolower(trim(preg_replace('/[^ .A-Za-z0-9\-\']/', '',$j))),1));
}
foreach ($citta as $k=>$v){
	array_push($array, substr(strtoupper(trim(preg_replace('/[^ .A-Za-z0-9\-]/', '',$k))),0,1).substr(strtolower(trim(preg_replace('/[^ .A-Za-z0-9\-\']/', '',$k))),1));
}

echo(json_encode($array));
?>