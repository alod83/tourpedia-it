<?php 

$database = "tourpedia";
$password=NULL;
$username="root";
$server="localhost";

$connessione = mysqli_connect($server, $username, $password);
if(!$connessione) die("Connessione fallita:".mysqli_connect_error($connessione));
$database = mysqli_select_db($connessione, "tourpedia");

?>