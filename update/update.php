<?php 
$date = date("d/m/y H:i:s");
//Abruzzo 			*NON DISPONIBILE*
Basilicata($date);
//Calabria 			*NON DISPONIBILE*
//Campania			*NON DISPONIBILE*
EmiliaRomagna($date);
Friuli($date);
//Lazio				*NON DISPONIBILE*
//Liguria($date);			//*NO CONNESSIONE*
Lombardia($date);
Marche($date);
//Molise 			*NON DISPONIBILE*
Piemonte($date);
Puglia($date);
//Sardegna			*FILE PDF*
//Sicilia			*NON SCARICABILE*
Toscana($date);
Trentino($date);
Umbria($date);
//VdAosta			*NON DISPONIBILE*
Veneto($date);

function Basilicata($date){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen("http://goo.gl/Kcc49c", "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'_id' => 'BAS'.$row, 
				'name' => $arr[1], 
				'description' => $arr[0], 
				'address' => $arr[3],
				'city' => 'Matera',
				'latitude' => round(floatval($arr[4]),6), 
				'longitude' => round(floatval($arr[5]),6)
				]);
			$manager->executeBulkWrite('Strutture.'.$date, $bulk);
		}
		print "BASILICATA: ".$row."</br>";
	}
}

function EmiliaRomagna($date){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen("http://dati.emilia-romagna.it/dataset/29a34fd2-3068-47c7-89cd-2995e4fac20f/resource/fdb22cba-61fb-4410-90c8-d20045807ab2/download/strutturericettive-2016-09-28.zip", "r"))!==FALSE){
		for ($i=0; $i<9; $i++){
			$filename = $handle->getNameIndex($i);
			$handle->extractTo('/temp/', $filename);
			$file=fopen('/temp/'.$filename, "r");
			$row=-1;
			while(($arr=fgetcsv($file,10000,";"))!==FALSE){
				$row++;
				if($row==0)continue;
				$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
				$bulk->insert([
					'_id' => 'EMI'.$row, 
					'name' => $arr[6], 
					'description' => $arr[5], 
					'address' => $arr[8],
					'city' => $arr[2], 
					'province' => $arr[1], 
					'locality' => $arr[3],
					'region' => 'Emilia-Romagna',
					'postal-code' => intval($arr[9]), 
					'number of stars' => $arr[7], 
					'email' => $arr[14], 
					'web site' => $arr[13], 
					'telephone' => intaval($arr[10]),
					'telephone2' => intval($arr[11]),
					'fax' => intval($arr[12]),
					'latitude' => round(floatval($arr[16]),6),
					'longitude' => round(floatval($arr[15]),6)
					]);
				$manager->executeBulkWrite('Strutture.'.$date, $bulk);
			}
			print "EMILIA-ROMAGNA: ".$row."</br>";
		}
	}
}

function Friuli($date){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen("https://www.dati.friuliveneziagiulia.it/api/views/fiiw-i5su/rows.csv?accessType=DOWNLOAD", "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'_id' => 'FRI'.$row, 
				'name' => $arr[6], 
				'description' => $arr[1], 
				'address' => $arr[9].$arr[10], 
				'city' => $arr[3], 
				'province' => $arr[2], 
				'locality' => $arr[7],
				'hamlet' => $arr[8],
				'region' => 'Friuli-Venezia Giulia',
				'postal-code' => intval($arr[10]), 
				'number of stars' => $arr[4], 
				'email' => $arr[14], 
				'web site' => $arr[15], 
				'telephone' => intval($arr[11]),
				'fax' => intval($arr[13]),
				'cellular phone' => intval($arr[12]),
				'rooms' => intval($arr[16]),
				'beds' => intval($arr[17]),
				'toilets' => intval($arr[18])
				]);
			$manager->executeBulkWrite('Strutture.'.$date, $bulk);
		}
		print "FRIULI-VENEZIA GIULIA: ".$row."</br>";
	}
}

function Liguria($date){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen("http://www.regione.liguria.it/sep-servizi-online/catalogo-servizi-online/opendata/download/412/6883/48.html", "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'_id' => 'LIG'.$row, 
				'name' => $arr[6], 
				'description' => $arr[2], 
				'address' => $arr[7],
				'city' => $arr[4], 
				'province' => $arr[3], 
				'locality' => $arr[9],
				'hamlet' => $arr[10],
				'region' => 'Liguria',
				'postal-code' => $arr[8], 
				'number of stars' => $arr[5], 
				'email' => $arr[13], 
				'web site' => $arr[14], 
				'telephone' => $arr[11],
				'fax' => $arr[12],
				'rooms' => $arr[15],
				'beds' => $arr[16]
				]);
			$manager->executeBulkWrite('Strutture.'.$date, $bulk);
		}
		print "LIGURIA: ".$row."</br>";
	}
}

