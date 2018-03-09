<?php

// prende tramite GET username e password
$nome_utente = $_GET["User"];
$password_utente = $_GET["Pass"];
$v;
include('config.php');
// verifico se username e password sono settati
if(isset($nome_utente)){
	if(isset($password_utente)){
		// verifico se username e password matchano con quelli sul database
		$sql = "SELECT COUNT(username) AS N FROM `utenti` WHERE username=\"$nome_utente\" AND utenti.password=\"$password_utente\"";
		$risultati = mysqli_query($connessione, $sql);
		$r = mysqli_fetch_assoc($risultati);
		$v = floatval($r["N"]);
		if($v == 1){
			echo("$nome_utente e $password_utente ci sono!");
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
			$result=iterator_to_array($collection->find());
			for($i = 0; $i < count($result); $i++){
				if($result[$i]['_id']== $nome_utente){
					header('Location: ../app/statistiche.html');
				}
			}
		}
	}
}
echo(json_encode($v));

// se il match è corretto, abilito le sessioni
/*session_start();
$_SESSION['username'] = $hotel_username;*/

// se il match non è corretto ritorna errore ritorna username o password errati

// ritornare un json con errore o successo

?>