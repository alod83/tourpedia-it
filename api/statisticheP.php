<?php 
include("config.php");

$N = $_REQUEST['n'];
header('Content-Type: application/json; charset=utf-8'); 
$v=array();
if($N==1){
	$sql = "SELECT Periodo, Viaggio, Dato FROM `prenotazioni` WHERE Categoria=\"solo alloggio\" AND Viaggio<>\"tutti i tipi di viaggio\" AND Viaggio<>\"viaggio di lavoro\"";
	$risultati = mysqli_query($connessione, $sql);
	while ( $r = mysqli_fetch_assoc($risultati)){
		$v[] = array("Periodo"=>$r["Periodo"],"Viaggio"=>$r["Viaggio"],"Dato"=>floatval($r['Dato']));
	}
}else if($N==2){
	$sql = "SELECT Viaggio, Eta, Dato FROM `persone_in_vacanza_in_italia` WHERE Eta<>\"totale\" AND Viaggio=\"viaggio di vacanza\" ORDER BY Dato DESC";
	$sql1 = "SELECT Viaggio, Eta, Dato FROM `persone_in_vacanza_in_italia` WHERE Eta<>\"totale\" AND Viaggio=\"vacanza breve\" ORDER BY Dato DESC";
	$sql2 = "SELECT Viaggio, Eta, Dato FROM `persone_in_vacanza_in_italia` WHERE Eta<>\"totale\" AND Viaggio=\"vacanza lunga\" ORDER BY Dato DESC";
	$risultato = mysqli_query($connessione, $sql);
	$risultato1 = mysqli_query($connessione, $sql1);
	$risultato2 = mysqli_query($connessione, $sql2);
	$array;
	$v1;
	$v2;
	while ( $r = mysqli_fetch_assoc($risultato)){
		$array[] = array("Viaggio"=>$r["Viaggio"],"Eta"=>$r["Eta"],"Dato"=>floatval($r['Dato']));
	}
	$v=array_merge($v,$array);
	while ( $r = mysqli_fetch_assoc($risultato1)){
		$v1[] = array("Viaggio"=>$r["Viaggio"],"Eta"=>$r["Eta"],"Dato"=>floatval($r['Dato']));
	}
	$v=array_merge($v,$v1);
	while ( $r = mysqli_fetch_assoc($risultato2)){
		$v2[] = array("Viaggio"=>$r["Viaggio"],"Eta"=>$r["Eta"],"Dato"=>floatval($r['Dato']));
	}
	$v=array_merge($v,$v2);
}else if($N==3){
	$sql = "SELECT Mezzo, Dato FROM `mezzo_di_trasporto_utilizzato` WHERE Viaggio=\"tutti i tipi di viaggio\" AND Mezzo<>\"tutte le voci\" ORDER BY Dato DESC";
	$risultati = mysqli_query($connessione, $sql);
	while ( $r = mysqli_fetch_assoc($risultati)){
		$v[] = array("Mezzo"=>$r["Mezzo"],"Dato"=>round((floatval($r['Dato'])/54714*100), 2));
	}
}else if($N==4){
	$sql = "SELECT alloggio, viaggio, Dato FROM `tipo_di_struttura_scelta` WHERE alloggio<>\"totale\"";
	$risultati = mysqli_query($connessione, $sql);
	while ( $r = mysqli_fetch_assoc($risultati)){
		$v[] = array("Alloggio"=>$r["alloggio"],"Viaggio"=>$r["viaggio"],"Dato"=>floatval($r['Dato']));
	}
}else if($N==5){
	$sql = "SELECT Territorio, Periodo, Dato FROM `presenza_negli_alberghi` WHERE Territorio=\"Italia\" AND Esercizio=\"totale esercizi ricettivi\" AND Indicatori=\"presenze\" AND Periodo>=2010";
	$risultato = mysqli_query($connessione, $sql);
	while ( $r = mysqli_fetch_assoc($risultato)){
		$v[] = array("Territorio"=>$r["Territorio"],"Periodo"=>$r["Periodo"],"Dato"=>floatval($r['Dato']));
	}
}else if($N==6){
	$regione = $_REQUEST['reg'];
	$sql = "SELECT Territorio, Periodo, Dato FROM `presenza_negli_alberghi` WHERE Territorio=\"".$regione."\" AND Esercizio=\"totale esercizi ricettivi\" AND Indicatori=\"presenze\" AND Periodo>=2010";
	$risultato = mysqli_query($connessione, $sql);
	while ( $r = mysqli_fetch_assoc($risultato)){
		$v[] = array("Territorio"=>$r["Territorio"],"Periodo"=>$r["Periodo"],"Dato"=>floatval($r['Dato']));
	}
}else if($N==7){
	$provincia = $_REQUEST['prov'];
	$sql = "SELECT Sigla, Periodo, Dato FROM `presenza_negli_alberghi` WHERE Sigla=\"".$provincia."\" AND Esercizio=\"totale esercizi ricettivi\" AND Indicatori=\"presenze\" AND Periodo>=2010";
	$risultato = mysqli_query($connessione, $sql);
	while ( $r = mysqli_fetch_assoc($risultato)){
		$v[] = array("Sigla"=>$r["Sigla"],"Periodo"=>$r["Periodo"],"Dato"=>floatval($r['Dato']));
	}
}else if($N==8){
	$regione = $_REQUEST['reg'];
	$sql = "SELECT Destinazione, Viaggio, Dato FROM `regioni_destinazione` WHERE Destinazione=\"".$regione."\"";
	$risultato = mysqli_query($connessione, $sql);
	while ( $r = mysqli_fetch_assoc($risultato)){
		$v[] = array("Destinazione"=>$r["Destinazione"],"Viaggio"=>$r["Viaggio"],"Dato"=>floatval($r['Dato']));
	}
}else if($N==9){
	$regione = $_REQUEST['reg'];
	$sql = "SELECT Aeroporti, Periodo, Dato FROM `voli_aerei_per_aeroporto` WHERE Regione=\"".$regione."\" AND servizio=\"linea interni\" AND Nazionalita=\"Mondo\" AND ArrivoPartenza=\"arrivi\"";
	$risultato = mysqli_query($connessione, $sql);
	while ( $r = mysqli_fetch_assoc($risultato)){
		$v[] = array("Aeroporti"=>$r["Aeroporti"],"Periodo"=>$r["Periodo"],"Dato"=>floatval($r['Dato']));
	}
}

echo(json_encode($v));

mysqli_close($connessione);
?>