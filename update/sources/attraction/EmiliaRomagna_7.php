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
				$field_a = $field[0]->attributes();
				if(isset($field_a['name']) && $field_a['name']=='Indirizzo'){$document['name']=(string)($field[0]->{'value'});}
				if(isset($field_a['name']) && $field_a['name']=='Telefono'){$document['telephone']=(string)($field[0]->{'value'});}
				if(isset($field_a['name']) && $field_a['name']=='Cap'){$document['postal-code']=intval((string)($field[0]->{'value'}));}
				if(isset($field_a['name']) && $field_a['name']=='Tipologia'){$document['category']=(string)($field[0]->{'value'});}
				if(isset($field_a['name']) && $field_a['name']=='Immagine Url'){$document['image']=(string)($field[0]->{'value'});}
				if(isset($field_a['name']) && $field_a['name']=='Url Scheda'){$document['url']=(string)($field[0]->{'value'});}
			}
			$nuovo->insert($document);
		}

	}else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			$vecchio_id=$obj['_id'];
			if(strpos($vecchio_id, 'EMI7_')!==false){
				$nuovo->insert($obj);
				$row++;
			}
		}
		print "Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog('EmiliaRomagna_7', $date, $row, $lastmodified, $collect);
}
?>
