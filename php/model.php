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

function getPseudo(){
	if (!isset($_POST["url"])) {
		die('Erreur');
	}else{
		$url = $_POST["url"];

		$response = file_get_contents($url);
		echo($response);
	}
}

function getPhotos(){
	// Verification que la requete ajax soit passé avec un id d'utilisateur
	if(!isset($_POST['id'])){
		die('Erreur');
	}else{
		$id = $_POST['id'];
		$url = 'https://instagram.com/graphql/query/?query_id=17888483320059182&variables={"id":"' . $id . '","first":50,"after":null}';

		$response = file_get_contents($url);

	    echo($response);
	}
}

function getMeteo(){
	// Verification que la requete ajax soit passé avec un id d'utilisateur
	if(!isset($_POST['ville'])){
		die('Erreur');
	}else{
		$ville = $_POST['ville'];
		$url = 'http://api.openweathermap.org/data/2.5/weather?q='.$ville.'&APPID=1389b1a0fd9f33f0aad25e67cb48e130&units=metric';

		$response = file_get_contents($url);

	    echo($response);
	}
}


?>
