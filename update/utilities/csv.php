<?php

require_once('functions.php');

function parseCSV($csv, $db_info,$region,$df,$date,$nuovo,$vecchio,$reg_acr_index=0)
{
	$url = $db_info['url'];
	$mapping = $db_info['mapping'];
	$collect = $db_info['collect'];
	$reg_acr = null;
	if(strpos($region, '_') !== false){
		preg_match('/[0-9]+$/',$region,$matches);
		$reg_acr=strtoupper(substr($region, 0,3).$matches[0]."_");
	}else{
		$reg_acr=strtoupper(substr($region, 0,3));
	}
	$lastmodified = null;
	
	if(($handle=fopen($csv, "r"))!==FALSE){
		//$lastmodified = null;
		if($df['lastmodified_number'])
		{
			$metadata = stream_get_meta_data($handle);
			$lastmodified = $metadata["wrapper_data"][$df['lastmodified_number']];
		}
		$row=-1;
		$title = null;
		
		// if the file does not contain new line use alternative method
		
		
		$burst = 10000;
		if($df['nonewline'])
			$burst = $df['linesize'];
    	
		while(($arr=fgetcsv($handle,$burst,$df['separator']))!==FALSE){
			$row++;
			
			//caso in cui il file inizia con righe vuote prima dei dati
			if($df['first_data_row']){
				if ($row<$df['first_data_row'])continue;
				
			}else{
				if($row==0){
					$title = $arr;
					continue;
				}
			}
			
			$id_index = $row+$reg_acr_index;
			$id=$reg_acr.$id_index;
			$document['_id'] = $id;
				
			if($df['encoding'] && $df['encoding'] == 'utf8'){
				$arr = array_map("utf8_encode", $arr);
			}
			
			$document=get_record($document,$mapping,$arr,$title);

			if($df['coord']){
				$document=TrovaCoordinate($document, $vecchio);
			}
			
			$nuovo->insert($document);
		}
	}
	else{
		//$connection = new MongoClient('mongodb://localhost:27017');
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
	if($df['curl'])
		unlink($url);
	return $row;
}

function CSV($region,$date,$config, $nuovo, $vecchio, $url=null,$reg_acr_index=0){
	$lastmodified=null;
	
	$db_info = get_database($config);
	$url = $db_info['url'];
	$mapping = $db_info['mapping'];
	$collect = $db_info['collect'];
	$fformat = $db_info['fformat'];
	
	//echo $url."\n";
	$df = parse_dataset_features($db_info['dataset_feature']);
	
	if($df['curl'])
		$url = download_file($url, $region, $fformat,$ssl);
	return parseCSV($url,$db_info,$region,$df,$date,$nuovo,$vecchio,$reg_acr_index);
}

?>