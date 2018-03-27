<?php

session_start();

if (isset($_POST["action"])){
	$action = $_POST["action"];
	$action();
}


// Récupére une ville en fonction des coordonnés récupérées grace à l'API d'HTML
// Stockage de la ville dans la SESSION
function setVille(){
	if(isset($_POST["longitude"]) && isset($_POST["latitude"])){
		$longitude = $_POST["longitude"];
		$latitude = $_POST["latitude"];

		$_SESSION['lat'] = $latitude; // Si la geolocalisation a marché 
		$_SESSION['long'] = $longitude;

		echo $_SESSION['lat'] . $_SESSION['long'];
	}
	
	



	
}


?>