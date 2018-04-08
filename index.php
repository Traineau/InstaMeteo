<?php
    session_start();
    $datetime = new DateTime();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Pour aider les photographes à planifier leurs shooting photos. Voir les photos d'un lieu, prévoir la météo, trouver des modèles photos, tout est possible!">
        <meta name="author" content="Thomas-Raineau Steven-Robillard Emeric-Mottier">
        <Meta name="keywords" content="Instagram, Meteo, API, Photos, Localisation, seo, référencement, optimisation">

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
                    include "header.php";     
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
                    <div id="villeIntrouvable"><h2>&nbsp</h2></div>

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

                <div class="col-12 chercherMeteoRecherche" style="margin-top:100px;">
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
            <p>Auteurs: <a href="mailto:emericmottier72@gmail.com">Emeric Mottier</a>, <a href="mailto:steeven.robillard@gmail.com">Steven Robillard</a>, <a href="mailto:thomasraineau3@gmail.com">Thomas Raineau</a></p>
            <p>Suivez-nous sur Instagram : <a target="blank" href="https://www.instagram.com/steeviny/?hl=fr">steeviny</a>, <a target="blank" href="https://www.instagram.com/thomasraineau/?hl=fr">thomasraineau</a>, <a target="blank" href="https://www.instagram.com/emeric_mottier/?hl=fr">emeric_mottier</a>
        </div>

    </body>
</html>