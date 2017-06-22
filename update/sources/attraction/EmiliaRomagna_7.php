<?php
function EmiliaRomagna_7($date, $config, $nuovo, $vecchio){
	$lastmodified=null;
	$collect="Attrazioni";
	$url = $config['url_attraction'];
	echo($url);
	if ($xml = simplexml_load_file($url)){
		$row=0;
		foreach($xml->{'Document'}->{'Placemark'} as $biblio){
			$row++;
			$document['_id'] = "EMI7_".$row;
			$document['description'] = 'Biblioteche comunali';
			$document['region'] = 'Emilia-Romagna';
			$document['city'] = 'Bologna';
			$document['province'] = 'BO';
			if(isset($biblio[0]->{'name'})){$document['name']=(string)($biblio[0]->{'name'});}
			if(isset($biblio[0]->{'Point'}->{'coordinates'})){
				preg_match('/[0-9]+\.[0-9]+,/', (string)($biblio[0]->{'Point'}->{'coordinates'}), $matches);
				$lon= round(floatval($matches[0]),6);
				preg_match('/[0-9]+\.[0-9]+$/', (string)($biblio[0]->{'Point'}->{'coordinates'}), $matches);
				$lat= round(floatval($matches[0]),6);
				$document['latitude']= $lat;
				$document['longitude']= $lon;
			}
			foreach ($biblio->{'ExtendedData'}->{'Data'} as $field) {
				if(isset($field[0]->attributes()['name']) and $field[0]->attributes()['name']=='Indirizzo'){$document['name']=(string)($field[0]->{'value'});}
				if(isset($field[0]->attributes()['name']) and $field[0]->attributes()['name']=='Telefono'){$document['telephone']=(string)($field[0]->{'value'});}
				if(isset($field[0]->attributes()['name']) and $field[0]->attributes()['name']=='Cap'){$document['postal-code']=intval((string)($field[0]->{'value'}));}
				if(isset($field[0]->attributes()['name']) and $field[0]->attributes()['name']=='Tipologia'){$document['category']=(string)($field[0]->{'value'});}
				if(isset($field[0]->attributes()['name']) and $field[0]->attributes()['name']=='Immagine Url'){$document['image']=(string)($field[0]->{'value'});}
				if(isset($field[0]->attributes()['name']) and $field[0]->attributes()['name']=='Url Scheda'){$document['url']=(string)($field[0]->{'value'});}
			}
			$nuovo->insertOne($document);
		}

	}else{
		$connection = new MongoDB\Client('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			$vecchio_id=$obj['_id'];
			if(strpos($vecchio_id, 'EMI7_')!==false){
				$nuovo->insertOne($obj);
				$row++;
			}
		}
		print "Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog('EmiliaRomagna_7', $date, $row, $lastmodified, $collect);
}
?>