<?php 
$date = date("d/m/y H:i:s");
$ini_array = parse_ini_file("link.ini", true);
$connection = new MongoClient('mongodb://localhost:27017');
$dbname = $connection->selectDB('Strutture');
$nuovo = $dbname->NUOVO;
$vecchio = $dbname->VECCHIO;
$temp = $dbname->TEMP;
$drop = $temp->drop();
CopiaCollezione($vecchio, $temp);
$drop = $vecchio->drop();
CopiaCollezione($nuovo, $vecchio);
$drop = $nuovo->drop();

//Abruzzo 			*NON DISPONIBILE*
Basilicata($date, $ini_array);
//Calabria 			*NON DISPONIBILE*
//Campania			*NON DISPONIBILE*
EmiliaRomagna($date, $ini_array);
Friuli($date, $ini_array);
//Lazio				*NON DISPONIBILE*
Liguria($date, $ini_array);	
Lombardia($date, $ini_array);
Marche($date, $ini_array);
//Molise 			*NON DISPONIBILE*
//Piemonte($date, $ini_array);
Puglia($date, $ini_array);
//Sardegna			*FILE PDF*
//Sicilia			*NON SCARICABILE*
Toscana($date, $ini_array);
Trentino($date, $ini_array);
Umbria($date, $ini_array);
//VdAosta			*NON DISPONIBILE*
Veneto($date, $ini_array);

