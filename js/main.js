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
        afficherVille($("#chercherVilleInput").val());
    });

    $("#chercherVilleBoutonHeader").on("click", function(e) {
        afficherVille($("#chercherVilleInputHeader").val());
    });

    // Les deux bouton pour rechercher une personne
    $("#chercherIdBouton").on("click", function(e) {
        afficherPhotos($("#chercherIdInput").val());
    });

    $("#chercherIdBoutonHeader").on("click", function(e) {
        afficherPhotos($("#chercherIdInputHeader").val());
    });

});

function afficherVille(nomVille){
    // On vide tous les champs
    $("#afficherImageMeteo").html('');
    $("#affichageVilleTemperature").html('');
    $("#affichageTempMinMax").html('');
    $("#affichageLeverSoleil").html('');
    $("#affichageCoucherSoleil").html('');

    $("#spinner").css("display", "block");

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
            console.log(retour);
            $("#spinner").css("display", "none");

            var infosImage = recupererImage(retour['weather'][0]['main']);

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
            $("#spinner").css("display", "none");
            $("#affichageVilleTemperature").html("<h2>Ville introuvable</h2>");
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
function afficherPhotos(id){
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

    // On mance l'animation, qui dure 1 sec
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
            // Si l'utilisateur est introuvable
            if(retour["data"]["user"] == null){
                // On cache la barre de chargement
                $(".progress-wrap").css("display", "none");

                // Affichage des photos récupérées
                $('#lesPhotos').append("<h2>Utilisateur introuvable</h2>");

            }else{
                // On récupère les infos qui nous intéressent
                object = retour["data"]["user"]["edge_owner_to_timeline_media"]["edges"];

                console.log(object);

                // Pour chaque photo récupérée, on les ajoute dans notre view
                $.each(object, function(index, value) {
                    // Un nombre aléatoire entre 4 et 4.5 pour diversifier un peu la tailel des photos
                    var nombre = Math.ceil( Math.random() * 1.5 + 4 );
                    // L'url de la photo
                    var url = value["node"]["display_url"];
                    // Le lien
                    var link = 'https://www.instagram.com/p/' + value["node"]["shortcode"] + '/';

                    // Le titre (commentaire publié par l'auteur de la photo sur cette photo)
                    if(typeof value["node"]["edge_media_to_caption"]["edges"]["0"] !== 'undefined'){
                        var title = value["node"]["edge_media_to_caption"]["edges"]["0"]["node"]["text"];
                    }else{
                        var title = "Photo";
                    }
                    

                    var boxe = '<div class="box size' +  nombre +  nombre +'">'
                            + '<a href="' + link + '" target="blank"><img src="' + url + '"  title="' + title + '"></a></div>';
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
            $('#lesPhotos').append("Une erreur est survenue, veuillez réessayer");
        }
    });
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
            location.reload();
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