<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>InstaMeteo</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="css/main.css" rel="stylesheet">

    <script type="text/javascript" src="js/instafeed.min.js"></script>

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">

      	<div class="col-lg-3">
	      	<span id="instagramLogo"></span>
	        <a class="navbar-brand js-scroll-trigger" href="#page-top">InstaMeteo</a>
	    </div>

        <div id="meteo" class="col-lg-3">
<?php

            if(isset($_SESSION['lat']) && isset($_SESSION['long'])){ 
                $lat = $_SESSION['lat']; // Si la geolocalisation a marché    
                $long = $_SESSION['long'];
            }else{
                $long  = 2.3466110229492188; // Sinon on prend les coordonnées de Paris
                $lat  = 48.858842286992044;
            }


            // Si on a pas de ville déja localisé
            if(!isset($_SESSION['ville'])){

            	// Localisation, pour récupérer la ville en fonction de la latitude et la longitude
            	$geocode=file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&sensor=false'); 

	            // On regarde parmis toutes les localisations de l'API si une ville correspond
	            $output= json_decode($geocode);

            	// Si on a des resultats pour cette localisation
	            if(isset($output->results[0])){

	            	// On cherche une ville qui correspond
            		for($j=0;$j<count($output->results[0]->address_components);$j++){
		                $cn=array($output->results[0]->address_components[$j]->types[0]);
		                if(in_array("locality", $cn)){

		                	// Set la variable de session ville
		                    $ville = $_SESSION['ville'] = $output->results[0]->address_components[$j]->long_name;
		                }
		            }
		          
	            }else{
	            	$ville = "Paris";
	            }
            }else{
            	$ville = $_SESSION['ville'];
            }

            // Récupération de la météo de la ville grace a l'API openWeatherMap
            $url="http://api.openweathermap.org/data/2.5/weather?q=".$ville.",fr&APPID=1389b1a0fd9f33f0aad25e67cb48e130&units=metric";
	        $json=@file_get_contents($url);
	        $data=json_decode($json,true);          

	        // En fonction du temps donné, on traduit et on récupère le titre de l'image approprié
	        switch ($data['weather'][0]['main']) {
	            case 'Clouds':
	            $temps = 'nuages';
	            $title = "Temps nuageux";
	            break;
	            case 'Rain':
	            $temps = 'pluie';
	            $title = "Pluie";
	            break;
	            case 'Drizzle':
	            $temps = 'pluie';
	            $title = "Pluie";
	            break; 
	            case 'Snow':
	            $temps = 'neige';
	            $title = "Neige";
	            break;                       
	            case 'Thunderstorm':
	            $temps = 'orage';
	            $title = "Orages";
	            break;
	            case 'Clear':
	            $temps = 'soleil';
	            $title = "Ensoleillé";
	            break;
	            default:
	            $temps = 'soleil';
	            $title = "Partiellement couvert";
	            break;
	        }

	        echo 
	        	'<div class="col-4" style="float:left;">
	        		<img width="60px" title="'.$title.'" id="image_meteo" src="images/'.$temps.'.png">
	        	</div>
	        	<div class="col-8 text-uppercase" style="float:left;margin-top:20px">'.$ville." ".$data["main"]["temp"].' °C</div>';

            

	        
?>
    </div>
    <div class="col-lg-6">
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
            	<div class="row">
				  <div class="col-lg-6">
				    <div class="input-group">
				      <input type="text" class="form-control" placeholder="Chercher une personne">
				      <span class="input-group-btn">
				        <button class="btn btn-default" type="button">Go!</button>
				      </span>
				    </div>
				  </div>
				  <div class="col-lg-6">
				    <div class="input-group">
				      <input type="text" class="form-control" placeholder="Chercher un lieu ">
				      <span class="input-group-btn">
				        <button class="btn btn-default" type="button">Go!</button>
				      </span>
				    </div>
				  </div>
				</div>
          </ul>
        </div>
      </div>
    </div>
    </nav>
    <header class="masthead text-center text-white d-flex">
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-10 mx-auto">
            <h1 class="text-uppercase">
              <strong>Bienvenue sur InstaMeteo</strong>
            </h1>
            <hr>
          </div>
          <div class="col-lg-8 mx-auto">
            <p class="text-faded mb-5">Ce site a été conçu afin d'aider les photographes à planifier leurs shooting photos. Voir les photos d'un lieu, prévoir la météo, trouver des modèles photos, tout est possible!</p>
          </div>
        </div>
      </div>
    </header>
      <div id="chercherPersonneContainer" class="container my-auto">
        <div class="row">
          <div class="col-lg-10 mx-auto">
            <h1 class="text-uppercase">
              <strong>Rechercher une personne</strong>
            </h1>
            <hr>
          </div>
          <div class="col-lg-8 mx-auto">
            <p class="text-faded mb-5">Si vous recherchez ici une personne avec la barre de recherche, ses photos instagram s'afficheront!</p>
          </div>
        </div>


    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>

  </body>

</html>
