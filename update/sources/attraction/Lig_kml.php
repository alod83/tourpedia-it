<?php
function Lig_kml($region,$date, $config, $nuovo, $vecchio){
	$lastmodified=null;
	if(strpos($region, '_') !== false){
		preg_match('/[0-9]+$/',$region,$matches);
		$reg_acr=strtoupper(substr($region, 0,3).$matches[0]."_");
	}else{
		$reg_acr=strtoupper(substr($region, 0,3));
	}
	$collect="Attrazioni";
	$url = $config['url_attraction'];
	$mapping = $config['attraction'];
	echo($url);
	$zip = new ZipArchive;
	$tmpZipFileName = "Tmpfile.zip";
	if(file_put_contents($tmpZipFileName, fopen($url, 'r'))){
		if($zip->open($tmpZipFileName)!==FALSE){
			$metadata = stream_get_meta_data(fopen($url,"r"));
			$lastmodified = $metadata["wrapper_data"][3];
			$filename = $zip->getNameIndex(0);
			$zip->extractTo('.', $filename);
			//$file=fopen($filename, "r");
			if ($xml = simplexml_load_file($filename)){
				$row=0;
				foreach($xml->{'Document'}->{'Folder'}->{'Placemark'} as $attr){
					$row++;
					$document['_id'] = $reg_acr.$row;
					$document['description'] = $mapping['description'];
					$document['region'] = $mapping['region'];
					$document['country'] = 'Italy';
					foreach($attr[0]->{'ExtendedData'}->{'SchemaData'}->{'SimpleData'} as $field){
						$field_a = $field[0]->attributes();
						if ((isset($field_a['name'])) && (($field_a['name']=="SITO") || ($field_a['name']=="SITO_INTERNET"))){$document['url']=(string)($field[0]);}
						if ((isset($field_a['name'])) && $field_a['name']=="EMAIL"){$document['email']=(string)($field[0]);}
						if ((isset($field_a['name'])) && $field_a['name']=="FAX"){$document['fax']=(string)($field[0]);}
						if ((isset($field_a['name'])) && $field_a['name']=="TELEFONO"){$document['telephone']=(string)($field[0]);}
						if ((isset($field_a['name'])) && $field_a['name']=="INDIRIZZO"){$document['address']=(string)($field[0]);}
						if ((isset($field_a['name'])) && ($field_a['name']=="DENOMINAZIONE" || $field_a['name']=="NOME") ){$document['name']=(string)($field[0]);}
						if ((isset($field_a['name'])) && $field_a['name']=="COMUNE"){$document['city']=ucfirst((string)($field[0]));}
						if ((isset($field_a['name'])) && (($field_a['name']=="COD_ISTAT_COM") || ($field_a['name']=="COD_COMUNE"))){$document['codistat']=intval((string)($field[0]));}
						if ((isset($field_a['name'])) && $field_a['name']=="ORARIO"){$document['opening hours']=(string)($field[0]);}
						if ((isset($field_a['name'])) && $field_a['name']=="CAP"){$document['postal-code']=intval((string)($field[0]));}
						if ((isset($field_a['name'])) && $field_a['name']=="TIPO_MUSEO"){$document['category']=(string)($field[0]);}
					}
					if (isset($attr[0]->{'Point'}->{'coordinates'})){
						preg_match('/[0-9]+\.[0-9]+,[1-9]/', (string)($attr[0]->{'Point'}->{'coordinates'}),$matches);
						$lon=preg_replace("/,[1-9]/", "", $matches[0]);
						preg_match('/[0-9]+\.[0-9]+,0/', (string)($attr[0]->{'Point'}->{'coordinates'}),$matches);
						$lat=preg_replace("/,0/", "", $matches[0]);
						$document['latitude']=round(floatval($lat),6);
						$document['longitude']=round(floatval($lon),6);
					}else {
						$document['latitude']=NULL;
						$document['longitude']=NULL;
					}
					$nuovo->insert($document);
				}
			}
		}
	}else{
		$connection = new MongoClient('mongodb://localhost:27017');
		$cursor = $vecchio->find();
		$row = 0;
		foreach ($cursor as $obj){
			$vecchio_id=$obj['_id'];
			if(strpos($vecchio_id, $reg_acr)!==false){
				$nuovo->insert($obj);
				$row++;
			}
		}
		print "Problems reading url. Recovered ".$row." records from the old database\n";
		$row = NULL;
	}
	UpdateLog($region, $date, $row, $lastmodified, $collect);
	// cancello i file temporanei
	unlink($tmpZipFileName);
	array_map('unlink', glob( "*.kml"));
}
