<?php
    session_start();
    $datetime = new DateTime();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>InstaMeteo</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
        <link href="css/main.css" rel="stylesheet">
		<link rel="icon" type="images/png" href="images/icone.png" />

        <script src="js/jquery-3.3.1.js"></script>
        <script src="js/main.js"></script>
        <script src="js/jquery.nested.js"></script>
        
    </head>
    <body id="page-top">
        <nav class="navbar navbar-expand navbar-light fixed-top" id="mainNav">
            <div class="container">
                <div class="col-3">
                    <span id="instagramLogo"><img src="images/instagramLogo.png" alt=""></span>
                    <a class="navbar-brand scroll" href="#page-top">InstaMeteo</a>
                </div>
                <div id="meteo" class="col-3">
<?php
                    if(isset($_SESSION['lat']) && isset($_SESSION['long'])){
                        $lat = $_SESSION['lat']; // Si la geolocalisation a marché
                        $long = $_SESSION['long'];
            
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
                        $json=file_get_contents($url);
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
                            <img width="40px" title="'.$title.'" id="image_meteo" src="images/'.$temps.'.png">
                            </div>
                            <div class="col-8 text-uppercase" style="float:left;margin-top:10px">'.$ville." ".round($data["main"]["temp"]).' °C</div>';
                    }else{
                        $data = null;
                        $temps = "";
                        $title = "";
                        $ville = "Ville non trouvée";
                        echo "Ville non trouvée";
                    }      
