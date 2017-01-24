<?php

	function basicAPI($tipo)
	{
		header('Content-Type: application/json');
		// Includo il file config per effettuare la connessione al database
		require('../php/config.php');
		// Creo l'array dove inserirò i records
		$r1 = array();
		$connection = new MongoClient('mongodb://localhost:27017');
		$dbname = $connection->selectDB('Strutture');
		$log = $dbname->LOG;
		$giorno = date("d");
		$giorno = $giorno-1;
		if ($giorno<10){
			$giorno = "0".$giorno;
		}
		$date = date("/m/y");
		$date = $giorno.$date." 03:00:00";
		if ($tipo == "grafico"){
			$product_array = array(
				'date' => $date
			);
			$arr_log = $log->findOne($product_array);
			$array_valori[0]["data"][0] = array(floatval(0));
			$array_valori[0]["data"][1] = array(floatval(0));
			$array_valori[0]["data"][2] = array(floatval(0));
			$array_valori[0]["data"][3] = array(floatval(0));
			$array_valori[0]["data"][4] = array(floatval(0));
			$array_valori[0]["data"][5] = array(floatval(0));
			$array_valori[0]["data"][6] = array(floatval(0));
			$array_valori[0]["data"][7] = array(floatval(0));
			$array_valori[0]["data"][8] = array(floatval($arr_log['Friuli-Venezia Giulia structures']));
			$array_valori[0]["data"][9] = array(floatval($arr_log['Basilicata structures']));
			$array_valori[0]["data"][10] = array(floatval($arr_log['Liguria structures']));
			$array_valori[0]["data"][11] = array(floatval($arr_log['Trentino structures']));
			$array_valori[0]["data"][12] = array(floatval($arr_log['Umbria structures']));
			$array_valori[0]["data"][13] = array(floatval($arr_log['Marche structures']));
			$array_valori[0]["data"][14] = array(floatval($arr_log['Puglia structures']));
			$array_valori[0]["data"][15] = array(floatval($arr_log['Piemonte structures']));
			$array_valori[0]["data"][16] = array(floatval($arr_log['Emilia-Romagna structures']));
			$array_valori[0]["data"][17] = array(floatval($arr_log['Lombardia structures']));
			$array_valori[0]["data"][18] = array(floatval($arr_log['Veneto structures']));
			$array_valori[0]["data"][19] = array(floatval($arr_log['Toscana structures']));
			
			$records = select ($mysqli, "SELECT `open_data`.`Numero_strutture` AS `value_op`, `booking.com`.`Numero_strutture` AS `value_bo` 
										FROM `open_data` JOIN `booking.com` ON `open_data`.`Regione` = `booking.com`.`Regione`  
										ORDER BY `open_data`.`Numero_strutture`, `open_data`.`Regione`");
			$r1[0]["name"] = "Open data";
			$r1[0]["data"] = array();
			$r1[1]["name"] = "Booking.com";
			$r1[1]["data"] = array();
			for ($i=0; $i < count($records); $i++){
				$records[$i]['value_op'] = array(floatval($records[$i]['value_op']));
				if ($records[$i]["value_op"]==null) {
					$r1[0]["data"][$i] = 0;
				}
				else {
					$r1[0]["data"][$i] = $records[$i]["value_op"];
				}
				$records[$i]['value_bo'] = array(floatval($records[$i]['value_bo']));
				if ($records[$i]["value_bo"]==null) {
					$r1[1]["data"][$i] = 0;
				}
				else {
					$r1[1]["data"][$i] = $records[$i]["value_bo"];
				}
			}
		}
		if ($tipo == "mappa") {
			$r1 = select ($mysqli, "SELECT `open_data`.`Numero_strutture` AS `value`, `coordinate`.`Regione` AS `name`, `coordinate`.`Coordinate` AS `path` FROM `open_data` JOIN `coordinate` WHERE (`open_data`.`Regione` = `coordinate`.`Regione` OR (`open_data`.`Regione` = 'Trentino-Alto Adige' AND `coordinate`.`Regione` = 'Bolzano') OR (`open_data`.`Regione` = 'Trentino-Alto Adige' AND `coordinate`.`Regione` = 'Trento')) ORDER BY `name`");
			$tipo_mappa = $_GET['tipo_mappa'];
			// Se Strutture/Popolazione
			if ($tipo_mappa == "recpop") {
				$r2 = select ($mysqli, "SELECT `Popolazione` FROM `popolazione_territorio` ORDER BY `Regione`");
				for ($i=0; $i < count($r1); $i++){
					if ($r1[$i]["value"] != null) {
						$r1[$i]["value"] = floatval($r1[$i]["value"]);
						$r2[$i] = floatval($r2[$i]["Popolazione"]);
						$r1[$i]["value"] = round(($r1[$i]["value"]/$r2[$i]*10000))/10;
					}
				}
			}
			// Se Strutture/Territorio
			if ($tipo_mappa == "recterr") {
				$r2 = select ($mysqli, "SELECT `Territorio` FROM `popolazione_territorio` ORDER BY `Regione`");
				for ($i=0; $i < count($r1); $i++){
					if ($r1[$i]["value"] != null) {
						$r1[$i]["value"] = floatval($r1[$i]["value"]);
						$r2[$i] = floatval($r2[$i]["Territorio"]);
						$r1[$i]["value"] = round(($r1[$i]["value"]/$r2[$i]*100))/10;
					}
				}
			}		}
		return json_encode($r1);
		return json_encode($r2);
	}
		
?>