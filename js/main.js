$(document).ready(function() {

    // On récupère la position de l'utilisateur, puis on appelle la fonction setPosition
    navigator.geolocation.getCurrentPosition(setPosition);

    // Clic sur un des elements de la classe scroll, on scroll a l'endroit spécifié dans le href
    $(".scroll").click(function(event){     
        event.preventDefault();
        $('html,body').stop().animate({scrollTop:$(this.hash).offset().top},1000);
    });

    // Les deux boutons pour rechercher une ville
    $("#chercherVilleBouton").on("click", function(e) {
        afficherMeteoVille($("#chercherVilleInput").val());
    });

    $("#chercherVilleBoutonHeader").on("click", function(e) {
        afficherMeteoVille($("#chercherVilleInputHeader").val());
    });

    // Les deux bouton pour rechercher une personne
    $("#chercherIdBouton").on("click", function(e) {
        afficherPhotos($("#chercherIdInput").val());
    });

    $("#chercherIdBoutonHeader").on("click", function(e) {
        afficherPhotos($("#chercherIdInputHeader").val());
    });

    $("#buttonNextDayMeteo").on("click", function(e) {
        afficherMeteoPlusieursJours($("#afficherVilleTemperature").data("data-ville"));
    });

    $("#buttonHideNextDay").on("click", function(e) {
        $("#buttonNextDayMeteo").css("display", "block");
        $("#meteoJoursSuivants").css("display", "none");
        $("#buttonHideNextDay").css("display", "block");
    });

});

function afficherMeteoVille(nomVille){

    $("#affichageVilleTemperature").attr("data-ville", nomVille);

    $.ajax({
        url: 'php/model.php', // La ressource ciblée
        type: 'POST',
        timeout: 3000,
        dataType: 'JSON',
        data: {
            action: 'getMeteo',
            ville: nomVille
        },
        success: function(retour) {

            var infosImage = recupererImage(retour['weather'][0]['main']);

            // On cache la météo des jours suivants si ils sont affichés
            if($("#meteoJoursSuivants").css("display") !== "none"){
                $("#buttonNextDayMeteo").css("display", "block");
                $("#meteoJoursSuivants").css("display", "none");
                $("#buttonHideNextDay").css("display", "block");
            }

            $("#afficherImageMeteo").html('<img style="float:right;" width="150px" title="' + infosImage.titre + '" id="image_meteo" src="images/' + infosImage.path +'.png">');

            $("#affichageVilleTemperature").html(retour["name"] + ", " + retour["main"]["temp"] + "°C")

            $("#affichageTempMinMax").html(
                '<span style="color:blue;">Min : <span class="bold">'
                + retour['main']['temp_min'] 
                + ' °C</span></span> , <span style="color:red;">Max : <span class="bold"> ' 
                + retour['main']['temp_max'] + ' °C</span></span>');

            $("#affichageLeverSoleil").html('Lever du soleil :  <span class="bold">' +  dateHeureMinute(retour['sys']['sunrise']) + ' </span>');
            $("#affichageCoucherSoleil").html('Coucher du soleil :  <span class="bold">' +  dateHeureMinute(retour['sys']['sunset']) + ' </span>');


        },

        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#chercherMeteoAffichage").prepend("<h2>Ville introuvable</h2>");
        }
    });
}

