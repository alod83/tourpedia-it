<?php

function CopiaCollezione($collPartenza, $collArrivo) {
	$cursor = $collPartenza->find();
	$num_docs = $cursor->count();
	if ($num_docs>0) {
		foreach ($cursor as $obj)
		{
			$collArrivo->save($obj);
		}
	}
}

function UpdateLog($regione, $date, $row, $lastmodified){
	$connection = new MongoClient('mongodb://localhost:27017');
	$dbname = $connection->selectDB('Strutture');
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
	$document[$regione." structures"] = $row;
	$log->save($document);
}

function TrovaCoordinate($document, $vecchio){
	$product_array = array(
		'address' => $document['address']
		);
	if($found = $vecchio->findOne($product_array)){
		if($found['latitude']!==NULL){
			$document['latitude']=$found['latitude'];
			$document['longitude']=$found['longitude'];
		}
		else{
			$document['latitude']=NULL;
			$document['longitude']=NULL;
		}
	}
	else{
		$add=$document['address'].", ".$document['city'];
		if($geo=geocode($add)){
			$document['latitude']=round(floatval($geo[0]),6);
			$document['longitude']=round(floatval($geo[1]),6);
			$document['enrichment']="latitude, longitude";
		}
		else{
			$document['latitude']=NULL;
			$document['longitude']=NULL;
		}
	}
	return $document;
}
?>