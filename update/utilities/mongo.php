<?php

function CopiaCollezione($collPartenza, $collArrivo) {
	$cursor = $collPartenza->find();
	//$num_docs = $cursor->count();
	//if ($num_docs>0) {
		foreach ($cursor as $obj)
		{
			$collArrivo->insertOne($obj);
		}
	//}
}

/*function UpdateLog($regione, $date, $row, $lastmodified, $collect){
	$connection = new MongoDB\Client('mongodb://localhost:27017');
	$dbname = $connection->$collect;
	$log = $dbname->LOG;
	$product_array = array(
		'date' => $date
		);
	$document = $log->findOne($product_array);
	if($row==NULL){
		$giorno = date("d");
		$giorno = $giorno-1;
		if ($giorno<10){
			$giorno = "0".$giorno;
		}
		$ieri = date("/m/y");
		// INSERIRE ORARIO DI ESECUZIONE DEL FILE
		$ieri = $giorno.$ieri." 03:00:00";
		$array_ieri = array(
		'date' => $ieri
		);
		$vecchiolog = $log->findOne($array_ieri);
		$vecchiadata = $vecchiolog[$regione." last modify"];
		$document[$regione." last modify"] = $vecchiadata;
	}
	else{
		if($regione!=="Trentino" AND $regione!=="Liguria" AND $regione!=="Umbria"){
			$lastmodified = substr($lastmodified, strlen("Last-Modified: "));
		}
		$document[$regione." last modify"] = $lastmodified;
	}
	if($collect=="Strutture"){
		$document[$regione." structures"] = $row;
	}else if ($collect=="Attrazioni"){
		$document[$regione." attractions"] = $row;
	}

	$log->insertOne($document);
}*/

function UpdateLog($region, $date, $row, $lastmodified, $collect){
	//$connection = new MongoDB\Client('mongodb://localhost:27017');
	$connection = new MongoClient('mongodb://localhost:27017');
	$dbname = $connection->selectDB($collect);
	//$dbname = $connection->$collect; 
	$log = $dbname->LOG;
	/*$product_array = array(
		'date' => $date
		);
	$document_date = $log->findOne($product_array);
	$date=$document_date['date'];*/
	$document["date"]=$date;
	if($row==NULL){
		$giorno = date("d");
		$giorno = $giorno-1;
		if ($giorno<10){
			$giorno = "0".$giorno;
		}
		$ieri = date("/m/y");
		// INSERIRE ORARIO DI ESECUZIONE DEL FILE
		$ieri = $giorno.$ieri." 03:00:00";
		$array_ieri = array(
		'date' => $ieri
		);
		if($vecchiolog = $log->findOne($array_ieri)){
			$vecchiadata = $vecchiolog[$region." last modify"];
			$document[$region." last modify"] = $vecchiadata;
		}else{
			$document[$region." last modify"] = null;
		}
	}
	else{
		if($lastmodified!==null){
			if($region!=="Trentino" AND  $region!=="Trentino_4" AND $region!=="Liguria" AND $region!=="Umbria"){
				$lastmodified = substr($lastmodified, strlen("Last-Modified: "));
			}
		}
		$document[$region." last modify"] = $lastmodified;
	}
	if($collect=="Strutture"){
		$document[$region." structures"] = $row;
	}else if ($collect=="Attrazioni"){
		$document[$region." attractions"] = $row;
	}

	$log->insertOne($document);
}

function TrovaCoordinate($document, $vecchio){
	$document['latitude']=NULL;
	$document['longitude']=NULL;
	if((array_key_exists('city', $document))&& (array_key_exists('address', $document))){
		$product_array = array(
			'address' => $document['address'],
			'city' => $document['city']
			);
		if(($found = $vecchio->findOne($product_array)) && (array_key_exists('latitude', $found))){
			if($found['latitude']!==NULL){
				$document['latitude']=$found['latitude'];
				$document['longitude']=$found['longitude'];
			}
		}
		else{
			$add=$document['address'].", ".$document['city'];
			if($geo=geocode($add)){
				$document['latitude']=round(floatval($geo[0]),6);
				$document['longitude']=round(floatval($geo[1]),6);
				$document['enrichment']="latitude, longitude";
			}
		}
	}
	return $document;
}
?>
