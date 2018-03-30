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

function getPhotos(){

	// Verification que la requete ajax soit passé avec un id d'utilisateur
	if(!isset($_POST['id'])){
		die('Erreur');
	}else{
		$id = $_POST['id'];
		$url = 'https://instagram.com/graphql/query/?query_id=17888483320059182&variables={"id":"' . $id . '","first":50,"after":null}';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
		// This is what solved the issue (Accepting gzip encoding)
		curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
		$response = curl_exec($ch);
		curl_close($ch);

	    echo "Resultats : ".$response;
	}
	
}


?>
