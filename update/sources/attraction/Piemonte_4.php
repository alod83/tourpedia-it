<?php
function Piemonte_4($date, $config, $nuovo, $vecchio){
	$tmpsevenZipFileName="Tmpfile.tar.7z";
	$sevenzip = new \eu\maxschuster\sevenzip\SevenZipArchive('7z');
	/*$url = $config['url_attraction'];
	echo($url);
	$mapping = $config['attraction'];
	$lastmodified = NULL;
	if(file_put_contents($tmpsevenZipFileName, fopen($url, 'r'))){
		//if($sevenzip->open($tmpsevenZipFileName)!==FALSE){
			$metadata = stream_get_meta_data(fopen($url,"r"));
			$lastmodified = $metadata["wrapper_data"][13];
			$row=-1;
			$filename = $sevenzip->get(2);	
			$sevenzip->extractTo('.', $filename);
			$file=fopen($filename, "r");
			while(($arr=fgetcsv($file,10000,";"))!==FALSE){
					$row++;
					if($row==0)continue;
					$document['_id']="PIE4_".$row;
					$document=get_record($document, $mapping,$arr);
					$document=TrovaCoordinate($document, $vecchio);
					$nuovo->insertOne($document);
			}
			//print "Piemonte_4: ".$row."\n";
		//}
	 }
	else{
		$connection = new MongoDB\Client('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			$vecchio_id=$obj['_id'];
			if(strpos($vecchio_id, 'PIE4_')!==false){
				$nuovo->insertOne($obj);
				$row++;
			}
		}
		print "Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog('Piemonte_4', $date, $row, $lastmodified);
	// cancello i file temporanei
	unlink($tmpsevenZipFileName);
	array_map('unlink', glob( "*.csv"));*/
}
?>