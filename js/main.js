$( document ).ready(function() {

    // On récupère la position de l'utilisateur, puis on appelle la fonction setPosition
    navigator.geolocation.getCurrentPosition(setPosition);

   
});

function setPosition(position){
	$.ajax({
       url : 'php/ajax/ajax.php', // La ressource ciblée
       type: 'POST',
       dataType : 'JSON',
       data:{
            action: 'setVille',
            latitude : position.coords.latitude,
            longitude: position.coords.longitude
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