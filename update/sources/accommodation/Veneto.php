<?php
function Veneto($date, $config, $nuovo, $vecchio){
	$url = $config['url_accommodation'];
	$mapping = $config['accommodation'];
	if(($handle=fopen($url, "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
		$lastmodified = $metadata["wrapper_data"][3];
		$row=-1;
		$counter_geo=0;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$document['_id']=				"VEN".$row;
			$document['region']=			'Veneto';
			get_record($document, $mapping,$arr);
			
			$document=TrovaCoordinate($document, $vecchio);
			$nuovo->save($document);
		}
		print "VENETO: ".$row."\n";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Veneto'){
				$nuovo->save($obj);
				$row++;
			}
		}
		print "VENETO: Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog('Veneto', $date, $row, $lastmodified);
}
?>