function CopiaCollezione($collPartenza, $collArrivo) {
	$cursor = $collPartenza->find();
	$num_docs = $cursor->count();
	if ($num_docs>0) {
		foreach ($cursor as $obj)
		{
			switch($obj['region']){
				case "Basilicata":
					$arr = array(
						'_id' 			=> $obj['_id'],
						'name' 			=> $obj['name'],
						'description' 	=> $obj['description'],
						'address' 		=> $obj['address'],
						'city' 			=> $obj['city'],
						'region' 		=> $obj['region'],
						'latitude' 		=> $obj['latitude'],
						'longitude' 	=> $obj['longitude']
						);
					$collArrivo->insert($arr);
					break;
				case "Emilia-Romagna":
					$arr = array(
						'_id' 				=> $obj['_id'],
						'name' 				=> $obj['name'],
						'description' 		=> $obj['description'],
						'address' 			=> $obj['address'],
						'city' 				=> $obj['city'],
						'region' 			=> $obj['region'],
						'latitude' 			=> $obj['latitude'],
						'longitude' 		=> $obj['longitude'],
						'province' 			=> $obj['province'],
						'locality' 			=> $obj['locality'],
						'postal-code' 		=> $obj['postal-code'],
						'number of stars' 	=> $obj['number of stars'],
						'email'		 		=> $obj['email'],
						'web site' 			=> $obj['web site'],
						'telephone' 		=> $obj['telephone'],
						'telephone2' 		=> $obj['telephone2'],
						'fax'	 			=> $obj['fax']
						);
					$collArrivo->insert($arr);
					break;
				case "Friuli-Venezia Giulia":
					$arr = array(
						'_id' 				=> $obj['_id'],
						'name' 				=> $obj['name'],
						'description' 		=> $obj['description'],
						'address' 			=> $obj['address'],
						'city' 				=> $obj['city'],
						'region' 			=> $obj['region'],
						'province' 			=> $obj['province'],
						'locality' 			=> $obj['locality'],
						'hamlet'			=> $obj['hamlet'],
						'postal-code' 		=> $obj['postal-code'],
						'number of stars' 	=> $obj['number of stars'],
						'email'		 		=> $obj['email'],
						'web site' 			=> $obj['web site'],
						'telephone' 		=> $obj['telephone'],
						'fax'	 			=> $obj['fax'],
						'cellular phone' 	=> $obj['cellular phone'],
						'rooms'	 			=> $obj['rooms'],
						'beds'	 			=> $obj['beds'],
						'toilets'	 		=> $obj['toilets']
						);
					$collArrivo->insert($arr);
					break;
				case "Liguria":
					$arr = array(
						'_id' 				=> $obj['_id'],
						'name' 				=> $obj['name'],
						'description' 		=> $obj['description'],
						'address' 			=> $obj['address'],
						'city' 				=> $obj['city'],
						'region' 			=> $obj['region'],
						'province' 			=> $obj['province'],
						'locality' 			=> $obj['locality'],
						'hamlet'			=> $obj['hamlet'],
						'postal-code' 		=> $obj['postal-code'],
						'number of stars' 	=> $obj['number of stars'],
						'email'		 		=> $obj['email'],
						'web site' 			=> $obj['web site'],
						'telephone' 		=> $obj['telephone'],
						'fax'	 			=> $obj['fax'],
						'rooms'	 			=> $obj['rooms'],
						'beds'	 			=> $obj['beds']
						);
					$collArrivo->insert($arr);
					break;
				case "Lombardia":
					$arr = array(
						'_id' 				=> $obj['_id'],
						'name' 				=> $obj['name'],
						'category'			=> $obj['category'],
						'description' 		=> $obj['description'],
						'address' 			=> $obj['address'],
						'city' 				=> $obj['city'],
						'region' 			=> $obj['region'],
						'province' 			=> $obj['province'],
						'locality' 			=> $obj['locality'],
						'hamlet'			=> $obj['hamlet'],
						'postal-code' 		=> $obj['postal-code'],
						'email'		 		=> $obj['email'],
						'web site' 			=> $obj['web site'],
						'telephone' 		=> $obj['telephone'],
						'fax'	 			=> $obj['fax'],
						'rooms'	 			=> $obj['rooms'],
						'suites'			=> $obj['suites'],
						'beds'	 			=> $obj['beds'],
						'latitude' 			=> $obj['latitude'],
						'longitude'	 		=> $obj['longitude']
						);
					$collArrivo->insert($arr);
					break;
				case "Marche":
					$arr = array(
						'_id' 				=> $obj['_id'],
						'name' 				=> $obj['name'],
						'description' 		=> $obj['description'],
						'address' 			=> $obj['address'],
						'city' 				=> $obj['city'],
						'region' 			=> $obj['region'],
						'locality' 			=> $obj['locality'],
						'postal-code' 		=> $obj['postal-code'],
						'email'		 		=> $obj['email'],
						'web site' 			=> $obj['web site'],
						'telephone' 		=> $obj['telephone'],
						'fax'	 			=> $obj['fax'],
						'cellular phone' 	=> $obj['cellular phone'],
						'latitude' 			=> $obj['latitude'],
						'longitude'	 		=> $obj['longitude']
						);
					$collArrivo->insert($arr);
					break;
				case "Puglia":
					$arr = array(
						'_id' 				=> $obj['_id'],
						'name' 				=> $obj['name'],
						'description' 		=> $obj['description'],
						'address' 			=> $obj['address'],
						'city' 				=> $obj['city'],
						'province' 			=> $obj['province'],
						'region' 			=> $obj['region'],
						'hamlet'			=> $obj['hamlet'],
						'postal-code' 		=> $obj['postal-code'],
						'number of stars' 	=> $obj['number of stars'],
						'email'		 		=> $obj['email'],
						'web site' 			=> $obj['web site'],
						'telephone' 		=> $obj['telephone'],
						'fax'	 			=> $obj['fax'],
						'rooms'	 			=> $obj['rooms'],
						'beds'	 			=> $obj['beds'],
						'toilets'	 		=> $obj['toilets'],
						'latitude' 			=> $obj['latitude'],
						'longitude'	 		=> $obj['longitude']
						);
					$collArrivo->insert($arr);
					break;
				case "Toscana":
					$arr = array(
						'_id' 				=> $obj['_id'],
						'name' 				=> $obj['name'],
						'description' 		=> $obj['description'],
						'address' 			=> $obj['address'],
						'city' 				=> $obj['city'],
						'province' 			=> $obj['province'],
						'region' 			=> $obj['region'],
						'postal-code' 		=> $obj['postal-code'],
						'number of stars' 	=> $obj['number of stars'],
						'email'		 		=> $obj['email'],
						'web site' 			=> $obj['web site'],
						'telephone' 		=> $obj['telephone'],
						'latitude' 			=> $obj['latitude'],
						'longitude'	 		=> $obj['longitude']
						);
					$collArrivo->insert($arr);
					break;
				case "Trentino":
					$arr = array(
						'_id' 				=> $obj['_id'],
						'name' 				=> $obj['name'],
						'description' 		=> $obj['description'],
						'address' 			=> $obj['address'],
						'city' 				=> $obj['city'],
						'province' 			=> $obj['province'],
						'region' 			=> $obj['region'],
						'hamlet'			=> $obj['hamlet'],
						'postal-code' 		=> $obj['postal-code'],
						'number of stars' 	=> $obj['number of stars'],
						'email'		 		=> $obj['email'],
						'web site' 			=> $obj['web site'],
						'telephone' 		=> $obj['telephone'],
						'fax'	 			=> $obj['fax'],
						'rooms'	 			=> $obj['rooms'],
						'beds'	 			=> $obj['beds']
						);
					$collArrivo->insert($arr);
					break;
				case "Umbria":
					$arr = array(
						'_id' 				=> $obj['_id'],
						'name' 				=> $obj['name'],
						'description' 		=> $obj['description'],
						'address' 			=> $obj['address'],
						'city' 				=> $obj['city'],
						'province' 			=> $obj['province'],
						'region' 			=> $obj['region'],
						'hamlet'			=> $obj['hamlet'],
						'postal-code' 		=> $obj['postal-code'],
						'number of stars' 	=> $obj['number of stars'],
						'email'		 		=> $obj['email'],
						'web site' 			=> $obj['web site'],
						'telephone' 		=> $obj['telephone'],
						'telephone2' 		=> $obj['telephone2'],
						'fax'	 			=> $obj['fax'],
						'rooms'	 			=> $obj['rooms'],
						'beds'	 			=> $obj['beds'],
						'toilets'	 		=> $obj['toilets'],
						'latitude' 			=> $obj['latitude'],
						'longitude'	 		=> $obj['longitude']
						);
					$collArrivo->insert($arr);
					break;
				case "Veneto":
					$arr = array(
						'_id' 				=> $obj['_id'],
						'name' 				=> $obj['name'],
						'description' 		=> $obj['description'],
						'address' 			=> $obj['address'],
						'city' 				=> $obj['city'],
						'province' 			=> $obj['province'],
						'locality'			=> $obj['locality'],
						'region' 			=> $obj['region'],
						'postal-code' 		=> $obj['postal-code'],
						'number of stars' 	=> $obj['number of stars'],
						'email'		 		=> $obj['email'],
						'web site' 			=> $obj['web site'],
						'telephone' 		=> $obj['telephone'],
						'fax'	 			=> $obj['fax']
						);
					$collArrivo->insert($arr);
					break;
			}
		}
	}
}

