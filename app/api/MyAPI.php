<?php
	function basicAPI(){
		/*$connection = new MongoClient('mongodb://localhost:27017');*/
		$connection = new MongoDB\Driver\Manager("mongodb://localhost:27017");
		/*$dbname = $connection->selectDB('Strutture');
		$nuovo = $dbname->NUOVO;*/
		$risultati = array();
		/*
		$product_array = array(
			'region' => $_GET['place']
		);
		$document = $nuovo->find($product_array);
		foreach ($document as $doc) {
			array_push($risultati, $doc);
		}
		*/
		if(isset($_GET['place'])){
			$place = $_GET['place'];
			$product_array = array(
				'city' => new MongoRegex("/^$place\$/i")
			);
			$document = $nuovo->find($product_array);
			foreach ($document as $doc) {
				array_push($risultati, $doc);
			}
		}
		//************************************ ANCHE PER PROVINCE, LOCALITY E HAMLET? *************************************
		/*$coordinate = array();
		$coordinate['lat']=$document['latitude'];
		$coordinate['lng']=$document['longitude'];*/
		/*$coordinate = array(
			'lat' => 43.233333,
			'lng' => 10.583333
		);*/
		//return json_encode($coordinate);
		return json_encode($risultati);
	}
	function tags(){
		/*$connection = new MongoClient('mongodb://localhost:27017');*/
		$connection = new MongoDB\Driver\Manager("mongodb://localhost:27017");
		/*$dbname = $connection->selectDB('Strutture');
		$nuovo = $dbname->NUOVO;*/
		$tags = array();
		$product_array = array(
			
		);
		/*$document = $nuovo->find($product_array);*/
		foreach ($document as $doc) {
			if($doc['region']=="Marche"){
				array_push($tags, ucfirst(strtolower($doc['city'])));
			}
			else{
				array_push($tags, ucfirst(strtolower($doc['city']))." (".$doc['province'].")");
			}
			//array_push($tags, ucfirst(strtolower($doc['city'])));
		}
		$tags = array_unique($tags);
		$tagsok = array();
		foreach ($tags as $tag) {
			array_push($tagsok, $tag);
		}
		sort($tagsok);
		return json_encode($tagsok);
	}
?>