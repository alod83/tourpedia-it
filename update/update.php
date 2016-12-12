<?php 
ini_set('MAX_EXECUTION_TIME', -1);
require_once 'Excel/reader.php';
require 'simple_html_dom.php';
ini_set('auto_detect_line_endings', TRUE);
$date = date("d/m/y H:i:s");
$ini_array = parse_ini_file("config.ini", true);
$connection = new MongoClient('mongodb://localhost:27017');
$dbname = $connection->selectDB('Strutture');
$nuovo = $dbname->NUOVO;
$vecchio = $dbname->VECCHIO;
$temp = $dbname->TEMP;
$log = $dbname->LOG;
$document["date"] = $date;
$log->save($document);
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
Piemonte($date, $ini_array);
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

function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){
 
        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        //$formatted_address = $resp['results'][0]['formatted_address'];
         
        // verify if data is complete
        if($lati && $longi/* && $formatted_address*/){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi 
                    //$formatted_address
                );
             
            return $data_arr;
             
        }else{
            return false;
        }
         
    }else{
        return false;
    }
}

function Basilicata($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	$lastmodified = NULL;
	$ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $ini_array["Basilicata"]["url"]); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	// dico al server che sono un browser
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    if (($output = curl_exec($ch))!=FALSE){ 
		$tmpFileName = 'tmpBasilicata.xls';
		// close curl resource to free up system resources 
		$metadata = stream_get_meta_data(fopen($ini_array["Basilicata"]["url"],"r"));
		$lastmodified = $metadata["wrapper_data"][3];
		curl_close($ch);
		$handle = fopen($tmpFileName, 'w');
		fwrite($handle, $output);
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CPa25a');
		$data->read($tmpFileName);
		$row=0;
		for($i=2; $i<$data->sheets[0]['numRows']; $i++){
				$row++;
				if(isset($data->sheets[0]['cells'][$i][2])) {$name=utf8_encode($data->sheets[0]['cells'][$i][2]);} else {$name=NULL;}
				if(isset($data->sheets[0]['cells'][$i][3])) {$description=utf8_encode($data->sheets[0]['cells'][$i][3]);} else {$description=NULL;}
				if(isset($data->sheets[0]['cells'][$i][5])) {$address=utf8_encode($data->sheets[0]['cells'][$i][5]);} else {continue;}
				if(isset($data->sheets[0]['cells'][$i][4])) {$stars=utf8_encode($data->sheets[0]['cells'][$i][4]);} else {$stars=NULL;}
				if(isset($data->sheets[0]['cells'][$i][7])) {$telephone=utf8_encode($data->sheets[0]['cells'][$i][7]);} else {$telephone=NULL;}
				if(isset($data->sheets[0]['cells'][$i][8])) {$cellular=utf8_encode($data->sheets[0]['cells'][$i][8]);} else {$cellular=NULL;}
				if(isset($data->sheets[0]['cells'][$i][9])) {$fax=utf8_encode($data->sheets[0]['cells'][$i][9]);} else {$fax=NULL;}
				if(isset($data->sheets[0]['cells'][$i][10])) {$web=utf8_encode($data->sheets[0]['cells'][$i][10]);} else {$web=NULL;}
				if(isset($data->sheets[0]['cells'][$i][11])) {$email=utf8_encode($data->sheets[0]['cells'][$i][11]);} else {$email=NULL;}
				if(isset($data->sheets[0]['cells'][$i][13])) {$beds=intval($data->sheets[0]['cells'][$i][13]);} else {$beds=NULL;}
				if(isset($data->sheets[0]['cells'][$i][6])) {
					$pcp=explode(" ", $data->sheets[0]['cells'][$i][6], 2);
					$postal=$pcp[0];
					$cp=explode("(", $pcp[1]);
					$city = substr($cp[0], 1);
					if($cp[1]=="Mt)" OR $cp[1]=="MT)") $prov="MT";
					if($cp[1]=="Pz)" OR $cp[1]=="PZ)") $prov="PZ";
				} 
				else {
					$postal=NULL;
					$city=NULL;
					$prov=NULL;
				}				
				$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
				$bulk->insert([
					'name' 				=> $name, 
					'description' 		=> $description, 
					'address' 			=> $address,
					'city' 				=> $city,
					'province'			=> $prov,
					'postal code'		=> $postal,
					'region' 			=> 'Basilicata',
					'number of stars'	=> $stars,
					'telephone'			=> $telephone, 
					'cellular phone'	=> $cellular,
					'fax'				=> $fax,
					'web site'			=> $web,
					'email'				=> $email,
					'beds'				=> $beds
					]);
				$manager->executeBulkWrite('Strutture.BASILICATA', $bulk);
		}
		print "BASILICATA: ".$row."</br>";
		/*if(($handle=fopen($ini_array["Basilicata"]["url"], "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
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
		$row = NULL;*/
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
	UpdateLog("Basilicata", $date, $row, $lastmodified);
}

