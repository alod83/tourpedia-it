<?php
function Friuli($date, $config, $nuovo, $vecchio){
	$url = $config['url_accommodation'];
	$mapping = $config['accommodation'];
	if(($handle=fopen($url, "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
		$lastmodified = $metadata["wrapper_data"][8];
		if (preg_match('/Last-Modified:(.*?)/i', $lastmodified, $matches))
		$row=-1;
		$prov=NULL;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$document['_id']=				"FRI".$row;
			$document['region']=			'Friuli-Venezia Giulia';
			
			get_record($document, $mapping,$arr);
			$document=TrovaCoordinate($document, $vecchio);
			$nuovo->save($document);
		}
		print "FRIULI-VENEZIA GIULIA: ".$row."\n";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Friuli-Venezia Giulia'){
				$nuovo->save($obj);
				$row++;
			}
		}
		print "FRIULI-VENEZIA GIULIA: Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog('Friuli-Venezia Giulia', $date, $row, $lastmodified);
}
?>