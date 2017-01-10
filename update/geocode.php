<?php
$connection = new MongoClient('mongodb://localhost:27017');
$dbname = $connection->selectDB('Strutture');
$nuovo = $dbname->NUOVO;
$product_array = array(
		'latitude' => NULL
		);
$counter = 0;
$countot = intval(file_get_contents("counter.txt"));
while($document = $nuovo->findOne($product_array)){
	if($countot>2500)exit("Limite giornaliero raggiunto");
	print_r($document);
	$counter++;
	$countot++;
	file_put_contents("counter.txt", $countot);
	if($document['region']=='Basilicata'){
		$address=$document['address'].", ".$document['region'];
	}
	else{
		$address=$document['address'].", ".$document['city'];
	}
	if($geo = geocode($address)){
		print $countot."\n";
		print $geo[0].", ".$geo[1];
		print "\n";
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
if($nuovo->findOne($product_array)==FALSE){
	$vecchio = $dbname->VECCHIO;
	CopiaCollezione($nuovo, $vecchio);
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