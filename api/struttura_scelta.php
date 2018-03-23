<?php 
session_start();
include("config.php");

header('Content-Type: application/json; charset=utf-8'); 

$array = $_SESSION;
echo(json_encode($array));

mysqli_close($connessione);
?>