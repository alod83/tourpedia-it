<?php
$addresses=array("simone.baldoni71@yahoo.com", "simone.baldoni71@gmail.com");
$subject = "Mail di prova numero 3";
$txt = "Se stai leggendo questo testo, l'email è arrivata!";

for($i = 0; $i<count($addresses); $i++){
	if(!mail($addresses[$i],$subject,$txt)){
		echo("Email non inviata");
	}else{
		echo("Email inviata a: ".$addresses[$i]."\n");
	}
}
?>