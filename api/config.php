<?php 

//APERTURA CONNESSIONE
function apriConnessione($server, $username, $password){
	$connessione = mysqli_connect($server, $username, $password);
	if(!$connessione) die("Connessione fallita:".mysqli_connect_error());
	return $connessione;
}

//CHIUSURA CONNESSIONE
function chiudiConnessione($connessione){
	mysqli_close($connessione);
}

?>