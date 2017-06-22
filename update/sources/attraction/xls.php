<?php
function xls_get_record($data, $mapping, $document, $i){
	foreach ($mapping as $k => $v) {
		$value = "";
		if(1 === preg_match('~[0-9]~', $v)){
			if(strpos($v, ',') !== false){
				$va = explode(',',$v);
				$adr="";
				$cv="";	
				$toponimo="";
				if(count($va)==3){
					if(isset($data->sheets[0]['cells'][$i][intval($va[0])])){$toponimo=utf8_encode($data->sheets[0]['cells'][$i][intval($va[0])]);}
					if(isset($data->sheets[0]['cells'][$i][intval($va[1])])) {$adr=utf8_encode($data->sheets[0]['cells'][$i][intval($va[1])]);}
					if(isset($data->sheets[0]['cells'][$i][intval($va[2])])) {$cv=utf8_encode($data->sheets[0]['cells'][$i][intval($va[2])]);}
				}else if (count($va)==2){	
					if(isset($data->sheets[0]['cells'][$i][intval($va[0])])){$adr=utf8_encode($data->sheets[0]['cells'][$i][intval($va[0])]);}
					if(isset($data->sheets[0]['cells'][$i][intval($va[1])])) {$cv=utf8_encode($data->sheets[0]['cells'][$i][intval($va[1])]);}
				}
				$value= trim($toponimo." ".$adr." ".$cv);
			}else if(strpos($v, '<') !== false){
				$va = explode('<', $v);
				$index = intval($va[0]);
				if(isset($data->sheets[0]['cells'][$i][intval($index)])){
					$value=utf8_encode($data->sheets[0]['cells'][$i][intval($index)]);
					$value = get_province($value);
				}
			}else{
				if(isset($data->sheets[0]['cells'][$i][intval($v)])){$value=utf8_encode($data->sheets[0]['cells'][$i][intval($v)]);}
			}

			switch ($k) {
				case 'codistat':
				case 'postal-code':
					$document[$k] =intval($value);
					break;
				case 'latitude':
				case 'longitude':
					$document[$k] = round(floatval($value),6);
					break;
								
				default:
					$document[$k] = $value;
			}
		}else{
			$document[$k]=$v;
		}
	}

	return $document;
}
function xls_parse($region, $date, $config, $nuovo, $vecchio){
	$lastmodified = NULL;
	if(strpos($region, '_') !== false){
		preg_match('/[0-9]+$/',$region,$matches);
		$reg_acr=strtoupper(substr($region, 0,3).$matches[0]."_");
	}else{
		$reg_acr=strtoupper(substr($region, 0,3));
	}
	$url = $config['url_attraction'];
	$mapping = $config['attraction'];
	$dataset_feature = $config['dataset_attraction'];
	$collect = "Attrazioni";
	echo($url);
	$lastmodified_number = false;
	$first_data_row = false;
	$coord = false;
	foreach($dataset_feature as $k => $v){
		switch ($k) {
			case 'lastmodified':
				$lastmodified_number = $v;
				break;
			case 'first_data_row':
				$first_data_row = $v;
				break;
			case 'coord':
				$coord = ($v === 'True') ? true : false;
				break;
			}
	}
	$ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	// dico al server che sono un browser
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    if (($output = curl_exec($ch))!=FALSE){ 
		$tmpFileName = $region.'xls';
		// close curl resource to free up system resources 
		curl_close($ch);
		if($lastmodified_number)
		{
			$metadata = stream_get_meta_data(fopen($url,"r"));
			$lastmodified = $metadata["wrapper_data"][$lastmodified_number];
		}
		$handle = fopen($tmpFileName, 'w');
		fwrite($handle, $output);
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('UTF8');
		$data->read($tmpFileName);
		$row=0;
		for($i=$first_data_row; $i<$data->sheets[0]['numRows']; $i++){
				$row++;
				$document['_id']=$reg_acr.$row;
				$document=xls_get_record($data,$mapping,$document,$i);

				if($coord){
				$document=TrovaCoordinate($document, $vecchio);
				}
				
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
			if(strpos($vecchio_id, $reg_acr)!==false){
				$nuovo->insertOne($obj);
				$row++;
			}
		}
		print "Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog($region, $date, $row, $lastmodified, $collect);
	unlink($tmpFileName);
}
?>