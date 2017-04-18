<?php
function Umbria($date, $config, $nuovo, $vecchio){
	$url = $config['url_accommodation'];
	$mapping = $config['accommodation'];
	if(($handle=fopen($url, "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$document['_id']=				"UMB".$row;
			$document['region']=			'Umbria';
			get_record($document, $mapping,$arr);
			
			//$document=TrovaCoordinate($document, $vecchio);
			$nuovo->save($document);
		}
		print "UMBRIA: ".$row."\n";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Umbria'){
				$nuovo->save($obj);
				$row++;
			}
		}
		print "UMBRIA: Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	$html = file_get_html('http://dati.umbria.it/dataset/strutture-ricettive/resource/062d7bd6-f9c6-424e-9003-0b7cb3744cab');
	$lastmodified=$html->find('table',0)->find('td',0);
	$lastmodified=substr($lastmodified,4,-5);
	UpdateLog('Umbria', $date, $row, $lastmodified);
}
?>