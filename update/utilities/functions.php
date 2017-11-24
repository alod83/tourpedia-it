<?php
function latlon_explode($point){
	preg_match('/\([0-9]+\.[0-9]+/',$point,$matches);
	$lon=str_replace("(", "", $matches[0]);
	$arr_point[]=$lon;
	preg_match('/[0-9]+\.[0-9]+\)/',$point,$matches);
	$lat=str_replace(")", "", $matches[0]);
	$arr_point[]=$lat;

	return $arr_point;

}
function get_op_hours($va,$arr){
	$count=0;
	$subdocument=array();
	for ($i=intval($va[0]);$i<=intval($va[1]);$i++){
		$count++;
		$string="";
		if ($count%2==0){
				if($arr[$i-1]!=="00:00" && $arr[$i]!=="00:00"){
					$string=$arr[$i-1]."-".$arr[$i];
					$hours[]=$string;
				}
			}

		switch ($count){
			case 6:
				$g="Lun";
				if(!empty($hours)){
					$subdocument[$g]=$hours;
					$hours=array();
				}
			case 12:
				$g="Mar";
				if(!empty($hours)){
					$subdocument[$g]=$hours;
					$hours=array();
				}
			case 18:
				$g="Mer";
				if(!empty($hours)){
					$subdocument[$g]=$hours;
					$hours=array();
				}
			case 24:
				$g="Gio";
				if(!empty($hours)){
					$subdocument[$g]=$hours;
					$hours=array();
				}
			case 30:
				$g="Ven";
				if(!empty($hours)){
					$subdocument[$g]=$hours;
					$hours=array();
				}
			case 36:
				$g="Sab";
				if(!empty($hours)){
					$subdocument[$g]=$hours;
					$hours=array();
				}
			case 42:
				$g="Dom";
				if(!empty($hours)){
					$subdocument[$g]=$hours;
					$hours=array();
				}
		}
		
	}
	return $subdocument;
}

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
		//Lazio
		case "LATINA":
			return "LT";
		case "ROMA":
			return "RM";
		case "VITERBO":
			return "VT";
		case "FROSINONE":
			return "FR";
		case "RIETI":
			return "RI";
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
		//Toscana
		case "AREZZO":
			return "AR";
		case "FIRENZE":
			return "FI";
		case "GROSSETO":
			return "GR";
		case "LIVORNO":
			return "LI";
		case "LUCCA":
			return "LU";
		case "MASSA-CARRARA":
			return "MS";
		case "PISA":
			return "PI";
		case "PISTOIA":
			return "PT";
		case "PRATO":
			return "PO";
		case "SIENA":
			return "SI";
		//Umbria
		case "PERUGIA":
			return "PG";
		case "TERNI":
			return "TR";
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
		default:
			return $province;
		
	}


}
function get_record($document,$mapping,$arr,$title=null)
{
	foreach($mapping as $k => $v)
	{
		$value = "";
		$subdocument=array();
		// check if the string contains numbers
		if(1 === preg_match('~[0-9]~', $v))
		{	
			if(strpos($v, '<') !== false)
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
					case 'lon':
						$value = latlon_explode($arr[$value]);
						break;
					case 'url':
						if((strpos($arr[$value], 'https://www.youtube.com/watch?v='))!== false){
							$value = "https://www.youtube.com/watch?v=".$arr[$value];
						}else{
							$value=$arr[$value];
						}
						break;
					case 'title':
						$indexes = explode(',', $va[0]);
						$value_list = array();
						for($i = 0; $i < count($indexes); $i++)
						{
							if (strpos($indexes[$i], ':') !== false )
							{
								$index = explode(':', $indexes[$i]);
								if(!empty($arr[$index[0]]))
									$value_list[] = array($title[$index[0]] => $arr[$index[0]]);
							}
							else
							{
								if($arr[$indexes[$i]] == "SI" || $arr[$indexes[$i]] == "YES" || $arr[$indexes[$i]] == 1 || $arr[$indexes[$i]] == "Alcune" || $arr[$indexes[$i]] == "Tutte" || $arr[$indexes[$i]] == 'Vero')
								{
									$value_list[] = $title[$indexes[$i]];
								}
							}
						}
						$value = $value_list;
						break;
					default:
						 $value = $arr[$value]; //in attesa di risolvere altri campi
				}
			}
			else if(strpos($v, ',') !== false)
			{	
				$va = explode(',',$v);
				foreach($va as $kva)
					if(array_key_exists($kva, $arr)){
						$value .= " ".$arr[intval($kva)];
					}
				$value = trim($value);

			}
			else if(strpos($v, 'or') !== false){
				$va = explode("or",$v);
				foreach($va as $kva){
					$subdocument[]=$arr[intval($kva)];

				}
				$value = $subdocument;
			}
			else if(strpos($v, '/') !== false)
			{
				$va = explode('/',$v);
				
				if(isset($arr[intval($va[0])]))
				{
					$value = str_replace('.', ',',$arr[intval($va[0])]);
					$div = $va[1];
					$value = floatval($value)/$div;
					
				}
			}
			
			else if(strpos($v, '-') !== false){
				$va = explode('-', $v);	
				$value=get_op_hours($va,$arr);
			}
			else{
				//esclude i casi in cui il record non è ben formato o vuoto (Trentino_2 Toscana_18, Toscana_19)
				if (array_key_exists(intval($v), $arr)){
					$value = $arr[intval($v)];
				}
			}
			
			
			switch($k)
			{
				case 'postal-code'	: 
				case 'rooms'		: 
				case 'beds'			: 
				case 'toilets'		:
				case 'suites'		:
				case 'codistat'		:
					$document[$k] = intval($value);
					break;
				case 'latitude'		: 
					if (!array_key_exists('longitude', $mapping)){
						$document['latitude']=round(floatval($value[1]),6);
						$document['longitude']=round(floatval($value[0]),6);
					}else{
						$document[$k]=round(floatval($value),6);
						
					}
					break;
				case 'longitude'	:
					$document[$k]=round(floatval($value),6);
					break;
				case 'languages'	:
					if(!empty($value))
					{
						if(!is_array($value))
						{
							$lang = str_replace('Inglese', 'English', $value);
							$lang = str_replace('Francese', 'French', $lang);
							$lang = str_replace('Spagnolo', 'Spanish', $lang);
							$lang = str_replace('Tedesco', 'German', $lang);
							$lang = str_replace('Portoghese', 'Portugese', $lang);
							$lang = str_replace('Arabo', 'Arabic', $lang);
							$lang = str_replace('Cinese', 'Chinese', $lang);
							$lang = str_replace('Giapponese','Japanese',$lang);
							$lang = str_replace('Russo', 'Russian', $lang);
							$document[$k] = explode(',', $lang);
						}
						else
						{
							$document[$k] = array();
							$lang_list = array('inglese' => 'English', 'francese' => 'French', 'tedesc' => 'German', 'spagnol' => 'Spanish', 'portoghese' => 'Portugese');
							for($i = 0; $i < count($value); $i++)
							{
								foreach($lang_list as $lang_k => $lang_v)
									if(strpos(strtolower($value[$i]),$lang_k) !== False )
										$document[$k][] = $lang_v;
							}
						}
						
					}
					break;
				case 'facilities'	:
				case 'sports equipment':
				case 'credit/debit cards':
				case 'location':
				case 'high season price':
				case 'low season price':
				case 'photo':
					if(!empty($value))
					{
						if(!is_array($value))
						{
							if(strpos($value, ',') !== false)
								$document[$k] = explode(',', $value);
							else if(strpos($value, '#') !== false)
								$document[$k] = explode('#', $value);
							else
								$document[$k] = explode('-', $value);
						}
						else
							$document[$k] = $value;
					}
					break;
				default				:
					if(!empty($value))
						$document[$k] = $value;
			}
		}
		else 
		{
			$document[$k] = $v;
		}
	}
	return $document;
	
}

