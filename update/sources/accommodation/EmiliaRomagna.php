<?php
function EmiliaRomagna($date, $config, $nuovo, $vecchio){
	$zip = new ZipArchive;
	$url = $config['url_accommodation'];
	$mapping = $config['accommodation'];
	$tmpZipFileName = "Tmpfile.zip";
	$lastmodified = NULL;
	if(file_put_contents($tmpZipFileName, fopen($url, 'r'))){
		if($zip->open($tmpZipFileName)!==FALSE){
			$metadata = stream_get_meta_data(fopen($url,"r"));
			$lastmodified = $metadata["wrapper_data"][7];
			$row=-1;
			for ($i=0; $i<9; $i++){
				$filename = $zip->getNameIndex($i);
				$zip->extractTo('.', $filename);
				$file=fopen($filename, "r");
				while(($arr=fgetcsv($file,10000,";"))!==FALSE){
					$row++;
					if($row==0)continue;
					if($arr[6]=='denominazione')continue;
					
					$document['_id']="EMI".$row;
					$document['region']='Emilia-Romagna';
					get_record($document, $mapping,$arr);
					
					$nuovo->save($document);
				}
			}
			print "EMILIA-ROMAGNA: ".$row."\n";
		}
	 }
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Emilia-Romagna'){
				$nuovo->save($obj);
				$row++;
			}
		}
		print "EMILIA-ROMAGNA: Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog('Emilia-Romagna', $date, $row, $lastmodified);
	// cancello i file temporanei
	unlink($tmpZipFileName);
	array_map('unlink', glob( "*.csv"));
}
?>