function UpdateLog ($regione, $date, $row){
	$connection = new MongoClient('mongodb://localhost:27017');
	$dbname = $connection->selectDB('Strutture');
	$log = $dbname->LOG;
	$product_array = array(
		'date' => $date
		);
	$document = $log->findOne($product_array);
	$document[$regione] = $row;
	$log->save($document);
}
function Basilicata($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Basilicata"]["url"], "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 			=> $arr[1], 
				'description' 	=> $arr[0], 
				'address' 		=> $arr[3],
				'city' 			=> 'Matera',
				'region' 		=> 'Basilicata',
				'latitude' 		=> round(floatval($arr[4]),6), 
				'longitude' 	=> round(floatval($arr[5]),6)
				]);
			$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
		}
		print "BASILICATA: ".$row."</br>";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$temp = $dbname->TEMP;
		$nuovo = $dbname->NUOVO;
		$cursor = $temp->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Basilicata'){
				$arr = array(
							'_id' 			=> $obj['_id'],
							'name' 			=> $obj['name'],
							'description' 	=> $obj['description'],
							'address' 		=> $obj['address'],
							'city' 			=> $obj['city'],
							'region' 		=> $obj['region'],
							'latitude' 		=> $obj['latitude'],
							'longitude' 	=> $obj['longitude']
							);
				$nuovo->insert($arr);
				$row++;
			}
		}
		print "BASILICATA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	$connection = new MongoClient('mongodb://localhost:27017');
	$dbname = $connection->selectDB('Strutture');
	$log = $dbname->LOG;
	$arr_log = array(
				'date'	=> $date,
				'Basilicata' => $row
	);
	$log->insert($arr_log);
}