?>
                </div>
                <div class="col-6">
                    <div class="collapse navbar-collapse" id="navbarResponsive">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="input-group">
                                            <input id="chercherIdInputHeader" type="text" class="form-control" placeholder="Entrez un pseudo">
                                            <span class="input-group-btn">
                                            <a id="chercherIdBoutonHeader" href="#chercherPersonneContainer" class="scroll btn btn-default" type="button">Go!</a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group">
                                            <input id="chercherVilleInputHeader" type="text" class="form-control" placeholder="Cherchez une ville">
                                            <span class="input-group-btn">
                                            <a id="chercherVilleBoutonHeader" href="#chercherMeteoContainer" class="scroll btn btn-default" type="button">Go!</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="masthead text-center text-white d-flex">
            <div class="container my-auto">
                <div class="row">
                    <div class="col-10 mx-auto">
                        <h1 class="text-uppercase">
                            <strong>Bienvenue sur InstaMeteo</strong>
                        </h1>
                        <hr>
                    </div>
                    <div class="col-8 mx-auto">
                        <p class="text-faded mb-5">Ce site a été conçu afin d'aider les photographes à planifier leurs shooting photos. Voir les photos d'un lieu, prévoir la météo, trouver des modèles photos, tout est possible!</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="chercherMeteoContainer">
            <div class="clear30"></div>
            <div class="row">
                <div class="col-10 mx-auto">
                    <h1 class="text-uppercase">
                        <strong>Rechercher la météo d'un lieu</strong>
                    </h1>
                    <hr>
                </div>
                <div class="col-12">
                    <p class="mb-5">Vous pouvez voir ici la météo de n'importe quelle ville en direct</p>
                </div>

                <div class="col-12">
                    <p class="bold"><?php echo $datetime->format("d/m/Y");?></p>
                </div>

                <div id="chercherMeteoAffichage" class="col-12">
                    <div id="afficherImageMeteo" class="col-6" style="float:left;">
                        <img style="float:right;" width="150px" title="<?php echo $title?>" id="image_meteo" src="images/<?php echo $temps?>.png">
                    </div>

                    <div class="col-6 mb-5 text-uppercase" style="float:left;margin-top:20px;">
                        <p id="affichageVilleTemperature" data-ville="<?php echo $ville?>" class="bold"><?php echo $ville.", ".round($data["main"]["temp"])?> °C</p><br/>
                        <p id="affichageTempMinMax">
                            <span style="color:blue;">Min : <span class="bold"><?php echo $data['main']['temp_min']?> °C</span></span> , <span style="color:red;">Max : <span class="bold"><?php echo $data['main']['temp_max']?> °C</span></span>
                        </p><br/>
                        <p id="affichageLeverSoleil">Lever du soleil :  <span class="bold"><?php echo date("H:i", $data['sys']['sunrise'])?></span></p><br/>
                        <p id="affichageCoucherSoleil">Coucher du soleil :  <span class="bold"><?php echo date("H:i", $data['sys']['sunset'])?></span></p><br/>
                    </div>

                    <button id="buttonNextDayMeteo" type="button" class="mt-3 btn btn-primary">Voir les jours suivants</button>
                </div>

                <div id="meteoJoursSuivants" class="col-12" style="display: none;">
                    <div class="clear50"></div>

                    <div id="meteoJoursSuivants1" class="col-6 left">
                        <div id="chercherMeteoAffichage" class="right">
                            <p class="bold" style="padding-left:40px;"><?php $datetime->modify('+1 day');echo $datetime->format("d/m/Y");?></p><br/>
                            <div id="afficherImageMeteo" style="float:left;">
                                <img style="float:right;" width="70px" title="<?php echo $title?>" id="image_meteo" src="images/<?php echo $temps?>.png">
                            </div>

                            <div class="mb-5 text-uppercase" style="float:left;">
                                <p id="affichageVilleTemperature" data-ville="<?php echo $ville?>" class="bold"><?php echo $ville.", ".round($data["main"]["temp"])?> °C</p><br/>
                                <p id="affichageTempMinMax">
                                    <span style="color:blue;">Min : <span class="bold"><?php echo round($data['main']['temp_min'])?> °C</span></span><br/>
                                    <span style="color:red;">Max : <span class="bold"><?php echo round($data['main']['temp_max'])?> °C</span></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="meteoJoursSuivants2" class="col-6 left">
                        <div id="chercherMeteoAffichage" class="left">
                            <p class="bold" style="padding-left:40px;"><?php $datetime->modify('+1 day');echo $datetime->format("d/m/Y");?></p><br/>
                            <div id="afficherImageMeteo" style="float:left;">
                                <img style="float:right;" width="70px" title="<?php echo $title?>" id="image_meteo" src="images/<?php echo $temps?>.png">
                            </div>

                            <div class="mb-5 text-uppercase" style="float:left;">
                                <p id="affichageVilleTemperature" data-ville="<?php echo $ville?>" class="bold"><?php echo $ville.", ".round($data["main"]["temp"])?> °C</p><br/>
                                <p id="affichageTempMinMax">
                                    <span style="color:blue;">Min : <span class="bold"><?php echo round($data['main']['temp_min'])?> °C</span></span><br/>
                                    <span style="color:red;">Max : <span class="bold"><?php echo round($data['main']['temp_max'])?> °C</span></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="meteoJoursSuivants3" class="col-6 left">
                        <div id="chercherMeteoAffichage" class="right">
                            <p class="bold" style="padding-left:40px;"><?php $datetime->modify('+1 day');echo $datetime->format("d/m/Y");?></p><br/>
                            <div id="afficherImageMeteo" style="float:left;">
                                <img style="float:right;" width="70px" title="<?php echo $title?>" id="image_meteo" src="images/<?php echo $temps?>.png">
                            </div>

                            <div class="mb-5 text-uppercase" style="float:left;">
                                <p id="affichageVilleTemperature" data-ville="<?php echo $ville?>" class="bold"><?php echo $ville.", ".round($data["main"]["temp"])?> °C</p><br/>
                                <p id="affichageTempMinMax">
                                    <span style="color:blue;">Min : <span class="bold"><?php echo round($data['main']['temp_min'])?> °C</span></span><br/>
                                    <span style="color:red;">Max : <span class="bold"><?php echo round($data['main']['temp_max'])?> °C</span></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="meteoJoursSuivants4" class="col-6 left">
                        <div id="chercherMeteoAffichage" class="left">
                            <p class="bold" style="padding-left:40px;"><?php $datetime->modify('+1 day');echo $datetime->format("d/m/Y");?></p><br/>
                            <div id="afficherImageMeteo" style="float:left;">
                                <img style="float:right;" width="70px" title="<?php echo $title?>" id="image_meteo" src="images/<?php echo $temps?>.png">
                            </div>

                            <div class="mb-5 text-uppercase" style="float:left;">
                                <p id="affichageVilleTemperature" data-ville="<?php echo $ville?>" class="bold"><?php echo $ville.", ".round($data["main"]["temp"])?> °C</p><br/>
                                <p id="affichageTempMinMax">
                                    <span style="color:blue;">Min : <span class="bold"><?php echo round($data['main']['temp_min'])?> °C</span></span><br/>
                                    <span style="color:red;">Max : <span class="bold"><?php echo round($data['main']['temp_max'])?> °C</span></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <button id="buttonHideNextDay" type="button" class="mt-3 btn btn-primary" style="display: none;margin:0 auto;">Cacher les météos des jours suivants</button>

                </div>

                <div class="col-12 chercherMeteoRecherche" style="margin-top:50px;">
                    <div class="input-group mainInput">
                        <input id="chercherVilleInput" type="text" class="form-control" placeholder="Chercher une ville ">
                        <span class="input-group-btn">
                        <button id="chercherVilleBouton" class="btn btn-default" type="button">Go!</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="clear100"></div>

        <div id="chercherPersonneContainer">

            <div class="clear30"></div>

            <div class="container my-auto">
                <div class="row">
                    <div class="col-12 mx-auto">
                        <h1 class="text-uppercase">
                            <strong>Rechercher une personne</strong>
                        </h1>
                        <hr>
                    </div>

                    <div class="col-12 mx-auto">
                        <p class="mb-5">Si vous recherchez ici une personne avec la barre de recherche, ses dernières photos instagram s'afficheront!</p>
                        <div class="input-group mainInput">
                            <input id="chercherIdInput" type="text" class="form-control" placeholder="Entrez un pseudo">
                            <span class="input-group-btn">
                                <button id="chercherIdBouton" class="btn btn-default" type="button">Go!</button>
                            </span>
                        </div>
                    </div>

                    <div class="clear150"></div>

                    <div class="col-12 mx-auto" id="afficherInfosPersonne" style="display:none;">
                        <h2 id="pseudoPersonne"></h2>
                        <div id="imagePersonne">

                        </div><br/>

                        <div id="infosPersonne">
                            <p id="nomPersonne"></p>                            
                            <p id="descriptionPersonne"></p>
                        </div>
                    </div>

                    <div class="clear150"></div>

                    <div class="progress-wrap progress" data-progress-percent="100" style="display:none;">
                        <div class="progress-bar progress"></div>
                    </div>

                    <div id="lesPhotos" class="col-lg-12">
                        <div class="box size"></div>
                    </div>
                </div>

            </div>
            <div class="clear100"></div>
        </div>
        <script>
            $("#lesPhotos").nested({resizeToFit: false});
        </script>

        <div id="footer">
            <div class="arrow-container animated fadeInDown">
                <a href="#page-top" class="arrow-2 scroll">
                    <img src="images/arrow.png">
                </a>
                <div class="arrow-1 animated hinge infinite zoomIn"></div>
            </div>

            <div class="clear50"></div>

            <p>© Tous droits réservés</p>
            <p><a href="https://github.com/Tynyndil/InstaMeteo" target="blank">Voir le code source du projet</a>
            <p>Auteurs: <a href="mailto:emericmottier72@gmail.com">Emeric Mottier</a>, <a href="mailto:thomasraineau3@gmail.com">Steven Robillard</a>, <a href="mailto:thomasraineau3@gmail.com">Thomas Raineau</a></p>
        </div>

    </body>
</html>