function Lombardia($date){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen("https://www.dati.lombardia.it/api/views/745d-3uyg/rows.csv?accessType=DOWNLOAD", "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'_id' => 'LOM'.$row, 
				'name' => utf8_encode($arr[3]), 
				'category' => utf8_encode($arr[4]),
				'description' => utf8_encode($arr[5]), 
				'address' => utf8_encode($arr[6]),
				'city' => utf8_encode($arr[2]), 
				'province' => utf8_encode($arr[1]), 
				'locality' => utf8_encode($arr[9]),
				'hamlet' => utf8_encode($arr[8]),
				'region' => 'Lombardia',
				'postal-code' => intval($arr[7]), 
				'email' => utf8_encode($arr[10]), 
				'web site' => utf8_encode($arr[13]), 
				'telephone' => intval($arr[11]),
				'fax' => intval($arr[12]),
				'rooms' => intval($arr[14]),
				'suites' => intval($arr[15]),
				'beds' => intval($arr[16]),
				'latitude' => round(floatval($arr[32]),6), 
				'longitude' => round(floatval($arr[33]),6)
				]);
			$manager->executeBulkWrite('Strutture.'.$date, $bulk);
		}
		print "LOMBARDIA: ".$row."</br>";
	}
}

function Marche($date){
	$arr_tot=array();
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen("http://goodpa.regione.marche.it/dataset/db70a411-cdc3-489a-8775-20301f51387e/resource/fb510e3b-6d4d-44cd-9269-e8a427d55f9c/download/elencostrutture.csv", "r"))!==FALSE){
		while(($arr=fgetcsv($handle,10000,";"))!==FALSE){
			$arr_tot=$arr_tot+$arr;
		}
		$row=0;
		for($i=18; $i+18<sizeof($arr_tot); $i=$i+18){
			$row++;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'_id' => 'MAR'.$row, 
				'name' => utf8_encode($arr_tot[$i+3]), 
				'description' => utf8_encode($arr_tot[$i+1]), 
				'address' => utf8_encode($arr_tot[$i+7]),
				'city' => utf8_encode($arr_tot[$i+9]), 
				'locality' => utf8_encode($arr_tot[$i+10]),
				'region' => 'Marche',
				'postal-code' => intval($arr_tot[$i+6]), 
				'email' => utf8_encode($arr_tot[$i+15]), 
				'web site' => utf8_encode($arr_tot[$i+14]), 
				'telephone' => intval($arr_tot[$i+11]),
				'cellular phone' => intval($arr_tot[$i+13]),
				'fax' => intval($arr_tot[$i+12]),
				'latitude' => round(floatval($arr_tot[$i+17]),6),
				'longitude' => round(floatval($arr_tot[$i+16]),6)
				]);
			$manager->executeBulkWrite('Strutture.'.$date, $bulk);
		}
		print "MARCHE: ".$row."</br>";
	}
}

function Piemonte($date){
	$xml = simplexml_load_file('index.php');
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	$row=0;
	foreach($xml->item as $strut){
		$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
		$bulk->insert([
		'_id' => 'PIE'.$row, 
		'name' => (string)($strut->name), 
		'description' => (string)($strut->cat_it), 
		'address' => (string)($strut->address),
		'city' => (string)($strut->township), 
		'region' => 'Piemonte',
		'postal-code' => (string)($strut->postal_code), 
		'number of stars' => (string)($strut->stars), 
		'email' => (string)($strut->email), 
		'web site' => (string)($strut->website), 
		'telephone' => (string)($strut->phone),
		'longitude' => round(floatval((string)($strut->longitude)),6),
		'latitude' => round(floatval((string)($strut->latitude)),6)
		]);
		$manager->executeBulkWrite('Strutture.'.$date, $bulk);
		$row++;
	}
	print "PIEMONTE: ".$row."</br>";
}

function Puglia($date){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen("http://www.dataset.puglia.it/dataset/805bce5d-7a6e-43f0-ace9-b9e97dd5060f/resource/200938d2-c40b-4090-a6ab-c471bc56e46c/download/strutturericettivealberghiereedextralberghiere.csv", "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'_id' => 'PUG'.$row, 
				'name' => $arr[2], 
				'description' => $arr[3], 
				'address' => $arr[8],
				'city' => $arr[12], 
				'province' => $arr[13],
				'hamlet' => $arr[9],				
				'region' => 'Puglia',
				'postal-code' => intval($arr[10]), 
				'number of stars' => $arr[4], 
				'email' => $arr[19], 
				'web site' => $arr[18], 
				'telephone' => intval($arr[16]),
				'fax' => intval($arr[17]),
				'rooms' => intval($arr[5]),
				'beds' => intval($arr[7]),
				'toilets' => intval($arr[6]),
				'latitude' => round(floatval($arr[14]),6), 
				'longitude' => round(floatval($arr[15]),6)
				]);
			$manager->executeBulkWrite('Strutture.'.$date, $bulk);
		}
		print "PUGLIA: ".$row."</br>";
	}
}

