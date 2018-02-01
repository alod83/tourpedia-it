<?php
$connection = new MongoClient('mongodb://localhost:27017');
$dbname = $connection->selectDB('Strutture');
$nuovo = $dbname->NUOVO;
$old = $dbname->VECCHIO;
$product_array = array(
		'$or' => array(array('latitude' => NULL),
		array('latitude' => array('$exists' => 'false')))
		);
$counter = 0;
$countot = intval(file_get_contents("counter.txt"));
echo $countot."\n";
while($document = $nuovo->findOne($product_array)){
	
	if(empty($document['address']))
		continue;
		
	// search in the old database
	$geo = null;
	$document_old = $old->findOne(array('_id' => $document['_id']));
	if(!empty($document_old))
	{
		$geo[0] = $document_old['latitude'];
		$geo[1] = $document_old['longitude'];
	}
	else
	{
		if($countot>2500){
			file_put_contents("counter.txt", "0");
		}
		else
		{
			$counter++;
			$countot++;
			file_put_contents("counter.txt", $countot);
			$address=$document['address'];
	
			if(isset($city))
				$address=$document['address'].", ".$document['city'];
			$geo = geocode($address);
			print $countot."\n";
			print $geo[0].", ".$geo[1];
			
		}
	}
	$document['latitude']=round(floatval($geo[0]),6);
	$document['longitude']=round(floatval($geo[1]),6);
	$document['enrichment']="latitude, longitude";
	
	$nuovo->save($document);
	if ($counter==40){
		sleep(1);
		$counter=0;
	}
}

function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=AIzaSyD64knRCOQVHjMOkp86vuBO_njh_mhWHw0";
	
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
	
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){

        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        //$formatted_address = $resp['results'][0]['formatted_address'];
         
        // verify if data is complete
        if($lati && $longi/* && $formatted_address*/){
			
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi 
                    //$formatted_address
                );
				
            return $data_arr;
             
        }else{
            return false;
        }
         
    }else{
        return false;
    }
}

function CopiaCollezione($collPartenza, $collArrivo) {
	$cursor = $collPartenza->find();
	$num_docs = $cursor->count();
	if ($num_docs>0) {
		foreach ($cursor as $obj)
		{
			$collArrivo->save($obj);
		}
	}
}
?>