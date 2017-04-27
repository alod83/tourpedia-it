<?php

function get_province($province)
{
	$province = strtoupper($province);
	switch($province)
	{
		// Friuli
		case "GORIZIA":
			return "GO";
		case "UDINE":
			return "UD";
		case "PORDENONE":
			return "PN";
		case "TRIESTE":
			return "TS";
		// Liguria
		case "GENOVA":
			return "GE";
		case "IMPERIA":
			return "IM";
		case "LA SPEZIA":
			return "SP";
		case "SAVONA":
			return "SV";
		// Piemonte
		case "ALESSANDRIA":
			return "AL";
		case "ASTI":
			return "AT";
		case "BIELLA":
			return "BI";
		case "CUNEO":
			return "CN";
		case "TORINO":
			return "TO";
		case "VERBANO-CUSIO-OSSOLA":
			return "VB";
		case "VERCELLI":
			return "VC";
		// Puglia
		case "BARI":
			return "BA";
		case "BARLETTA-ANDRIA-TRANI":
			return "BT";
		case "BRINDISI":
			return "BR";
		case "FOGGIA":
			return "FG";
		case "LECCE":
			return "LE";
		case "TARANTO":
			return "TA";
		// Veneto
		case "BELLUNO":
			return "BL";
		case "PADOVA":
			return "PD";
		case "ROVIGO":
			return "RO";
		case "TREVISO":
			return "TV";
		case "VENEZIA":
			return "VE";
		case "VERONA":
			return "VE";
		case "VICENZA":
			return "VI";
	}

}
function get_record(&$document,$mapping,$arr)
{
	foreach($mapping as $k => $v)
	{
		$value = "";
		// check if the string contains numbers
		if(1 === preg_match('~[0-9]~', $v))
		{	
			if(strpos($v, ',') !== false)
			{
				$va = explode(',',$v);
				foreach($va as $kva)
					$value .= " ".$arr[intval($kva)];
				$value = trim($value);
			}
			else if(strpos($v, '/') !== false)
			{
				$va = explode('/',$v);
				
				$value = str_replace('.', ',',$arr[intval($va[0])]);
				$div = $va[1];
				$value = floatval($value)/$div;
			}
			else if(strpos($v, '<') !== false)
			{
				$va = explode('<', $v);
				$value = intval($va[0]);
				switch($va[1])
				{
					case 'province': 
						$value = get_province($arr[$value]);
						break;
					case 'utf8':
						$value = utf8_encode($arr[$value]);
						break;
				}
			}
			else
				$value = $arr[intval($v)];
			
			switch($k)
			{
				case 'postal-code'	: 
				case 'rooms'		: 
				case 'beds'			: 
				case 'toilets'		:
				case 'suites'		:
					$document[$k] = intval($value);
					break;
				case 'latitude'		: 
				case 'longitude'	:
					$document[$k]=round(floatval($value),6);
					break;
				default				:
					$document[$k] = $value;
			}
		}
		else 
		{
			$document[$k] = $v;
		}
	}
}

function CSV($region,$date, $config, $nuovo, $vecchio){
	$url = $config['url_accommodation'];
	echo $url;
	$mapping = $config['accommodation'];
	
	$coord = false;
	$encoding = false;
	$separator = false;
	$lastmodified_number = false;
	foreach($config['dataset_accommodation'] as $k => $v)
	{
		switch($k)
		{
			case 'separator':
				$separator = $v;
				break;
			case 'coordinates':
				$coord = $v === 'True' ? true : false;
				break;
			case 'encoding':
				$encoding = $v;
				break;
			case 'lastmodified':
				$lastmodified_number = $v;
				break;
		}
	}
	/*$separator = $config['sep_accommodation'];
	$coord = false;
	if(isset($config['coord_accommodation']))
		$coord = $config['coord_accommodation'] === 'True' ? true : false;
	$encoding = false;
	if(isset($config['encoding_accommodation']))
		$encoding = $config['encoding_accommodation'];
	$lastmodified_number = false;
	if(isset($config['lastmodified_accommodation']))
		$lastmodified_number = $config['lastmodified_accommodation'];*/
	
	$acronym = strtoupper(substr($region, 0,3));
	if(($handle=fopen($url, "r"))!==FALSE){
		$lastmodified = null;
		if($lastmodified_number)
		{
			$metadata = stream_get_meta_data($handle);
			$lastmodified = $metadata["wrapper_data"][$lastmodified_number];
		}
		$row=-1;
		$prov=NULL;
		while(($arr=fgetcsv($handle,10000,$separator))!==FALSE){
			$row++;
			if($row==0)continue;
			$document['_id'] = $acronym.$row;
				
			if($encoding && $encoding == 'utf8')
				$arr = array_map("utf8_encode", $arr);
			get_record($document, $mapping,$arr);
			if($coord)
				$document=TrovaCoordinate($document, $vecchio);
			
			$nuovo->save($document);
		}
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			if($obj['region']==$region){
				$nuovo->save($obj);
				$row++;
			}
		}
		print "Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog($region, $date, $row, $lastmodified);
}
?>