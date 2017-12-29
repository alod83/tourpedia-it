<?php 

require_once 'libraries/excel/reader.php';
require 'libraries/simple_html_dom.php';
include('utilities/mongo.php');
include('utilities/csv.php');
include('utilities/zip.php');
include('utilities/convert_to_csv.php');
include('enrichment/geocoding.php');

ini_set('MAX_EXECUTION_TIME', -1);
ini_set('auto_detect_line_endings', TRUE);

$date = date("d/m/y H:i:s");

// update databases: drop TEMP, move VECCHIO to TEMP, move NUOVO to VECCHIO
$connection = new MongoClient('mongodb://localhost:27017');
$dbname = $connection->selectDB('Strutture');
$nuovo = $dbname->NUOVO;
$vecchio = $dbname->VECCHIO;
$temp = $dbname->TEMP;
$log = $dbname->LOG;
$document["date"] = $date;
$log->save($document);
$drop = $temp->drop();
CopiaCollezione($vecchio, $temp);
$drop = $vecchio->drop();
CopiaCollezione($nuovo, $vecchio);
$drop = $nuovo->drop();

// now we are ready to update NUOVO
$ini_array = parse_ini_file("config.ini", true);

// read the list of sources, for each source call the related crawler
$ra = $ini_array["Sources"]["accommodation_list"];
for($i = 0; $i < count($ra); $i++)
{
	$source = $ra[$i];
	echo "$source\n";
	$config = $ini_array[$source];
	$format = "";
	if(isset($ini_array[$source]['dataset_accommodation']) &&
	   isset($ini_array[$source]['dataset_accommodation']['format']))
		$format = $ini_array[$source]['dataset_accommodation']['format'];
	if($format === 'CSV')
	{
		echo "CSV\n";
		CSV($source,$date, $config, $nuovo, $vecchio);
	}
	else if($format === 'GeoJson')
	{
		GeoJson($source,$date,$config,$nuovo,$vecchio);
    }
	else if(strpos($format,'ZIP') !== FALSE)
	{
		echo "ZIP\n";
		ZIP($source,$date, $config, $nuovo, $vecchio);
	}
	else if(strpos($format,'XLS') !== FALSE)
	{
		echo "XLS\n";
		convertToCSV($source,$date, $config, $nuovo, $vecchio);
	}
	else
	{
		include('sources/accommodation/'.$source.'.php');
		$source($date, $config, $nuovo, $vecchio);
	}
}

?> 