function EmiliaRomagna($date, $ini_array){
	$zip = new ZipArchive;
	$tmpZipFileName = "Tmpfile.zip";
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	file_put_contents($tmpZipFileName, fopen($ini_array["EmiliaRomagna"]["url"], 'r'));
	if($zip->open($tmpZipFileName)!==FALSE){
		$row=-1;
		for ($i=0; $i<9; $i++){
			$filename = $zip->getNameIndex($i);
			$zip->extractTo('.', $filename);
			$file=fopen($filename, "r");
			while(($arr=fgetcsv($file,10000,";"))!==FALSE){
				$row++;
				if($row==0)continue;
				$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
				$bulk->insert([
					'name' 				=> $arr[6], 
					'description'		=> $arr[5], 
					'address'			=> $arr[8],
					'city' 				=> $arr[2], 
					'province' 			=> $arr[1], 
					'locality'			=> $arr[3],
					'region' 			=> 'Emilia-Romagna',
					'postal-code' 		=> intval($arr[9]), 
					'number of stars' 	=> $arr[7], 
					'email' 			=> $arr[14], 
					'web site' 			=> $arr[13], 
					'telephone' 		=> intval($arr[10]),
					'telephone2' 		=> intval($arr[11]),
					'fax' 				=> intval($arr[12]),
					'latitude' 			=> round(floatval($arr[16]),6),
					'longitude' 		=> round(floatval($arr[15]),6)
					]);
				$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
			}
		}
		print "EMILIA-ROMAGNA: ".$row."</br>";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$temp = $dbname->TEMP;
		$nuovo = $dbname->NUOVO;
		$cursor = $temp->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Emilia-Romagna'){
				$arr = array(
							'_id' 				=> $obj['_id'],
							'name' 				=> $obj['name'],
							'description' 		=> $obj['description'],
							'address' 			=> $obj['address'],
							'city' 				=> $obj['city'],
							'region' 			=> $obj['region'],
							'latitude' 			=> $obj['latitude'],
							'longitude' 		=> $obj['longitude'],
							'province' 			=> $obj['province'],
							'locality' 			=> $obj['locality'],
							'postal-code' 		=> $obj['postal-code'],
							'number of stars' 	=> $obj['number of stars'],
							'email'		 		=> $obj['email'],
							'web site' 			=> $obj['web site'],
							'telephone' 		=> $obj['telephone'],
							'telephone2' 		=> $obj['telephone2'],
							'fax'	 			=> $obj['fax']
							);
				$nuovo->insert($arr);
				$row++;
			}
		}
		print "EMILIA-ROMAGNA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Emilia-Romagna', $date, $row);
	// cancello i file temporanei
	unlink($tmpZipFileName);
	array_map('unlink', glob( "*.csv"));
}

function Friuli($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Friuli"]["url"], "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 				=> $arr[6], 
				'description' 		=> $arr[1], 
				'address' 			=> $arr[9].$arr[10], 
				'city'				=> $arr[3], 
				'province' 			=> $arr[2], 
				'locality' 			=> $arr[7],
				'hamlet' 			=> $arr[8],
				'region' 			=> 'Friuli-Venezia Giulia',
				'postal-code' 		=> intval($arr[10]), 
				'number of stars' 	=> $arr[4], 
				'email' 			=> $arr[14], 
				'web site' 			=> $arr[15], 
				'telephone' 		=> intval($arr[11]),
				'fax' 				=> intval($arr[13]),
				'cellular phone' 	=> intval($arr[12]),
				'rooms' 			=> intval($arr[16]),
				'beds' 				=> intval($arr[17]),
				'toilets' 			=> intval($arr[18])
				]);
			$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
		}
		print "FRIULI-VENEZIA GIULIA: ".$row."</br>";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$temp = $dbname->TEMP;
		$nuovo = $dbname->NUOVO;
		$cursor = $temp->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Friuli-Venezia Giulia'){
				$arr = array(
							'_id' 				=> $obj['_id'],
							'name' 				=> $obj['name'],
							'description' 		=> $obj['description'],
							'address' 			=> $obj['address'],
							'city' 				=> $obj['city'],
							'region' 			=> $obj['region'],
							'province' 			=> $obj['province'],
							'locality' 			=> $obj['locality'],
							'hamlet'			=> $obj['hamlet'],
							'postal-code' 		=> $obj['postal-code'],
							'number of stars' 	=> $obj['number of stars'],
							'email'		 		=> $obj['email'],
							'web site' 			=> $obj['web site'],
							'telephone' 		=> $obj['telephone'],
							'fax'	 			=> $obj['fax'],
							'cellular phone' 	=> $obj['cellular phone'],
							'rooms'	 			=> $obj['rooms'],
							'beds'	 			=> $obj['beds'],
							'toilets'	 		=> $obj['toilets']
							);
				$nuovo->insert($arr);
				$row++;
			}
		}
		print "FRIULI-VENEZIA GIULIA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Friuli-Venezia Giulia', $date, $row);
}

