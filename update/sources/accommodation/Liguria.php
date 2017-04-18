<?php

function Liguria($date, $config, $nuovo, $vecchio){
	$url = $config['url_accommodation'];
	$mapping = $config['accommodation'];
	$ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
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
		if(($handle=fopen($tmpFileName, "r"))!==FALSE){
			$metadata = stream_get_meta_data($handle);
			$row=-1;
			while(($arr=fgetcsv($handle,10000,";"))!==FALSE){
				$row++;
				if($row==0)continue;
				$document['_id']=				"LIG".$row;
				$document['region']=			'Liguria';
				get_record($document, $mapping,$arr);
				
				$document=TrovaCoordinate($document, $vecchio);
				$nuovo->save($document);
			}
			print "LIGURIA: ".$row."\n";
		}
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Liguria'){
				$nuovo->save($obj);
				$row++;
			}
		}
		print "LIGURIA: Problems reading url. Recovered ".$row." records from the old database\n";
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
?>