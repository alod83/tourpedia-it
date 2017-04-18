<?php
function Marche($date, $config, $nuovo, $vecchio){
	$url = $config['url_accommodation'];
	$mapping = $config['accommodation'];
	$arr_tot=array();
	if(($handle=fopen($url, "r"))!==FALSE){
		$metadata = stream_get_meta_data($handle);
		$lastmodified = $metadata["wrapper_data"][10];
		$row=-1;
		while(($arr=fgetcsv($handle,10000,";"))!==FALSE){
			$row++;
			if($row==0)continue;
			if(isset($arr[3])==FALSE){
				$row--;
				continue;
			}
			$arr = array_map("utf8_encode", $arr);
			$document['region'] = 'Marche';
			get_record($document, $mapping,$arr);
			
			$nuovo->save($document);
		}
		print "MARCHE: ".$row."\n";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Marche'){
				$nuovo->save($obj);
				$row++;
			}
		}
		print "MARCHE: Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog('Marche', $date, $row, $lastmodified);
}
?>