function Liguria($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	$ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL, $ini_array["Liguria"]["url"]); 
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	// dico al server che sono un browser
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    $output = curl_exec($ch); 

	$tmpFileName = 'tmpLiguria.csv';
    // close curl resource to free up system resources 
    curl_close($ch);
    $handle = fopen($tmpFileName, 'w');
	fwrite($handle, $output);
	fclose($handle);
	#if(($handle=fopen("http://www.regione.liguria.it/sep-servizi-online/catalogo-servizi-online/opendata/download/412/6883/48.html", "r"))!==FALSE){
	if(($handle=fopen($tmpFileName, "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,";"))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 				=> $arr[6], 
				'description' 		=> $arr[2], 
				'address' 			=> $arr[7],
				'city' 				=> $arr[4], 
				'province' 			=> $arr[3], 
				'locality' 			=> $arr[9],
				'hamlet' 			=> $arr[10],
				'region' 			=> 'Liguria',
				'postal-code' 		=> $arr[8], 
				'number of stars' 	=> $arr[5], 
				'email' 			=> $arr[13], 
				'web site' 			=> $arr[14], 
				'telephone' 		=> $arr[11],
				'fax' 				=> $arr[12],
				'rooms' 			=> $arr[15],
				'beds' 				=> $arr[16]
				]);
			$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
		}
		print "LIGURIA: ".$row."</br>";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$temp = $dbname->TEMP;
		$nuovo = $dbname->NUOVO;
		$cursor = $temp->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Liguria'){
				$arr = array(
							'_id' 				=> $obj['_id'],
							'name' 				=> $obj['name'],
							'description' 		=> $obj['description'],
							'address' 			=> $obj['address'],
							'city' 				=> $obj['city'],
							'region' 			=> $obj['region'],
							'province' 			=> $obj['province'],
							'locality' 			=> $obj['locality'],
							'hamlet'			=> $obj['hamlet'],
							'postal-code' 		=> $obj['postal-code'],
							'number of stars' 	=> $obj['number of stars'],
							'email'		 		=> $obj['email'],
							'web site' 			=> $obj['web site'],
							'telephone' 		=> $obj['telephone'],
							'fax'	 			=> $obj['fax'],
							'rooms'	 			=> $obj['rooms'],
							'beds'	 			=> $obj['beds']
							);
				$nuovo->insert($arr);
				$row++;
			}
		}
		print "LIGURIA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Liguria', $date, $row);
	unlink($tmpFileName);
}

function Lombardia($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Lombardia"]["url"], "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 			=> utf8_encode($arr[3]), 
				'category' 		=> utf8_encode($arr[4]),
				'description' 	=> utf8_encode($arr[5]), 
				'address' 		=> utf8_encode($arr[6]),
				'city'			=> utf8_encode($arr[2]), 
				'province' 		=> utf8_encode($arr[1]), 
				'locality' 		=> utf8_encode($arr[9]),
				'hamlet' 		=> utf8_encode($arr[8]),
				'region' 		=> 'Lombardia',
				'postal-code' 	=> intval($arr[7]), 
				'email' 		=> utf8_encode($arr[10]), 
				'web site' 		=> utf8_encode($arr[13]), 
				'telephone' 	=> intval($arr[11]),
				'fax' 			=> intval($arr[12]),
				'rooms' 		=> intval($arr[14]),
				'suites' 		=> intval($arr[15]),
				'beds' 			=> intval($arr[16]),
				'latitude' 		=> round(floatval($arr[32]),6), 
				'longitude' 	=> round(floatval($arr[33]),6)
				]);
			$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
		}
		print "LOMBARDIA: ".$row."</br>";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$temp = $dbname->TEMP;
		$nuovo = $dbname->NUOVO;
		$cursor = $temp->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Lombardia'){
				$arr = array(
							'_id' 				=> $obj['_id'],
							'name' 				=> $obj['name'],
							'category'			=> $obj['category'],
							'description' 		=> $obj['description'],
							'address' 			=> $obj['address'],
							'city' 				=> $obj['city'],
							'region' 			=> $obj['region'],
							'province' 			=> $obj['province'],
							'locality' 			=> $obj['locality'],
							'hamlet'			=> $obj['hamlet'],
							'postal-code' 		=> $obj['postal-code'],
							'email'		 		=> $obj['email'],
							'web site' 			=> $obj['web site'],
							'telephone' 		=> $obj['telephone'],
							'fax'	 			=> $obj['fax'],
							'rooms'	 			=> $obj['rooms'],
							'suites'			=> $obj['suites'],
							'beds'	 			=> $obj['beds'],
							'latitude' 			=> $obj['latitude'],
							'longitude'	 		=> $obj['longitude']
							);
				$nuovo->insert($arr);
				$row++;
			}
		}
		print "LOMBARDIA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Lombardia', $date, $row);
}

