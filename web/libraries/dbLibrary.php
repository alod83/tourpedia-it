<?php

/******************************
 * Open a Connection to MySQL *
 ******************************/
function openDB($servername="localhost", $username="root", $password=NULL, $database="lpw"){
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $database);
    // Set UTF 8
    mysqli_query($conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	// Check connection
	if (!$conn) die("Connection failed: " . mysqli_connect_error());
	return $conn;
}


/******************************
 * Lettura dei records        *
 ******************************/
function select($conn,$sql){
	$resultSet = mysqli_query($conn, $sql);	
	// Controllo esito query
	if(!$resultSet){
		print("Select $sql failed: " . mysqli_error($conn));
		return(NULL);
	}	
	// Sposto i records in un array numerico
	while ($record = mysqli_fetch_assoc($resultSet)) $records[]=$record;
	// liberazione della memoria impegnata dal result set
	mysqli_free_result($resultSet);	
	return $records;
}


/******************************
 * Inserimento record       *
 ******************************/
function insert($conn, $sql){	
	$ok=mysqli_query($conn, $sql);	
	if (!$ok) print("Insertion failed: " . mysqli_error($conn));
}


/******************************
 * Close the Connection to MySQL *
 ******************************/
 function closeDB ($conn){
	mysqli_close($conn);
}

?>