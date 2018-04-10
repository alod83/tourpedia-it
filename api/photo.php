<?php 

session_start();
header('Content-Type: application/json; charset=utf-8');
require('../vendor/autoload.php');
$ini_array = parse_ini_file("../update/config.ini", true);
$mongo_url = $ini_array['Mongo']['url'];
//$connection = new MongoClient($mongo_url);
$connection = new MongoDB\Client($mongo_url);
//$dbname = $connection->selectDB($ini_array['Mongo']['db_accommodation']);
$db_name = $ini_array['Mongo']['db_accommodation'];
$collection_name = $ini_array['Mongo']['collection'];
//$collection = $dbname->$ini_array['Mongo']['collection'];
$collection = $connection->$db_name->$collection_name;
$destination= '../app/photos';
$destination2= 'photos';
$tmp_name = $_FILES["inputFile"]["tmp_name"];
$name = $_FILES["inputFile"]["name"];
$allowed =  array('jpeg' ,'png' ,'jpg');
$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
if(in_array($ext,$allowed)) {
	for($i=0; $i<count($allowed); $i++){
		$filename='../app/photos/'.$_SESSION['_id'].'.'.$allowed[$i];
		if(file_exists($filename)){
			unlink($filename);
		}
	}
	move_uploaded_file($tmp_name, "$destination/".$_SESSION['_id'].".$ext");
	$setdata = array('$set' => array('photo' => "$destination2/".$_SESSION['_id'].".$ext"));
	$collection->updateOne(array("_id" => $_SESSION['_id']), $setdata);
	echo(json_encode(1));
}else{
	$setdata = array('$set' => array('photo' => null));
	$collection->updateOne(array("_id" => $_SESSION['_id']), $setdata);
	echo(json_encode(2));
}

?>