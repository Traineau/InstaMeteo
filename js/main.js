$(document).ready(function() {

    // On récupère la position de l'utilisateur, puis on appelle la fonction setPosition
    navigator.geolocation.getCurrentPosition(setPosition);

    $("#chercherVilleBouton").on("click", function() {

      var ville = $("#chercherVilleInput").val();

      $.ajax({
            url: 'http://api.openweathermap.org/data/2.5/weather?q=' + ville + '&APPID=1389b1a0fd9f33f0aad25e67cb48e130&units=metric', // La ressource ciblée
            type: 'GET',
            dataType: 'JSON',
            success: function(retour) {
              console.log(retour);
              $("#affichageVilleTemperature").html(retour["name"] + " " + retour["main"]["temp"] + "°C")
            },

            error: function(XMLHttpRequest, textStatus, errorThrown) {
             alert("Ville introuvable");
           }
         });

    });

    $("#chercherIdBouton").on("click", function() {

      $("#lesPhotos").html("");

      var id = $("#chercherIdInput").val();

      $.ajax({
        url: 'https://instagram.com/graphql/query/?query_id=17888483320059182&variables={"id":"'+ id +'","first":20,"after":null}',
        type: 'GET',
        dataType: 'JSON',

        success: function(retour) {
          object = retour["data"]["user"]["edge_owner_to_timeline_media"]["edges"];

            // Pourchaque photo récupérée, on les ajoute dans notre view
            $.each(object, function(index, value) {
              console.log(value);
              $("#lesPhotos").append(
                '<div class="col-lg-4 float-left containerPhoto"><img src="'+ value["node"]["display_url"]+'" alt="Photos"></div>').show('slow');
            });

          }
        });

    });

  });

function setPosition(position) {
  $.ajax({
        url: 'php/ajax/ajax.php', // La ressource ciblée
        type: 'POST',
        dataType: 'JSON',
        data: {
          action: 'setVille',
          latitude: position.coords.latitude,
          longitude: position.coords.longitude
        },

        complete: function(response){
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