function EmiliaRomagna($date, $ini_array){
	$zip = new ZipArchive;
	$tmpZipFileName = "Tmpfile.zip";
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	$lastmodified = NULL;
	if(file_put_contents($tmpZipFileName, fopen($ini_array["EmiliaRomagna"]["url"], 'r'))){
		if($zip->open($tmpZipFileName)!==FALSE){
			$metadata = stream_get_meta_data(fopen($ini_array["EmiliaRomagna"]["url"],"r"));
			$lastmodified = $metadata["wrapper_data"][7];
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
						'telephone' 		=> $arr[10],
						'telephone2' 		=> $arr[11],
						'fax' 				=> $arr[12],
						'latitude' 			=> round(floatval($arr[16]),6),
						'longitude' 		=> round(floatval($arr[15]),6)
						]);
					$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
				}
			}
			print "EMILIA-ROMAGNA: ".$row."</br>";
		}
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
				$nuovo->save($obj);
				$row++;
			}
		}
		print "EMILIA-ROMAGNA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Emilia-Romagna', $date, $row, $lastmodified);
	// cancello i file temporanei
	unlink($tmpZipFileName);
	array_map('unlink', glob( "*.csv"));
}

function Friuli($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Friuli"]["url"], "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
		$lastmodified = $metadata["wrapper_data"][8];
		if (preg_match('/Last-Modified:(.*?)/i', $lastmodified, $matches))
		$row=-1;
		$prov=NULL;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			switch($arr[2]){
				case "GORIZIA":
					$prov = "GO";
					break;
				case "UDINE":
					$prov = "UD";
					break;
				case "PORDENONE":
					$prov = "PN";
					break;
				case "TRIESTE":
					$prov = "TS";
					break;
			}
			//$geo = geocode($arr[9].$arr[10].", ".$arr[3].", ".$prov);
			//print_r ($geo);
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 				=> $arr[6], 
				'description' 		=> $arr[1], 
				'address' 			=> $arr[9].$arr[10], 
				'city'				=> $arr[3], 
				'province' 			=> $prov, 
				'locality' 			=> $arr[7],
				'hamlet' 			=> $arr[8],
				'region' 			=> 'Friuli-Venezia Giulia',
				'postal-code' 		=> intval($arr[10]), 
				'number of stars' 	=> $arr[4], 
				'email' 			=> $arr[14], 
				'web site' 			=> $arr[15], 
				'telephone' 		=> $arr[11],
				'fax' 				=> $arr[13],
				'cellular phone' 	=> $arr[12],
				'rooms' 			=> intval($arr[16]),
				'beds' 				=> intval($arr[17]),
				'toilets' 			=> intval($arr[18]),
				//'latitude'			=> round(floatval($geo[0]),6),
				//'longitude'			=> round(floatval($geo[1]),6)
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
				$nuovo->save($obj);
				$row++;
			}
		}
		print "FRIULI-VENEZIA GIULIA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Friuli-Venezia Giulia', $date, $row, $lastmodified);
}

function Liguria($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	$ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $ini_array["Liguria"]["url"]); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	// dico al server che sono un browser
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    if (($output = curl_exec($ch))!=FALSE){ 

		$tmpFileName = 'tmpLiguria.csv';
		// close curl resource to free up system resources 
		curl_close($ch);
		$handle = fopen($tmpFileName, 'w');
		fwrite($handle, $output);
		fclose($handle);
		#if(($handle=fopen("http://www.regione.liguria.it/sep-servizi-online/catalogo-servizi-online/opendata/download/412/6883/48.html", "r"))!==FALSE){
		if(($handle=fopen($tmpFileName, "r"))!==FALSE){
			$metadata = stream_get_meta_data($handle);
			$row=-1;
			while(($arr=fgetcsv($handle,10000,";"))!==FALSE){
				$row++;
				if($row==0)continue;
				switch($arr[3]){
					case "Genova":
						$prov = "GE";
						break;
					case "Imperia":
						$prov = "Im";
						break;
					case "La Spezia":
						$prov = "SP";
						break;
					case "Savona":
						$prov = "SV";
						break;
				}
				//$geo = geocode($arr[7].", ".$arr[4].", ".$prov);
				$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
				$bulk->insert([
					'name' 				=> $arr[6], 
					'description' 		=> $arr[2], 
					'address' 			=> $arr[7],
					'city' 				=> $arr[4], 
					'province' 			=> $prov, 
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
					'beds' 				=> $arr[16],
					//'latitude'			=> round(floatval($geo[0]),6),
					//'longitude'			=> round(floatval($geo[1]),6)
					]);
				$manager->executeBulkWrite('Strutture.LIGURIA', $bulk);
			}
			print "LIGURIA: ".$row."</br>";
		}
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
				$nuovo->save($obj);
				$row++;
			}
		}
		print "LIGURIA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	$ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, "http://www.regione.liguria.it/sep-servizi-online/catalogo-servizi-online/opendata/item/6883-strutture-ricettive-alberghiere.html"); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	// dico al server che sono un browser
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    if (($output = curl_exec($ch))!=FALSE){ 
		$tmpFileName = 'tmpLiguria.html';
		// close curl resource to free up system resources 
		curl_close($ch);
		$handle = fopen($tmpFileName, 'w');
		fwrite($handle, $output);
		fclose($handle);
		$html = file_get_html('tmpLiguria.html');
		$lastmodified=$html->find('div[class=flexi value field_data_ultimo_aggiornamento]',0);
		$lastmodified=substr($lastmodified,strlen("<div class='flexi value field_data_ultimo_aggiornamento'>"),-6);
	}
	UpdateLog('Liguria', $date, $row, $lastmodified);
	unlink($tmpFileName);
}

