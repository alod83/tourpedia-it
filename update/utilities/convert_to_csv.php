<?php

require_once('csv.php');

function convertToCSV($region,$date, $config, $nuovo, $vecchio)
{
	$db_info = get_database($config);
	$url = $db_info['url'];
	$fformat = $db_info['fformat'];
	
	$df = parse_dataset_features($db_info['dataset_feature']);
	
	$ssl = $df['ssl'];
	$url = download_file($url, $region, $fformat,$ssl);
	
	// convert file to csv
	$csv = str_replace(' ', '', $region).".csv";
	system("ssconvert $url $csv");
	
	$result = parseCSV($csv,$db_info,$region,$df,$date,$nuovo,$vecchio);
	
	if(file_exists($url))
		unlink($url);
	if(file_exists($csv))
		unlink($csv);
	return $result;
}

?>