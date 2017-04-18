<?php
function Lombardia($date, $config, $nuovo, $vecchio){
	$url = $config['url_accommodation'];
	$mapping = $config['accommodation'];
	if(($handle=fopen($url, "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
		$lastmodified = $metadata["wrapper_data"][8];
		$row=-1;
		while(($arr=fgetcsv($handle,10000,","))!==FALSE){
			$row++;
			if($row==0)continue;
			$arr = array_map("utf8_encode", $arr);
			
			$document['_id']=				"LOM".$row;
			$document['region']=			'Lombardia';
			get_record($document, $mapping,$arr);
			
			$nuovo->save($document);
		}
		print "LOMBARDIA: ".$row."\n";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Lombardia'){
				$nuovo->save($obj);
				$row++;
			}
		}
		print "LOMBARDIA: Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog('Lombardia', $date, $row, $lastmodified);
}
?>