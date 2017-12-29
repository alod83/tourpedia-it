<?php

function ZIP($source,$date, $config, $nuovo, $vecchio)
{
	$zip = new ZipArchive;
	$db_info = get_database($config);
	$url = $db_info['url'];
	$mapping = $db_info['mapping'];
	$collect = $db_info['collect'];
	$db = $db_info['db'];
	$df = parse_dataset_features($db_info['dataset_feature']);
	
	$nfiles = intval($config['dataset_'.$db]['number of files']);
	$ndir = intval($config['dataset_'.$db]['number of internal directories']);
	$fformat = $config['dataset_'.$db]['file format'];
	
	$tmpZipFileName = "Tmpfile.zip";
	if(file_put_contents($tmpZipFileName, fopen($url, 'r')))
	{
		if($zip->open($tmpZipFileName)!==FALSE)
		{
			$nrows = 0;
			for ($i=0; $i<$nfiles; $i++)
			{
				$filename = $zip->getNameIndex($i);
				if($ndir > 0)
				{
					$base_dir = str_replace(' ', '', $source);
					$zip->extractTo($base_dir);
					$filename = "";
					$current_dir = $base_dir."/".$zip->getNameIndex($i);
					
					for($j = 0; $j < $ndir; $j++)
					{
						$list_dir = scandir($current_dir);
						// first two dir are . and ..
						$current_dir = $current_dir.$list_dir[2];						
					}
					$list_files = scandir($current_dir);
					foreach($list_files as $file)
					{
						$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
						if($ext == strtolower($fformat))
						{
							$filename = $current_dir."/".$file;
							break;
						}
					}
							
				}
				else
					$zip->extractTo('.', $filename);
				if($fformat === 'CSV')
					$nrows += parseCSV($filename, $db_info,$source,$df,$date,$nuovo,$vecchio,$nrows);
					//$nrows += CSV($source,$date, $config, $nuovo, $vecchio,$filename,$nrows);
			}	
		}
	 }
	 unlink($tmpZipFileName);
	 array_map('unlink', glob( "*.csv"));
}

?>