<?php

function Basilicata($date, $config, $nuovo, $vecchio){
	$url = $config['url_accommodation'];
	//$mapping = $config['accommodation'];
	$lastmodified = NULL;
	$ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	// dico al server che sono un browser
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    if (($output = curl_exec($ch))!=FALSE){ 
		$tmpFileName = 'tmpBasilicata.xls';
		// close curl resource to free up system resources 
		$metadata = stream_get_meta_data(fopen($url,"r"));
		$lastmodified = $metadata["wrapper_data"][3];
		curl_close($ch);
		$handle = fopen($tmpFileName, 'w');
		fwrite($handle, $output);
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CPa25a');
		$data->read($tmpFileName);
		$row=0;
		for($i=2; $i<$data->sheets[0]['numRows']; $i++){
				$row++;
				if(isset($data->sheets[0]['cells'][$i][2])) {$document['name']=utf8_encode($data->sheets[0]['cells'][$i][2]);} else {$document['name']=NULL;}
				if(isset($data->sheets[0]['cells'][$i][3])) {$document['description']=utf8_encode($data->sheets[0]['cells'][$i][3]);} else {$document['description']=NULL;}
				if(isset($data->sheets[0]['cells'][$i][5])) {$document['address']=utf8_encode($data->sheets[0]['cells'][$i][5]);} else {continue;}
				if(isset($data->sheets[0]['cells'][$i][4])) {$document['number of stars']=utf8_encode($data->sheets[0]['cells'][$i][4]);} else {$document['number of stars']=NULL;}
				if(isset($data->sheets[0]['cells'][$i][7])) {$document['telephone']=utf8_encode($data->sheets[0]['cells'][$i][7]);} else {$document['telephone']=NULL;}
				if(isset($data->sheets[0]['cells'][$i][8])) {$document['cellular phone']=utf8_encode($data->sheets[0]['cells'][$i][8]);} else {$document['cellular phone']=NULL;}
				if(isset($data->sheets[0]['cells'][$i][9])) {$document['fax']=utf8_encode($data->sheets[0]['cells'][$i][9]);} else {$document['fax']=NULL;}
				if(isset($data->sheets[0]['cells'][$i][10])) {$document['web site']=utf8_encode($data->sheets[0]['cells'][$i][10]);} else {$document['web site']=NULL;}
				if(isset($data->sheets[0]['cells'][$i][11])) {$document['email']=utf8_encode($data->sheets[0]['cells'][$i][11]);} else {$document['email']=NULL;}
				if(isset($data->sheets[0]['cells'][$i][13])) {$document['beds']=intval($data->sheets[0]['cells'][$i][13]);} else {$document['beds']=NULL;}
				if(isset($data->sheets[0]['cells'][$i][6])) {
					$pcp=explode(" ", $data->sheets[0]['cells'][$i][6], 2);
					$postal=$pcp[0];
					$cp=explode("(", $pcp[1]);
					$city = substr($cp[0], 1);
					if($cp[1]=="Mt)" OR $cp[1]=="MT)") $prov="MT";
					if($cp[1]=="Pz)" OR $cp[1]=="PZ)") $prov="PZ";
				} 
				else {
					$postal=NULL;
					$city=NULL;
					$prov=NULL;
				}
				$document['postal-code']=$postal;
				$document['city']=$city;
				$document['province']=$prov;
				$document['_id']='BAS'.$row;
				$document['region'] = 'Basilicata';
				$document=TrovaCoordinate($document, $vecchio);
				$nuovo->save($document);
		}
		print "BASILICATA: ".$row."\n";
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']=='Basilicata'){
				$nuovo->save($obj);
				$row++;
			}
		}
		print "BASILICATA: Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog("Basilicata", $date, $row, $lastmodified);
	unlink($tmpFileName);
}
?>