function Lombardia($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Lombardia"]["url"], "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
		$lastmodified = $metadata["wrapper_data"][8];
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
				'telephone' 	=> $arr[11],
				'fax' 			=> $arr[12],
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
				$nuovo->save($obj);
				$row++;
			}
		}
		print "LOMBARDIA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Lombardia', $date, $row, $lastmodified);
}

function Marche($date, $ini_array){
	$arr_tot=array();
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Marche"]["url"], "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
		$lastmodified = $metadata["wrapper_data"][10];
		/*while(($arr=fgetcsv($handle,10000,";"))!==FALSE){
			$arr_tot=$arr_tot+$arr;
		}
		$row=0;
		for($i=18; $i+18<sizeof($arr_tot); $i=$i+18){*/
		$row=-1;
		while(($arr=fgetcsv($handle,10000,";"))!==FALSE){
			$row++;
			if($row==0)continue;
			if(isset($arr[3])==FALSE){
				$row--;
				continue;
			}
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 				=> utf8_encode($arr[3]), 
				'description'		=> utf8_encode($arr[1]), 
				'address' 			=> utf8_encode($arr[7]),
				'city' 				=> utf8_encode($arr[9]), 
				'locality' 			=> utf8_encode($arr[10]),
				'region' 			=> 'Marche',
				'postal-code' 		=> intval($arr[6]), 
				'email' 			=> utf8_encode($arr[15]), 
				'web site' 			=> utf8_encode($arr[14]), 
				'telephone' 		=> $arr[11],
				'cellular phone' 	=> $arr[13],
				'fax' 				=> $arr[12],
				'latitude' 			=> round(floatval($arr[17]),6),
				'longitude' 		=> round(floatval($arr[16]),6)
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
				$nuovo->save($obj);
				$row++;
			}
		}
		print "MARCHE: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Marche', $date, $row, $lastmodified);
}

function Piemonte($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	$ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $ini_array["Piemonte"]["url"]); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	// dico al server che sono un browser
	//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);
	if(!empty($output)){
		$row=-1;
		$arr=explode('";"', $output);
		for($i=18; $i<count($arr)-1; $i=$i+18){
			$row++;
			if($row==0)continue;
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
				'name' 				=> $arr[$i+2], 
				'description' 		=> $arr[$i+7], 
				'address' 			=> $arr[$i+3],
				'city' 				=> $arr[$i+5], 
				'province' 			=> $arr[$i+1],
				'region' 			=> 'Piemonte',
				'postal-code' 		=> intval($arr[$i+4]), 	
				'number of stars' 	=> $arr[$i+8], 
				'email' 			=> $arr[$i+11],  
				'telephone' 		=> $arr[$i+9],
				'fax' 				=> $arr[$i+10],
				'rooms' 			=> intval($arr[$i+13]),
				'beds' 				=> intval($arr[$i+14]),
				'toilets' 			=> intval($arr[$i+15])
				]);
			$manager->executeBulkWrite('Strutture.NUOVO', $bulk);
		}
		print "PIEMONTE: ".$row."</br>";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$temp = $dbname->TEMP;
		$nuovo = $dbname->NUOVO;
		$cursor = $temp->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Piemonte'){
				$nuovo->save($obj);
				$row++;
			}	
		}
		print "PIEMONTE: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
}