function Toscana($date){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen("http://dati.toscana.it/dataset/ceb33e9c-7c80-478a-a3be-2f3700a64906/resource/5e8ec560-cbe6-4630-b191-e274218c183c/download/strutturericettive20161009.csv", "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,"|"))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'_id' => 'TOS'.$row, 
				'name' => utf8_encode($arr[3]), 
				'description' => utf8_encode($arr[2]), 
				'address' => utf8_encode($arr[4]), 
				'city' => utf8_encode($arr[6]), 
				'province' => utf8_encode($arr[7]), 
				'region' => 'Toscana',
				'postal-code' => intval($arr[5]), 
				'number of stars' => utf8_encode($arr[8]),
				'email' => utf8_encode($arr[9]), 
				'web site' => utf8_encode($arr[10]), 
				'telephone' => intval($arr[13]),
				'latitude' => round(floatval($arr[11]),6),
				'longitude' => round(floatval($arr[12]),6)
				]);
			$manager->executeBulkWrite('Strutture.'.$date, $bulk);
		}
		print "TOSCANA: ".$row."</br>";
	}
}

function Trentino($date){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	$xml = simplexml_load_file('EserciziAlberghieri.xml');
	$row=0;
	foreach($xml->{'prezzi-localita-turistica'} as $strut){
		$row++;
		$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
		$bulk->insert([
				'_id' => 'TRE'.$row, 
				'name' => (string)($strut[0]->attributes()->denominazione), 
				'description' => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'tipologia-alberghiera'}), 
				'address' => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->indirizzo), 
				'city' => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->comune), 
				'hamlet' => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->frazione),
				'province' => 'Trento', 
				'region' => 'Trentino',
				'postal-code' => (string)($strut[0]->{'prezzi-localita'}->attributes()->cap), 
				'number of stars' => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->{'livello-classifica'}),
				'email' => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-email'}), 
				'web site' => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-www'}), 
				'telephone' => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-telefono'}),
				'fax' => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-fax'}),
				'rooms' => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'numero-unita'}),
				'bed' => "<td>".(string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'numero-posti-letto'})
				]);
				$manager->executeBulkWrite('Strutture.'.$date, $bulk);
	}
	print "TRENTINO: ".$row."</br>";
}

function Umbria($date){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen("http://dati.umbria.it/datastore/dump/062d7bd6-f9c6-424e-9003-0b7cb3744cab", "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'_id' => 'UMB'.$row, 
				'name' => $arr[5], 
				'description' => $arr[6], 
				'address' => $arr[8],
				'city' => $arr[4], 
				'province' => $arr[11], 
				'hamlet' => $arr[9],
				'region' => 'Umbria',
				'postal-code' => intval($arr[10]), 
				'number of stars' => $arr[7], 
				'email' => $arr[16], 
				'web site' => $arr[15], 
				'telephone' => intval($arr[12]),
				'telephone2' => intval($arr[13]),
				'fax' => intval($arr[14]),
				'longitude' => round(floatval($arr[17]),6),
				'latitude' => round(floatval($arr[18]),6),
				'rooms' => intval($arr[19]),
				'beds' => intval($arr[20]),
				'toilets' => intval($arr[21])
				]);
			$manager->executeBulkWrite('Strutture.'.$date, $bulk);
		}
		print "UMBRIA: ".$row."</br>";
	}
}

function Veneto($date){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen("http://www.veneto.eu/static/opendata/dove-alloggiare.csv", "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'_id' => 'VEN'.$row, 
				'name' => $arr[7], 
				'description' => $arr[3], 
				'address' => $arr[8]." ".$arr[9],
				'city' => $arr[1], 
				'province' => $arr[0], 
				'locality' => $arr[2],
				'region' => 'Veneto',
				'postal-code' => intval($arr[11]), 
				'number of stars' => $arr[6], 
				'email' => $arr[14], 
				'web site' => $arr[15], 
				'telephone' => intval($arr[12]),
				'fax' => intval($arr[13])
				]);
			$manager->executeBulkWrite('Strutture.'.$date, $bulk);
		}
		print "VENETO: ".$row."</br>";
	}
}
?> 