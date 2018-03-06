<?php

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

require('../../vendor/autoload.php');

$ini_array = parse_ini_file("../../update/config.ini", true);

$mongo_url = $ini_array['Mongo']['url'];

//$connection = new MongoClient($mongo_url);
$connection = new MongoDB\Client($mongo_url);

//$dbname = $connection->selectDB($ini_array['Mongo']['db_accommodation']);
$db_name = $ini_array['Mongo']['db_accommodation'];
$collection_name = $ini_array['Mongo']['collection'];
//$collection = $dbname->$ini_array['Mongo']['collection'];
$collection = $connection->$db_name->$collection_name;

$result=iterator_to_array($collection->find());	

include('../../api/config.php');
for($i = 0; $i < count($result); $i++)
{
	$hotel_username = $result[$i]['_id'];
	
	// genero una password di 8 caratteri
	$hotel_password = randomPassword();
	echo $hotel_username." ".$hotel_password."<br>";
	
	// memorizzo username e password su db
	// TODO memorizzare la hash della password
	
	$sql = "INSERT INTO utenti(username,password) VALUES ('$hotel_username','$hotel_password')";
	$risultati = mysqli_query($connessione, $sql);
	
}	
mysqli_close($connessione);

?>