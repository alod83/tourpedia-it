<?php
function Italia($region,$date, $config, $nuovo, $vecchio){
	$lastmodified=null;
	$collect="Attrazioni";
	$url = $config['url_attraction'];
	$mapping = $config['attraction'];
	$dataset_feature = $config['dataset_attraction'];
	echo($url);
	$encoding = false;
	$separator = false;
	$lastmodified_number = false;
	foreach($dataset_feature as $k => $v)
	{
		switch($k)
		{
			case 'separator':
				$separator = $v;
				break;
			case 'encoding':
				$encoding = $v;
				break;
			case 'lastmodified':
				$lastmodified_number = $v;
				break;
		}
	}
	$zip = new ZipArchive;
	$tmpZipFileName = "Tmpfile.zip";
	if(file_put_contents($tmpZipFileName, fopen($url, 'r'))){
		if($zip->open($tmpZipFileName)!==FALSE){
			if($lastmodified_number){
				$metadata = stream_get_meta_data(fopen($url, 'r'));
				$lastmodified = $metadata["wrapper_data"][$lastmodified_number];
			}
			for($i=0; $i<8; $i++;){
				$filename = $zip->getNameIndex($i);
				if($filename=="Lista_siti_Unesco.csv"){
					$zip->extractTo('.', $filename);
				}
			}
			$handle=fopen($filename, "r");
			$row=-1;
			while(($arr=fgetcsv($handle,10000,$separator))!==FALSE){
				$row++;
				if($row==0){
					continue;
				}
				$id='ITA'.$row;
				$document['_id'] = $id;
					
				if($encoding && $encoding == 'utf8'){
					$arr = array_map("utf8_encode", $arr);
				}
				$document=get_record($document,$mapping,$arr);
	 			$nuovo->insertOne($document);
			}	
		}
	}else{
		$connection = new MongoDB\Client('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			$vecchio_id=$obj['_id'];
			if(strpos($vecchio_id, 'ITA')!==false){
				$nuovo->insertOne($obj);
				$row++;
			}
		}
		print "Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog($region, $date, $row, $lastmodified, $collect);
	// cancello i file temporanei
	unlink($tmpZipFileName);
	array_map('unlink', glob( "*.csv"));
}
?>