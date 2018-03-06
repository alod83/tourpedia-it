<?php 

include("config.php");

header('Content-Type: application/json'); 

$N = $_REQUEST['n'];
$connessione = apriConnessione("localhost", "root", null);
$database = mysqli_select_db($connessione, "tourpedia");
if($N==1){
	$sql = "SELECT Periodo, Viaggio, Dato FROM `prenotazioni` WHERE Categoria=\"solo alloggio\" AND Viaggio<>\"tutti i tipi di viaggio\" AND Viaggio<>\"viaggio di lavoro\"";
	$risultati = mysqli_query($connessione, $sql);
	$v;
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
	$v=array();
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
	$v;
	while ( $r = mysqli_fetch_assoc($risultati)){
		$v[] = array("Mezzo"=>$r["Mezzo"],"Dato"=>round((floatval($r['Dato'])/54714*100), 2));
	}
}else if($N==4){
	$sql = "SELECT alloggio, viaggio, Dato FROM `tipo_di_struttura_scelta` WHERE alloggio<>\"totale\"";
	$risultati = mysqli_query($connessione, $sql);
	$v;
	while ( $r = mysqli_fetch_assoc($risultati)){
		$v[] = array("Alloggio"=>$r["alloggio"],"Viaggio"=>$r["viaggio"],"Dato"=>floatval($r['Dato']));
	}
}
echo(json_encode($v));

chiudiConnessione($connessione);
?>