function Marche($date, $ini_array){
	$arr_tot=array();
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Marche"]["url"], "r"))!==FALSE){
		while(($arr=fgetcsv($handle,10000,";"))!==FALSE){
			$arr_tot=$arr_tot+$arr;
		}
		$row=0;
		for($i=18; $i+18<sizeof($arr_tot); $i=$i+18){
			$row++;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 				=> utf8_encode($arr_tot[$i+3]), 
				'description'		=> utf8_encode($arr_tot[$i+1]), 
				'address' 			=> utf8_encode($arr_tot[$i+7]),
				'city' 				=> utf8_encode($arr_tot[$i+9]), 
				'locality' 			=> utf8_encode($arr_tot[$i+10]),
				'region' 			=> 'Marche',
				'postal-code' 		=> intval($arr_tot[$i+6]), 
				'email' 			=> utf8_encode($arr_tot[$i+15]), 
				'web site' 			=> utf8_encode($arr_tot[$i+14]), 
				'telephone' 		=> intval($arr_tot[$i+11]),
				'cellular phone' 	=> intval($arr_tot[$i+13]),
				'fax' 				=> intval($arr_tot[$i+12]),
				'latitude' 			=> round(floatval($arr_tot[$i+17]),6),
				'longitude' 		=> round(floatval($arr_tot[$i+16]),6)
				]);
			$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
		}
		print "MARCHE: ".$row."</br>";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$temp = $dbname->TEMP;
		$nuovo = $dbname->NUOVO;
		$cursor = $temp->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Marche'){
				$arr = array(
							'_id' 				=> $obj['_id'],
							'name' 				=> $obj['name'],
							'description' 		=> $obj['description'],
							'address' 			=> $obj['address'],
							'city' 				=> $obj['city'],
							'region' 			=> $obj['region'],
							'locality' 			=> $obj['locality'],
							'postal-code' 		=> $obj['postal-code'],
							'email'		 		=> $obj['email'],
							'web site' 			=> $obj['web site'],
							'telephone' 		=> $obj['telephone'],
							'fax'	 			=> $obj['fax'],
							'cellular phone' 	=> $obj['cellular phone'],
							'latitude' 			=> $obj['latitude'],
							'longitude'	 		=> $obj['longitude']
							);
				$nuovo->insert($arr);
				$row++;
			}
		}
		print "MARCHE: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Marche', $date, $row);
}

function Piemonte($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	$ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL, $ini_array["Piemonte"]["url"]); 
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	// dico al server che sono un browser
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    $output = curl_exec($ch); 

	$tmpFileName = 'tmpLiguria.csv';
    // close curl resource to free up system resources 
    curl_close($ch);
    $handle = fopen($tmpFileName, 'w');
	fwrite($handle, $output);
	fclose($handle);
	//$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Piemonte"]["url"], "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 				=> $arr[2], 
				'description' 		=> $arr[7], 
				'address' 			=> $arr[3],
				'city' 				=> $arr[5], 
				'province' 			=> $arr[1],
				'region' 			=> 'Piemonte',
				'postal-code' 		=> intval($arr[4]), 	
				'number of stars' 	=> $arr[8], 
				'email' 			=> $arr[11],  
				'telephone' 		=> intval($arr[9]),
				'fax' 				=> intval($arr[10]),
				'rooms' 			=> intval($arr[13]),
				'beds' 				=> intval($arr[14]),
				'toilets' 			=> intval($arr[15])
				]);
			$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
		}
		print "PIEMONTE: ".$row."</br>";
	}
	else print "Errore!";
}
	/*$xml = simplexml_load_file('index.php');
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	$row=0;
	foreach($xml->item as $strut){
		$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
		$bulk->insert([
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
*/
function Puglia($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Puglia"]["url"], "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 				=> $arr[2], 
				'description' 		=> $arr[3], 
				'address' 			=> $arr[8],
				'city' 				=> $arr[12], 
				'province' 			=> $arr[13],
				'hamlet' 			=> $arr[9],				
				'region' 			=> 'Puglia',
				'postal-code' 		=> intval($arr[10]), 
				'number of stars' 	=> $arr[4], 
				'email' 			=> $arr[19], 
				'web site' 			=> $arr[18], 
				'telephone' 		=> intval($arr[16]),
				'fax' 				=> intval($arr[17]),
				'rooms' 			=> intval($arr[5]),
				'beds' 				=> intval($arr[7]),
				'toilets' 			=> intval($arr[6]),
				'latitude' 			=> round(floatval($arr[14]),6), 
				'longitude' 		=> round(floatval($arr[15]),6)
				]);
			$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
		}
		print "PUGLIA: ".$row."</br>";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$temp = $dbname->TEMP;
		$nuovo = $dbname->NUOVO;
		$cursor = $temp->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Puglia'){
				$arr = array(
							'_id' 				=> $obj['_id'],
							'name' 				=> $obj['name'],
							'description' 		=> $obj['description'],
							'address' 			=> $obj['address'],
							'city' 				=> $obj['city'],
							'province' 			=> $obj['province'],
							'region' 			=> $obj['region'],
							'hamlet'			=> $obj['hamlet'],
							'postal-code' 		=> $obj['postal-code'],
							'number of stars' 	=> $obj['number of stars'],
							'email'		 		=> $obj['email'],
							'web site' 			=> $obj['web site'],
							'telephone' 		=> $obj['telephone'],
							'fax'	 			=> $obj['fax'],
							'rooms'	 			=> $obj['rooms'],
							'beds'	 			=> $obj['beds'],
							'toilets'	 		=> $obj['toilets'],
							'latitude' 			=> $obj['latitude'],
							'longitude'	 		=> $obj['longitude']
							);
				$nuovo->insert($arr);
				$row++;
			}
		}
		print "PUGLIA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Puglia', $date, $row);
}

