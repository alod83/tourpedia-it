<?php

// prende tramite GET username e password

// verificare se username e password matchano con quelli sul database
// SELECT count(username) FROM utenti where username = '$username' and password = $password'";

// se il match è corretto, abilito le sessioni
session_start();
$_SESSION['username'] = $hotel_username;
header('Location: statistiche.html');

// se il match non è corretto ritorna errore ritorna username o password errati

// ritornare un json con errore o successo

?>