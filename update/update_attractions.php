<?php 
//require 'vendor/autoload.php';
require_once 'libraries/excel/reader.php';
//require 'libraries/SevenZipArchive.php';
include('utilities/mongo.php');
//include('utilities/functions.php');
include('utilities/csv.php');
include('enrichment/geocoding.php');
include('sources/attraction/Lig_kml.php');
Include('sources/attraction/xls.php');
ini_set('MAX_EXECUTION_TIME', -1);
ini_set('auto_detect_line_endings', TRUE);

$date = date("d/m/y H:i:s");
//$global_rows=-1;

// update databases: drop TEMP, move VECCHIO to TEMP, move NUOVO to VECCHIO
//$connection = new MongoDB\Client('mongodb://localhost:27017');
//$dbname = $connection->Attrazioni;
$connection = new MongoClient('mongodb://localhost:27017');
$dbname = $connection->selectDB('Attrazioni');
$nuovo = $dbname->NUOVO;
$vecchio = $dbname->VECCHIO;
$temp = $dbname->TEMP;
//$log = $dbname->LOG;
//$document["date"] = $date;
//$log->insertOne($document);
$drop = $temp->drop();
CopiaCollezione($vecchio, $temp);
$drop = $vecchio->drop();
CopiaCollezione($nuovo, $vecchio);
$drop = $nuovo->drop();

// now we are ready to update NUOVO
$ini_array = parse_ini_file("config.ini", true);

// read the list of sources, for each source call the related crawler
$ra = $ini_array["Sources"]["attraction_list"];
for($i = 0; $i < count($ra); $i++)
{
	$source = $ra[$i];
	echo "$source\n";
	$config = $ini_array[$source];
	$format = "";
	if(isset($ini_array[$source]['dataset_attraction']) &&
	   isset($ini_array[$source]['dataset_attraction']['format']))
	{
		$format = $ini_array[$source]['dataset_attraction']['format'];
	}
	if($format === 'CSV')
	{
		CSV($source,$date, $config, $nuovo, $vecchio);
	}else if ($ra[$i]=="Liguria_1" or $ra[$i]=="Liguria_2" or $ra[$i]=="Liguria_3" or $ra[$i]=="Liguria_4" or $ra[$i]=="Liguria_5" or $ra[$i]=="Liguria_6" ){
		Lig_kml($source,$date, $config, $nuovo, $vecchio);
	}else if ($ra[$i]=="Marche_1" or $ra[$i]=="Marche_2" or $ra[$i]=="Marche_3" or $ra[$i]=="Marche_4" or $ra[$i]=="Veneto_1" or $ra[$i]=="Veneto_2" or $ra[$i]=="Trentino_3"){
		xls_parse($source, $date, $config, $nuovo, $vecchio);
	}
	else{
		include('sources/attraction/'.$source.'.php');
		$source($date, $config, $nuovo, $vecchio);
	}
	
}

?>
