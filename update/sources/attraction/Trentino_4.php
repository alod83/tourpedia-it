<?php
function Trentino_4($date, $config, $nuovo, $vecchio){
	$lastmodified=null;
	$collect="Attrazioni";
	$url = $config['url_attraction'];
	echo($url);
	if ($xml = simplexml_load_file($url)){
		$lastmodified = (string)($xml->{'data-export'});
		$row=0;
		foreach($xml->{'biblioteca'} as $biblio){
			$row++;
			$document['_id'] = "TRE4_".$row;
			$document['description'] = 'Biblioteche';
			$document['region'] = 'Trentino-Alto Adige';
			if(isset($biblio[0]->{'anagrafica'}->{'nomi'}->{'attuale'})){ $document['name']= (string)($biblio[0]->{'anagrafica'}->{'nomi'}->{'attuale'});} 
			if(isset($biblio[0]->{'anagrafica'}->{'indirizzo'}->{'via'})){$document['address'] = (string)($biblio[0]->{'anagrafica'}->{'indirizzo'}->{'via'});}
			if(isset($biblio[0]->{'anagrafica'}->{'indirizzo'}->{'cap'})){$document['postal-code'] = intval((string)($biblio[0]->{'anagrafica'}->{'indirizzo'}->{'cap'}));}
			if(isset($biblio[0]->{'anagrafica'}->{'indirizzo'}->{'comune'})){$document['city'] = (string)($biblio[0]->{'anagrafica'}->{'indirizzo'}->{'comune'});}
			if(isset($biblio[0]->{'anagrafica'}->{'indirizzo'}->{'provincia'})){$document['province'] = (string)($biblio[0]->{'anagrafica'}->{'indirizzo'}->{'provincia'});}
			if(isset($biblio[0]->{'anagrafica'}->{'indirizzo'}->{'coordinate'})){$document['latitude'] = round(floatval((string)($biblio[0]->{'anagrafica'}->{'indirizzo'}->{'coordinate'}->attributes()["latitudine"])),6);}
			if(isset($biblio[0]->{'anagrafica'}->{'indirizzo'}->{'coordinate'})){$document['longitude'] = round(floatval((string)($biblio[0]->{'anagrafica'}->{'indirizzo'}->{'coordinate'}->attributes()["longitudine"])),6);}
			if(isset($biblio[0]->{'anagrafica'}->{'contatti'}->{'altri'}->{'altro'}->{'valore'})){$document['url'] = (string)($biblio[0]->{'anagrafica'}->{'contatti'}->{'altri'}->{'altro'}->{'valore'});}
			if(isset($biblio[0]->{'servizi'}->{'orario'}->{'ufficiale'})){
				$string="";
				foreach($biblio[0]->{'servizi'}->{'orario'}->{'ufficiale'}->{'orario'} as $or){
					$g=(string)($or[0]->attributes()["giorno"]);
					$da=(string)($or[0]->attributes()["dalle"]);
					$a=(string)($or[0]->attributes()["alle"]);
					$substring=" ".$g.": ".$da."-".$a;
					$string.=$substring;
				}
				$document['opening hours'] = trim($string);
			}
			$nuovo->insertOne($document);
		}
		//print "TRENTINO: ".$row."\n";
	}
	else{
		$connection = new MongoDB\Client('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			$vecchio_id=$obj['_id'];
			if(strpos($vecchio_id, 'TRE4_')!==false){
				$nuovo->insertOne($obj);
				$row++;
			}
		}
		print "Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog('Trentino_4', $date, $row, $lastmodified, $collect);
}
?>