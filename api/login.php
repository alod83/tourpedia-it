<?php
session_start();
// prende tramite GET username e password
header('Content-Type: application/json; charset=utf-8');
$nome_utente = $_GET["User"];
$password_utente = $_GET["Pass"];
$v;
include('config.php');
// verifico se username e password sono settati
if(isset($nome_utente)){
	if(isset($password_utente)){
		session_unset();
		// verifico se username e password matchano con quelli sul database
		$sql = "SELECT COUNT(username) AS N FROM `utenti` WHERE username=\"$nome_utente\" AND utenti.password=\"$password_utente\"";
		$risultati = mysqli_query($connessione, $sql);
		$r = mysqli_fetch_assoc($risultati);
		$v = floatval($r["N"]);
		// se il match è corretto, abilito le sessioni
		if($v == 1){
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
			$query = array('_id' => $nome_utente);
			$result=iterator_to_array($collection->find($query));
			if(isset($result[0]['_id'])){
				$_SESSION['_id'] = $result[0]['_id'];
			}
			if(isset($result[0]['name'])){
				$_SESSION['name'] = $result[0]['name'];
			}
			if(isset($result[0]['description'])){
				$_SESSION['description'] = $result[0]['description'];
			}
			if(isset($result[0]['address'])){
				$_SESSION['address'] = $result[0]['address'];
			}
			if(isset($result[0]['number of stars'])){
				$_SESSION['number of stars'] = intval(trim(preg_replace('/[^0-9]/', '',$result[0]['number of stars'])));
			}
			if(isset($result[0]['telephone'])){
				$_SESSION['telephone'] = $result[0]['telephone'];
			}
			if(isset($result[0]['email'])){
				$_SESSION['email'] = $result[0]['email'];
			}
			if(isset($result[0]['web site'])){
				$_SESSION['web site'] = $result[0]['web site'];
			}
			if(isset($result[0]['country'])){
				$_SESSION['country'] = $result[0]['country'];
			}
			if(isset($result[0]['region'])){
				$_SESSION['region'] = $result[0]['region'];
			}
			if(isset($result[0]['province'])){
				$_SESSION['province'] = $result[0]['province'];
			}
			if(isset($result[0]['postal-code'])){
				$_SESSION['postal-code'] = $result[0]['postal-code'];
			}
			if(isset($result[0]['city'])){
				$_SESSION['city'] = $result[0]['city'];
			}
			if(isset($result[0]['latitude'])){
				$_SESSION['latitude'] = $result[0]['latitude'];
			}
			if(isset($result[0]['longitude'])){
				$_SESSION['longitude'] = $result[0]['longitude'];
			}
			if(isset($result[0]['locality'])){
				$_SESSION['locality'] = $result[0]['locality'];
			}
			if(isset($result[0]['hamlet'])){
				$_SESSION['hamlet'] = $result[0]['hamlet'];
			}
			if(isset($result[0]['fax'])){
				$_SESSION['fax'] = $result[0]['fax'];
			}
			if(isset($result[0]['opening period'])){
				$_SESSION['opening period'] = $result[0]['opening period'];
			}
			if(isset($result[0]['facilities'])){
				$servizi = array();
				for($i=0; $i< count($result[0]['facilities']); $i++){
					array_push($servizi , $result[0]['facilities'][$i]);
				}
				$_SESSION['facilities']=$servizi;
			}
			if(isset($result[0]['photo'])){
				$_SESSION['photo'] = $result[0]['photo'];
			}
			if(isset($result[0]['beds'])){
				$_SESSION['beds'] = $result[0]['beds'];
			}
			if(isset($result[0]['rooms'])){
				$_SESSION['rooms'] = $result[0]['rooms'];
			}
			if(isset($result[0]['suites'])){
				$_SESSION['suites'] = $result[0]['suites'];
			}
			if(isset($result[0]['facebook'])){
				$_SESSION['facebook'] = $result[0]['facebook'];
			}
			if(isset($result[0]['instagram'])){
				$_SESSION['instagram'] = $result[0]['instagram'];
			}
			if(isset($result[0]['twitter'])){
				$_SESSION['twitter'] = $result[0]['twitter'];
			}
			if(isset($result[0]['languages'])){
				$lingue = array();
				for($i=0; $i< count($result[0]['languages']); $i++){
					array_push($lingue , $result[0]['languages'][$i]);
				}
				$_SESSION['languages']=$lingue;
			}
			if(isset($result[0]['category'])){
				$_SESSION['category'] = $result[0]['category'];
			}
			$a='../app/modifica.html';
		}else{
			// se il match non è corretto ritorna username o password errati
			$a="Username o password errati";
		}
	}
}
// ritornare un json con errore o successo
echo(json_encode($a));
?>