function afficherMeteoPlusieursJours(nomVille){
    var nomVille = $("#affichageVilleTemperature").attr("data-ville");

    $.ajax({
        url: 'php/model.php', // La ressource ciblée
        type: 'POST',
        timeout: 3000,
        dataType: 'JSON',
        data: {
            action: 'getNextDayMeteo',
            ville: nomVille
        },
        success: function(retour) {
            console.log(retour);
            var i = 0;
            var object = retour["list"];

            // On recupere la date d'aujourd'hui pour prendre les jours suivants uniquement
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1;
            var yyyy = today.getFullYear();
            if(dd<10) {
                dd = '0'+dd
            } 
            if(mm<10) {
                mm = '0'+mm
            } 
            today = yyyy + '-' + mm + '-' + dd;

            // La liste des données météo
            $.each(object, function(index, value) {              

                // On ne prend que les données présentes a 15h, donc une par jour, et on ne prend pas les données pour aujourd'hui
                if(value["dt_txt"].includes("15:00:00") && !value["dt_txt"].includes(today)){
                    i++;

                    var infosImage = recupererImage(value['weather'][0]['main']);

                    $("#meteoJoursSuivants"+i+" #afficherImageMeteo").html('<img style="float:right;" width="70px" title="' + infosImage.titre + '" id="image_meteo" src="images/' + infosImage.path +'.png">');

                    $("#meteoJoursSuivants"+i+" #affichageVilleTemperature").html(value["main"]["temp"] + "°C")

                    $("#meteoJoursSuivants"+i+" #affichageTempMinMax").html(
                        '<span style="color:blue;">Min : <span class="bold">'
                        + value['main']['temp_min'] 
                        + ' °C</span></span><br/><span style="color:red;">Max : <span class="bold"> ' 
                        + value['main']['temp_max'] + ' °C</span></span>');
                }
            });

            $("#buttonNextDayMeteo").css("display", "none");
            $("#meteoJoursSuivants").css("display", "block");
            $("#buttonHideNextDay").css("display", "block");

        },

        error: function(XMLHttpRequest, textStatus, errorThrown) {
        }
    });

}

function recupererImage(weather){
    var infosImage;
    switch (weather) {
        case 'Clouds':
            infosImage = {titre : "Temps nuageux", path : "nuages"};
        break;
        case 'Rain':
            infosImage = {titre : "Pluie", path : "pluie"};
        break;
        case 'Drizzle':
            infosImage = {titre : "Pluie", path : "pluie"};
        break;
        case 'Snow':
            infosImage = {titre : "Neige", path : "neige"};
        break;
        case 'Thunderstorm':
            infosImage = {titre : "Orages", path : "orage"};
        break;
        case 'Clear':
            infosImage = {titre : "Ensoleillé", path : "soleil"};
        break;
        default:
            infosImage = {titre : "Partiellement couvert", path : "soleil"};
        break;
    }

    return infosImage;

}

function dateHeureMinute(timestamp){
    var date = new Date(timestamp*1000);

    var hours = date.getHours();

    var minutes = "0" + date.getMinutes();

    // Will display time in 10:30:23 format
    return hours + ':' + minutes.substr(-2) ;
}


