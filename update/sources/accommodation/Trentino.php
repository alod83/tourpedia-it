<?php
function Trentino($date, $config, $nuovo, $vecchio){
	$url = $config['url_accommodation'];
	//$mapping = $config['accommodation'];
	if ($xml = simplexml_load_file($url)){
		$lastmodified = (string)($xml->attributes()->{'data-inizio-validita'});
		$row=0;
		foreach($xml->{'prezzi-localita-turistica'} as $strut){
			$row++;
			$document['_id']				 =	"TRE".$row;
			$document['name']            = (string)($strut[0]->attributes()->denominazione);
			$document['description']     = (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'tipologia-alberghiera'}); 
			$document['address']         = (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->indirizzo);
			$document['city']            = (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->comune);
			$document['hamlet']          = (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->frazione);
			$document['province']        = 'TN';
			$document['region']          = 'Trentino';
			$document['postal-code']     = (string)($strut[0]->{'prezzi-localita'}->attributes()->cap);
			$document['number of stars'] = (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->{'livello-classifica'});
			$document['email']           = (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-email'}); 
			$document['web site']        = (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-www'});
			$document['telephone']       = (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-telefono'});
			$document['fax']             = (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-fax'});
			$document['rooms']           = (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'numero-unita'});
			$document['beds']            = (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'numero-posti-letto'});
			$document=TrovaCoordinate($document, $vecchio);
			$nuovo->save($document);
		}
		print "TRENTINO: ".$row."\n";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Trentino'){
				$nuovo->save($obj);
				$row++;
			}
		}
		print "TRENTINO: Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog('Trentino', $date, $row, $lastmodified);
}
?>