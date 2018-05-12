<?php
include('../../api/config.php');

$subject = "Mail di prova numero 3";
/*query SQL per estrarre le email, le password*/
$sql = "SELECT username, password, email FROM `utenti` WHERE country=\"Italy\" AND sent=0 LIMIT 500";
$risultati = mysqli_query($connessione, $sql);
while ( $r = mysqli_fetch_assoc($risultati)){
	/*echo($r["username"]." ".$r["password"]." ".$r["email"]."<br>");*/
	$txt = "Salve! Queste sono le tue credenziali.<br>Nome utente: ".$r["username"]."<br>Password: ".$r["password"]."<br>Se stai leggendo questo testo, l'email è arrivata!<br>";
	$txt = wordwrap($txt,70);
	echo($txt);
	/*if(!mail($r["email"],$subject,$txt)){
		echo("Email non inviata");
	}else{
		echo("Email inviata a: ".$r["email"]."<br>");
		$sql2 = "UPDATE `utenti` SET sent=1 WHERE username=".$r["username"];
		$risultati2 = mysqli_query($connessione, $sql2);
	}*/
}

mysqli_close($connessione);
?>