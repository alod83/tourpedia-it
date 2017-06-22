<?php
function Trentino_3($date, $config, $nuovo, $vecchio){
	$collect = "Attrazioni";
	$url = $config['url_attraction'];
	echo($url);
	$lastmodified = NULL;
	$ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	// dico al server che sono un browser
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    if (($output = curl_exec($ch))!=FALSE){ 
		$tmpFileName = 'tmpTrentino_3.xls';
		// close curl resource to free up system resources 
		$metadata = stream_get_meta_data(fopen($url,"r"));
		$lastmodified = $metadata["wrapper_data"][3];
		curl_close($ch);
		$handle = fopen($tmpFileName, 'w');
		fwrite($handle, $output);
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CPa25a');
		$data->read($tmpFileName);
		$row=0;
		for($i=6; $i<$data->sheets[0]['numRows']; $i++){
				$row++;
				if(isset($data->sheets[0]['cells'][$i][2])) {$document['city']=utf8_encode($data->sheets[0]['cells'][$i][2]);} else {$document['city']=NULL;}
				if(isset($data->sheets[0]['cells'][$i][3])) {$document['name']=utf8_encode($data->sheets[0]['cells'][$i][3]);} else {$document['name']=NULL;}
				if(isset($data->sheets[0]['cells'][$i][5])) {$document['address']=utf8_encode($data->sheets[0]['cells'][$i][5]);} else {$document['address']=NULL;}
				$document['_id']='TRE3_'.$row;
				$document['region'] = 'Trentino';
				$document['description'] = "Osterie tipiche";
				$document=TrovaCoordinate($document, $vecchio);
				$nuovo->insertOne($document);
		}
		//print "BASILICATA: ".$row."\n";
	}
	else{
		$connection = new MongoDB\Client('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			$vecchio_id=$obj['_id'];
			if(strpos($vecchio_id, 'TRE3_')!==false){
				$nuovo->insertOne($obj);
				$row++;
			}
		}
		print "Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog("Trentino_3", $date, $row, $lastmodified, $collect);
	unlink($tmpFileName);
}
?>