// Fonction qui utilise l'api d'instagram pour afficher les photos d'un utilisateur
function afficherPhotos(pseudo){

    var id = getIdFromPseudo(pseudo);

    $("#lesPhotos").html("");
    // Le tableau contenant toutes nos nouvelles div avec les images
    var boxes = [];

    // Gestion de la barre de chargement
    // C'est uniquement du visuel, pour montrer a l'utilisateur qu'il faut patienter
    // On remet la barre a 0
    $(".progress-bar").css("left", "0px");
    // Affichage de la barre
    $(".progress-wrap").css("display", "block");

    // On recupere la zone a colorier en fonction de la taille de la barre
    var getPercent = ($('.progress-wrap').data('progress-percent') / 100);
    var getProgressWrapWidth = $('.progress-wrap').width();
    var progressTotal = getPercent * getProgressWrapWidth;
    var animationLength = 500;

    // On lance l'animation, qui dure 0.5 sec
    $('.progress-bar').animate({
        left: progressTotal
    }, animationLength);

    $.ajax({
        url: 'php/model.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
            action: 'getPhotos',
            id: id
        },
        success: function(retour) {
            console.log(retour);
            // Si l'utilisateur est introuvable
            if(retour["data"]["user"] == null){
                // On cache la barre de chargement
                $(".progress-wrap").css("display", "none");
                // Affichage des photos récupérées
                $('#lesPhotos').append("<h2>Utilisateur introuvable</h2>");
            }else{
                // On récupère les infos qui nous intéressent
                object = retour["data"]["user"]["edge_owner_to_timeline_media"]["edges"];

                // Pour chaque photo récupérée, on les ajoute dans notre view
                $.each(object, function(index, value) {
                    // Un nombre aléatoire entre 4 et 4.5 pour diversifier un peu la tailel des photos
                    var nombre = Math.ceil( Math.random() * 1 + 5 );
                    // L'url de la photo
                    var url = value["node"]["display_url"];
                    // Le lien
                    var link = 'https://www.instagram.com/p/' + value["node"]["shortcode"] + '/';
                    // Nombre de like
                    var nbLike = value["node"]["edge_media_preview_like"]["count"];
                    // Le nombre de commentaire
                    var nbCom = value["node"]["edge_media_to_comment"]["count"];
                    // L'url de l'image
                    var dlImage = value["node"]["display_url"];

                    // Le titre (commentaire publié par l'auteur de la photo sur cette photo)
                    if(typeof value["node"]["edge_media_to_caption"]["edges"]["0"] !== 'undefined'){
                        var title = value["node"]["edge_media_to_caption"]["edges"]["0"]["node"]["text"];
                    }else{
                        var title = "Photo";
                    }

                    var boxe = 
                                '<div class="box size' +  5 +  6 +'">'
                            +   '   <div id="containerPhoto">'
                            +   '       <a href="' + link + '" target="blank"><img src="' + url + '"  title="' + title + '"></a>'
                            +   '   </div>'
                            +   '   <div id="containerActionPhoto">'
                            +   '       <span class="left"><img title="Nombre de like" src="images/hearth.png" width="15px"> : ' + nbLike
                            +   '           &nbsp &nbsp<img title="Nombre de commentaires" src="images/comment.png" width="15px"> : ' + nbCom 
                            +   '       </span><span class="right">'
                            +   '           <a href="' + dlImage + '" target="blank"><img title="Télécharger l\'image" src="images/dl.png" width="15px">'
                            +   '           </a>&nbsp &nbsp<img title="Voir la météo" src="images/black-sun.png" width="20px">'
                            +   '       </span></div></div>';
                    boxes.push(boxe);
                });

                // On cache la barre de chargement
                $(".progress-wrap").css("display", "none");

                // Affichage des photos récupérées
                $('#lesPhotos').append(boxes).nested('append',boxes);
            }
        },

        error: function(XMLHttpRequest, textStatus, errorThrown) {
            // On cache la barre de chargement
            $(".progress-wrap").css("display", "none");
            // Affichage des photos récupérées
            $('#lesPhotos').append("Une erreur est survenue avec l'API Instagram, veuillez réessayer");
        }
    });
}

function getIdFromPseudo(pseudo){
    var id;
    var searchUrl = 'http://mv-wss.handysofts.com/mv-wss/insprofilefinder/api/v1/user/profile/search/%s';
    var url = searchUrl.replace("%s", pseudo);

    $.ajax({
        url: 'php/model.php',
        type: 'POST',
        dataType: 'JSON',
        async: false,
        data: {
            url: url,
            action: 'getPseudo',
        },
        success: function(data) {
            console.log(data);
            $.each( data.users, function( index, user ) {
                console.log('<img src="' + user.profilePicture + '"');
                // Affichage des informations sur l'utilisateur trouvé
                $("#afficherInfosPersonne").css("display", "block");

                // Remplacement du contenu
                $("#imagePersonne").html('<img src="' + user.profilePicture + '">');
                $("#pseudoPersonne").html(user.username);
                $("#nomPersonne").html(user.fullName);
                $("#descriptionPersonne").html(user.bio);
                id = user.id;
            });
        }
    });


    return id;
}

function setPosition(position) {
    $.ajax({
        url: 'php/model.php', // La ressource ciblée
        type: 'POST',
        dataType: 'JSON',
        data: {
            action: 'setVille',
            latitude: position.coords.latitude,
            longitude: position.coords.longitude
        },

        complete: function(response) {
            //location.reload();
        }
    });

}

function errorHandler(err) {
    if (err.code == 1) {
        alert("Error: Access is denied!");
    } else if (err.code == 2) {
        alert("Error: Position is unavailable!");
    }
}