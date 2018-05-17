<?php 
session_start();
header('Content-Type: application/json; charset=utf-8');
$ar = json_decode($_REQUEST['ar'], true);
//$array=array();
//require('../vendor/autoload.php');
include('../libraries/mongodb_old.php');
$ini_array = parse_ini_file("../update/config.ini", true);
$mongo_url = $ini_array['Mongo']['url'];
//$connection = new MongoClient($mongo_url);
//$connection = new MongoDB\Client($mongo_url);
//$dbname = $connection->selectDB($ini_array['Mongo']['db_accommodation']);
//$db_name = $ini_array['Mongo']['db_accommodation'];
//$collection_name = $ini_array['Mongo']['collection'];
//$collection = $dbname->$ini_array['Mongo']['collection'];
//$collection = $connection->$db_name->$collection_name;
$connection = new MyMongoClient($mongo_url, $ini_array);
$connection->selectDB('db_accommodation');
if(isset($ar)){
	$newdata = array();
	if(isset($ar["name"])){
		$newdata['name'] = $ar["name"];
		$_SESSION['name'] = $ar["name"];
	}
	if(isset($ar["description"])){
		$newdata['description'] = $ar["description"];
		$_SESSION['description'] = $ar["description"];
	}
	if(isset($ar["address"])){
		$newdata['address'] = $ar["address"];
		$_SESSION['address'] = $ar["address"];
	}
	if(isset($ar["number of stars"])){
		$newdata['number of stars'] = intval($ar["number of stars"]);
		$_SESSION['number of stars'] = intval($ar["number of stars"]);
	}
	if(isset($ar["email"])){
		$newdata['email'] = $ar["email"];
		$_SESSION['email'] = $ar["email"];
	}
	if(isset($ar["telephone"])){
		$newdata['telephone'] = $ar["telephone"];
		$_SESSION['telephone'] = $ar["telephone"];
	}
	if(isset($ar["country"])){
		$newdata['country'] = $ar["country"];
		$_SESSION['country'] = $ar["country"];
	}
	if(isset($ar["region"])){
		$newdata['region'] = $ar["region"];
		$_SESSION['region'] = $ar["region"];
	}
	if(isset($ar["province"])){
		$newdata['province'] = $ar["province"];
		$_SESSION['province'] = $ar["province"];
	}
	if(isset($ar["postal-code"])){
		$newdata['postal-code'] = $ar["postal-code"];
		$_SESSION['postal-code'] = $ar["postal-code"];
	}
	if(isset($ar["city"])){
		$newdata['city'] = $ar["city"];
		$_SESSION['city'] = $ar["city"];
	}
	if(isset($ar["latitude"])){
		$newdata['latitude'] = floatval($ar["latitude"]);
		$_SESSION['latitude'] = $ar["latitude"];
	}
	if(isset($ar["longitude"])){
		$newdata['longitude'] = floatval($ar["longitude"]);
		$_SESSION['longitude'] = $ar["longitude"];
	}
	if(isset($ar["locality"])){
		$newdata['locality'] = $ar["locality"];
		$_SESSION['locality'] = $ar["locality"];
	}
	if(isset($ar["hamlet"])){
		$newdata['hamlet'] = $ar["hamlet"];
		$_SESSION['hamlet'] = $ar["hamlet"];
	}
	if(isset($ar["fax"])){
		$newdata['fax'] = $ar["fax"];
		$_SESSION['fax'] = $ar["fax"];
	}
	if(isset($ar["opening period"])){
		$newdata['opening period'] = $ar["opening period"];
		$_SESSION['opening period'] = $ar["opening period"];
	}
	if(isset($ar["facilities"])){
		$newdata['facilities'] = $ar["facilities"];
		$_SESSION['facilities'] = $ar["facilities"];
	}
	if(isset($ar["web site"])){
		$newdata['web site'] = $ar["web site"];
		$_SESSION['web site'] = $ar["web site"];
	}
	if(isset($ar["beds"])){
		$newdata['beds'] = $ar["beds"];
		$_SESSION['beds'] = $ar["beds"];
	}
	if(isset($ar["rooms"])){
		$newdata['rooms'] = $ar["rooms"];
		$_SESSION['rooms'] = $ar["rooms"];
	}
	if(isset($ar["suites"])){
		$newdata['suites'] = $ar["suites"];
		$_SESSION['suites'] = $ar["suites"];
	}
	if(isset($ar["facebook"])){
		$newdata['facebook'] = $ar["facebook"];
		$_SESSION['facebook'] = $ar["facebook"];
	}
	if(isset($ar["instagram"])){
		$newdata['instagram'] = $ar["instagram"];
		$_SESSION['instagram'] = $ar["instagram"];
	}
	if(isset($ar["twitter"])){
		$newdata['twitter'] = $ar["twitter"];
		$_SESSION['twitter'] = $ar["twitter"];
	}
	if(isset($ar["category"])){
		$newdata['category'] = $ar["category"];
		$_SESSION['category'] = $ar["category"];
	}
	if(isset($ar["languages"])){
		$newdata['languages'] = $ar["languages"];
		$_SESSION['languages'] = $ar["languages"];
	}
	$newdata['verified'] = true;
	$setdata = array('$set' => $newdata);
	$connection->updateOne(array("_id" => $_SESSION['_id']), $setdata);
	//$query = array('_id' => $_SESSION['_id']);
	//$result=iterator_to_array($connection->find($query));
	//$array=$result;
	$a='../app/statistiche.html';
}
//echo(json_encode($array));
echo(json_encode($a));
?>