$( document ).ready(function() {

    // On récupère la position de l'utilisateur, puis on appelle la fonction setPosition
    //navigator.geolocation.getCurrentPosition(setPosition);
    document.querySelector("form").addEventListener("submit",envoi);
   
});

function envoi(evt){

  evt.preventDefault();
  var ville= document.getElementById("ville").value;

  $.ajax({
       url : 'http://api.openweathermap.org/data/2.5/weather?q='+ville+'&APPID=1389b1a0fd9f33f0aad25e67cb48e130&units=metric', // La ressource ciblée
       type: 'GET',
       dataType : 'JSON',
       success : function(retour){
           console.log(retour);
           $("#temperature").html(retour["main"]["temp"]);
       }
    });
}



function errorHandler(err) {
    if(err.code == 1) {
       alert("Error: Access is denied!");
    }
    
    else if( err.code == 2) {
       alert("Error: Position is unavailable!");
    }
 }