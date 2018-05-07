<?php 

header('Content-Type: application/json; charset=utf-8');
$latitudine = $_REQUEST['lat'];
$longitudine = $_REQUEST['lon'];
$array=array();

//require('../vendor/autoload.php');
include('../libraries/mongodb.php');
$ini_array = parse_ini_file("../update/config.ini", true);
$mongo_url = $ini_array['Mongo']['url'];
//$connection = new MongoClient($mongo_url);
//$connection = new MongoDB\Client($mongo_url);
//$dbname = $connection->selectDB($ini_array['Mongo']['db_accommodation']);
$connection = new MyMongoClient($mongo_url, $ini_array);
$connection->selectDB('db_attraction');
//$db_name = $ini_array['Mongo']['db_attraction'];
//$collection_name = $ini_array['Mongo']['collection'];
//$collection = $dbname->$ini_array['Mongo']['collection'];
//$collection = $connection->$db_name->$collection_name;
//$query = array('$and' => array( array('latitude' => array( '$gt' => $latitudine-0.001, '$lt' => $latitudine+0.001 )), array('longitude' => array( '$gt' => $longitudine-0.001, '$lt' => $longitudine+0.001 )) ));
$query = array( 'latitude' => array( '$gt' => $latitudine-0.001, '$lt' => $latitudine+0.001 ), 'longitude' => array( '$gt' => $longitudine-0.001, '$lt' => $longitudine+0.001 ));
$result=iterator_to_array($connection->find($query));
if(count($result) == 0){
	//$query = array('$and' => array( array('latitude' => array( '$gt' => $latitudine-0.01, '$lt' => $latitudine+0.01 )), array('longitude' => array( '$gt' => $longitudine-0.01, '$lt' => $longitudine+0.01 )) ));
	$query = array( 'latitude' => array( '$gt' => $latitudine-0.01, '$lt' => $latitudine+0.01 ), 'longitude' => array( '$gt' => $longitudine-0.01, '$lt' => $longitudine+0.01 ));
	$result=iterator_to_array($connection->find($query));
}
if(count($result) > 10){
	for($i=0; $i<10; $i++){
		array_push($array , $result[$i]);
	}
}else{
	for($i=0; $i<count($result); $i++){
		array_push($array , $result[$i]);
	}
}
echo(json_encode($array));
?>