function Toscana($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Toscana"]["url"], "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,"|"))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 				=> utf8_encode($arr[3]), 
				'description' 		=> utf8_encode($arr[2]), 
				'address' 			=> utf8_encode($arr[4]), 
				'city' 				=> utf8_encode($arr[6]), 
				'province' 			=> utf8_encode($arr[7]), 
				'region' 			=> 'Toscana',
				'postal-code' 		=> intval($arr[5]), 
				'number of stars' 	=> utf8_encode($arr[8]),
				'email' 			=> utf8_encode($arr[9]), 
				'web site' 			=> utf8_encode($arr[10]), 
				'telephone'			=> intval($arr[13]),
				'latitude' 			=> round(floatval($arr[11]),6),
				'longitude' 		=> round(floatval($arr[12]),6)
				]);
			$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
		}
		print "TOSCANA: ".$row."</br>";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$temp = $dbname->TEMP;
		$nuovo = $dbname->NUOVO;
		$cursor = $temp->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Toscana'){
				$arr = array(
							'_id' 				=> $obj['_id'],
							'name' 				=> $obj['name'],
							'description' 		=> $obj['description'],
							'address' 			=> $obj['address'],
							'city' 				=> $obj['city'],
							'province' 			=> $obj['province'],
							'region' 			=> $obj['region'],
							'postal-code' 		=> $obj['postal-code'],
							'number of stars' 	=> $obj['number of stars'],
							'email'		 		=> $obj['email'],
							'web site' 			=> $obj['web site'],
							'telephone' 		=> $obj['telephone'],
							'latitude' 			=> $obj['latitude'],
							'longitude'	 		=> $obj['longitude']
							);
				$nuovo->insert($arr);
				$row++;
			}
		}
		print "TOSCANA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Toscana', $date, $row);
}

function Trentino($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if ($xml = simplexml_load_file($ini_array["Trentino"]["url"])){
		$row=0;
		foreach($xml->{'prezzi-localita-turistica'} as $strut){
			$row++;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
					'name'            => (string)($strut[0]->attributes()->denominazione), 
					'description'     => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'tipologia-alberghiera'}), 
					'address'         => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->indirizzo), 
					'city'            => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->comune), 
					'hamlet'          => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->frazione),
					'province'        => 'Trento', 
					'region'          => 'Trentino',
					'postal-code'     => (string)($strut[0]->{'prezzi-localita'}->attributes()->cap), 
					'number of stars' => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->{'livello-classifica'}),
					'email'           => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-email'}), 
					'web site'        => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-www'}), 
					'telephone'       => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-telefono'}),
					'fax'             => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-fax'}),
					'rooms'           => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'numero-unita'}),
					'beds'             => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'numero-posti-letto'})
					]);
					$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
		}
		print "TRENTINO: ".$row."</br>";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$temp = $dbname->TEMP;
		$nuovo = $dbname->NUOVO;
		$cursor = $temp->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Trentino'){
				$arr = array(
							'_id' 				=> $obj['_id'],
							'name' 				=> $obj['name'],
							'description' 		=> $obj['description'],
							'address' 			=> $obj['address'],
							'city' 				=> $obj['city'],
							'province' 			=> $obj['province'],
							'region' 			=> $obj['region'],
							'hamlet'			=> $obj['hamlet'],
							'postal-code' 		=> $obj['postal-code'],
							'number of stars' 	=> $obj['number of stars'],
							'email'		 		=> $obj['email'],
							'web site' 			=> $obj['web site'],
							'telephone' 		=> $obj['telephone'],
							'fax'	 			=> $obj['fax'],
							'rooms'	 			=> $obj['rooms'],
							'beds'	 			=> $obj['beds']
							);
				$nuovo->insert($arr);
				$row++;
			}
		}
		print "TRENTINO: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Trentino', $date, $row);
}