function CSV($region,$date,$config, $nuovo, $vecchio, $url=null,$reg_acr_index=0){
	$lastmodified=null;
	$collect=null;
	$mapping = null;
	$collect = null;
	$dataset_feature = null;
	if(strpos($region, '_') !== false){
		preg_match('/[0-9]+$/',$region,$matches);
		$reg_acr=strtoupper(substr($region, 0,3).$matches[0]."_");
	}else{
		$reg_acr=strtoupper(substr($region, 0,3));
	}
	if(isset($config['url_attraction'])){
		$url = $url != null ? $url : $config['url_attraction'];
		$mapping = $config['attraction'];
		$dataset_feature = $config['dataset_attraction'];
		$collect = "Attrazioni";
	} 
	else if (isset($config['url_accommodation'])) {
		$url = $url != null ? $url : $config['url_accommodation'];
		$mapping = $config['accommodation'];
		$dataset_feature = $config['dataset_accommodation'];
		$collect = "Strutture";
	} 
	echo $url."\n";
	$coord = false;
	$curl = false;
	$ssl = false;
	$nonewline = false;
	$linesize = 0;
	$encoding = false;
	$separator = false;
	$lastmodified_number = false;
	$first_data_row = false;
	foreach($dataset_feature as $k => $v)
	{
		switch($k)
		{
			case 'separator':
				$separator = $v;
				break;
			case 'coord':
				$coord = ($v === 'True') ? true : false;
				break;
			case 'encoding':
				$encoding = $v;
				break;
			case 'lastmodified':
				$lastmodified_number = $v;
				break;
			case 'first_data_row':
				$first_data_row = $v;
				break;
			case 'curl':
				$curl = ($v === 'True') ? true : false;
				break;
			case 'ssl':
				$ssl= ($v === 'True') ? true : false;
				break;
			case 'nonewline':
				$nonewline= ($v === 'True') ? true : false;
				break;
			case 'linesize':
				$linesize = intval($v);
				break;
			
		}
	}
	if($curl)
	{
		echo "curl\n";
		$ch = curl_init(); 
    	curl_setopt($ch, CURLOPT_URL, $url); 
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	if($ssl)
    	{
    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    	} 
    	else
    	{
			// dico al server che sono un browser
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    	}
    	$handle = curl_exec($ch);
    	
    	file_put_contents($region, $handle);
    	$url = $region;
    	
	}
	
	if(($handle=fopen($url, "r"))!==FALSE){
		//$lastmodified = null;
		if($lastmodified_number)
		{
			$metadata = stream_get_meta_data($handle);
			$lastmodified = $metadata["wrapper_data"][$lastmodified_number];
		}
		$row=-1;
		$title = null;
		
		// if the file does not contain new line use alternative method
		
		
		$burst = 10000;
		if($nonewline)
			$burst = $linesize;
    	
		while(($arr=fgetcsv($handle,$burst,$separator))!==FALSE){
			$row++;
			
			//caso in cui il file inizia con righe vuote prima dei dati
			if($first_data_row){
				if ($row<$first_data_row)continue;
				
			}else{
				if($row==0){
					$title = $arr;
					continue;
				}
			}
			
			$id_index = $row+$reg_acr_index;
			$id=$reg_acr.$id_index;
			$document['_id'] = $id;
				
			if($encoding && $encoding == 'utf8'){
				$arr = array_map("utf8_encode", $arr);
			}
			$document=get_record($document,$mapping,$arr,$title);

			if($coord){
				$document=TrovaCoordinate($document, $vecchio);
			}
			
			$nuovo->insert($document);
		}
	}
	else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			$vecchio_id=$obj['_id'];
			if(strpos($vecchio_id, $reg_acr)!==false){

				$nuovo->insertOne($obj);
				$row++;
			}
		}
		print "Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog($region, $date, $row, $lastmodified, $collect);
	if($curl)
		unlink($region);
	return $row;
}

function ZIP($source,$date, $config, $nuovo, $vecchio)
{
	$zip = new ZipArchive;
	$url = $config['url_accommodation'];
	$nfiles = intval($config['dataset_accommodation']['number of files']);
	$fformat = $config['dataset_accommodation']['file format'];
	$tmpZipFileName = "Tmpfile.zip";
	if(file_put_contents($tmpZipFileName, fopen($url, 'r')))
	{
		if($zip->open($tmpZipFileName)!==FALSE)
		{
			$nrows = 0;
			for ($i=0; $i<$nfiles; $i++)
			{
				$filename = $zip->getNameIndex($i);
				$zip->extractTo('.', $filename);
				if($fformat === 'CSV')
					$nrows += CSV($source,$date, $config, $nuovo, $vecchio,$filename,$nrows);
			}	
		}
	 }
	 unlink($tmpZipFileName);
	 array_map('unlink', glob( "*.csv"));
}


?>
