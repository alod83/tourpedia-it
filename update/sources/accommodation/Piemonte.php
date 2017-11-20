<?php
function Piemonte($date, $config, $nuovo, $vecchio){
	$url = $config['url_accommodation'];
	//$mapping = $config['accommodation'];
	$ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);
	if(!empty($output)){
		$row=-1;
		$arr=explode('";"', $output);
		for($i=18; $i<count($arr)-1; $i=$i+18){
			$row++;
			if($row==0)continue;
			switch($arr[$i+1]){
				case "ALESSANDRIA":
					$prov = "AL";
					break;
				case "ASTI":
					$prov = "AT";
					break;
				case "BIELLA":
					$prov = "BI";
					break;
				case "CUNEO":
					$prov = "CN";
					break;
				case "TORINO":
					$prov = "TO";
					break;
				case "VERBANO-CUSIO-OSSOLA":
					$prov = "VB";
					break;
				case "VERCELLI":
					$prov = "VC";
					break;
			}
			$document['_id']=				"PIE".$row;
			$document['name']=				$arr[$i+2];
			$document['description']=		$arr[$i+7];
			$document['address']=			$arr[$i+3];
			$document['city']=				$arr[$i+5];
			$document['province']=			$prov;
			$document['region']=			'Piemonte';
			$document['postal-code']=		intval($arr[$i+4]);
			$document['number of stars']=	$arr[$i+8];
			$document['email']=				$arr[$i+11];
			$document['telephone']=			$arr[$i+9];
			$document['fax']=				$arr[$i+10];
			$document['rooms']=				intval($arr[$i+13]);
			$document['beds']=				intval($arr[$i+14]);
			$document['toilets']=			intval($arr[$i+15]);
			$document=TrovaCoordinate($document, $vecchio);
			$nuovo->save($document);
		}
		print "PIEMONTE: ".$row."\n";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Piemonte'){
				$nuovo->save($obj);
				$row++;
			}	
		}
		print "PIEMONTE: Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	$html = file_get_html('http://www.dati.piemonte.it/catalogodati/dato/100995-.html');
	$lastmodified=$html->find('table[class=tabella_item]',1)->find('td',1);
	$lastmodified=substr($lastmodified,80,-10);
	UpdateLog('Piemonte', $date, $row, $lastmodified);
}
?>