function Umbria($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Umbria"]["url"], "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 				=> $arr[5], 
				'description' 		=> $arr[6], 
				'address' 			=> $arr[8],
				'city' 				=> $arr[4], 
				'province' 			=> $arr[11], 
				'hamlet' 			=> $arr[9],
				'region' 			=> 'Umbria',
				'postal-code' 		=> intval($arr[10]), 
				'number of stars' 	=> $arr[7], 
				'email' 			=> $arr[16], 
				'web site' 			=> $arr[15], 
				'telephone' 		=> intval($arr[12]),
				'telephone2' 		=> intval($arr[13]),
				'fax' 				=> intval($arr[14]),
				'longitude' 		=> round(floatval($arr[17]),6),
				'latitude' 			=> round(floatval($arr[18]),6),
				'rooms'				=> intval($arr[19]),
				'beds' 				=> intval($arr[20]),
				'toilets' 			=> intval($arr[21])
				]);
			$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
		}
		print "UMBRIA: ".$row."</br>";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$temp = $dbname->TEMP;
		$nuovo = $dbname->NUOVO;
		$cursor = $temp->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Umbria'){
				$arr = array(
							'_id' 				=> $obj['_id'],
							'name' 				=> $obj['name'],
							'description' 		=> $obj['description'],
							'address' 			=> $obj['address'],
							'city' 				=> $obj['city'],
							'province' 			=> $obj['province'],
							'region' 			=> $obj['region'],
							'hamlet'			=> $obj['hamlet'],
							'postal-code' 		=> $obj['postal-code'],
							'number of stars' 	=> $obj['number of stars'],
							'email'		 		=> $obj['email'],
							'web site' 			=> $obj['web site'],
							'telephone' 		=> $obj['telephone'],
							'telephone2' 		=> $obj['telephone2'],
							'fax'	 			=> $obj['fax'],
							'rooms'	 			=> $obj['rooms'],
							'beds'	 			=> $obj['beds'],
							'toilets'	 		=> $obj['toilets'],
							'latitude' 			=> $obj['latitude'],
							'longitude'	 		=> $obj['longitude']
							);
				$nuovo->insert($arr);
				$row++;
			}
		}
		print "UMBRIA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Umbria', $date, $row);
}

function Veneto($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Veneto"]["url"], "r"))!==FALSE){
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 				=> $arr[7], 
				'description' 		=> $arr[3], 
				'address' 			=> $arr[8]." ".$arr[9],
				'city' 				=> $arr[1], 
				'province' 			=> $arr[0], 
				'locality' 			=> $arr[2],
				'region' 			=> 'Veneto',
				'postal-code' 		=> intval($arr[11]), 
				'number of stars' 	=> $arr[6], 
				'email' 			=> $arr[14], 
				'web site' 			=> $arr[15], 
				'telephone' 		=> intval($arr[12]),
				'fax' 				=> intval($arr[13])
				]);
			$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
		}
		print "VENETO: ".$row."</br>";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$temp = $dbname->TEMP;
		$nuovo = $dbname->NUOVO;
		$cursor = $temp->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Veneto'){
				$arr = array(
							'_id' 				=> $obj['_id'],
							'name' 				=> $obj['name'],
							'description' 		=> $obj['description'],
							'address' 			=> $obj['address'],
							'city' 				=> $obj['city'],
							'province' 			=> $obj['province'],
							'locality'			=> $obj['locality'],
							'region' 			=> $obj['region'],
							'postal-code' 		=> $obj['postal-code'],
							'number of stars' 	=> $obj['number of stars'],
							'email'		 		=> $obj['email'],
							'web site' 			=> $obj['web site'],
							'telephone' 		=> $obj['telephone'],
							'fax'	 			=> $obj['fax']
							);
				$nuovo->insert($arr);
				$row++;
			}
		}
		print "VENETO: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Veneto', $date, $row);
}
?> 