function Puglia($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Puglia"]["url"], "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
		$lastmodified = $metadata["wrapper_data"][11];
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			switch($arr[13]){
				case "Bari":
					$prov = "BA";
					break;
				case "Barletta-Andria-Trani":
					$prov = "BT";
					break;
				case "Brindisi":
					$prov = "BR";
					break;
				case "Foggia":
					$prov = "FG";
					break;
				case "Lecce":
					$prov = "LE";
					break;
				case "Taranto":
					$prov = "TA";
					break;
			}
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
				'telephone' 		=> $arr[16],
				'fax' 				=> $arr[17],
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
				$nuovo->save($obj);
				$row++;
			}
		}
		print "PUGLIA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Puglia', $date, $row, $lastmodified);
}

function Toscana($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Toscana"]["url"], "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
		$lastmodified = $metadata["wrapper_data"][9];
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
				'telephone'			=> $arr[13],
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
				$nuovo->save($obj);
				$row++;
			}
		}
		print "TOSCANA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Toscana', $date, $row, $lastmodified);
}

function Trentino($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if ($xml = simplexml_load_file($ini_array["Trentino"]["url"])){
		$lastmodified = (string)($xml->attributes()->{'data-inizio-validita'});
		$row=0;
		foreach($xml->{'prezzi-localita-turistica'} as $strut){
			$row++;
			//$geo = geocode((string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->indirizzo).", ".(string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->comune).", TN");
			$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
			$bulk->insert([
					'name'            => (string)($strut[0]->attributes()->denominazione), 
					'description'     => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'tipologia-alberghiera'}), 
					'address'         => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->indirizzo), 
					'city'            => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->comune), 
					'hamlet'          => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->frazione),
					'province'        => 'TN', 
					'region'          => 'Trentino',
					'postal-code'     => (string)($strut[0]->{'prezzi-localita'}->attributes()->cap), 
					'number of stars' => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->{'prezzi-saa'}->attributes()->{'livello-classifica'}),
					'email'           => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-email'}), 
					'web site'        => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-www'}), 
					'telephone'       => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-telefono'}),
					'fax'             => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'recapito-fax'}),
					'rooms'           => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'numero-unita'}),
					'beds'             => (string)($strut[0]->{'prezzi-localita'}->{'prezzi-albergo'}->attributes()->{'numero-posti-letto'}),
					//'latitude'			=> round(floatval($geo[0]),6),
					//'longitude'			=> round(floatval($geo[1]),6)
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
				$nuovo->save($obj);
				$row++;
			}
		}
		print "TRENTINO: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Trentino', $date, $row, $lastmodified);
}

function Umbria($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Umbria"]["url"], "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
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
				'telephone' 		=> $arr[12],
				'telephone2' 		=> $arr[13],
				'fax' 				=> $arr[14],
				'longitude' 		=> round(floatval($arr[17])/100000,6),
				'latitude' 			=> round(floatval($arr[18])/100000,6),
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
				$nuovo->save($obj);
				$row++;
			}
		}
		print "UMBRIA: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	$html = file_get_html('http://dati.umbria.it/dataset/strutture-ricettive/resource/062d7bd6-f9c6-424e-9003-0b7cb3744cab');
	$lastmodified=$html->find('table',0)->find('td',0);
	$lastmodified=substr($lastmodified,4,-5);
	UpdateLog('Umbria', $date, $row, $lastmodified);
}

function Veneto($date, $ini_array){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	if(($handle=fopen($ini_array["Veneto"]["url"], "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
		$lastmodified = $metadata["wrapper_data"][3];
		$row=-1;
		$counter_geo=0;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			switch($arr[0]){
				case "BELLUNO":
					$prov = "BL";
					break;
				case "PADOVA":
					$prov = "PD";
					break;
				case "ROVIGO":
					$prov = "RO";
					break;
				case "TREVISO":
					$prov = "TV";
					break;
				case "VENEZIA":
					$prov = "VE";
					break;
				case "VERONA":
					$prov = "VE";
					break;
				case "VICENZA":
					$prov = "VI";
					break;
			}
			//$geo = geocode($arr[8]." ".$arr[9].", ".$arr[1].", ".$arr[0]);
			//$counter_geo++;
			//if($counter_geo==30){$counter_geo=0;
			//sleep(1);
			//print $row."</br>";
			//print "sleep";}
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
				'telephone' 		=> $arr[12],
				'fax' 				=> $arr[13],
				//'longitude' 		=> round(floatval($geo[0]),6),
				//'latitude' 			=> round(floatval($geo[1]),6),
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
				$nuovo->save($obj);
				$row++;
			}
		}
		print "VENETO: Problems reading url. Recovered ".$row." records from the old database</br>";
		$row = NULL;
	}
	UpdateLog('Veneto', $date, $row, $lastmodified);
}
?> 