
var messageTypes = {INFO:"info", SUCCESS:"success", WARNING:"warning", ERROR:"danger"};


/*
    Events goes here
 */
$( document ).ready(function()
{
    $( "#searchButton" ).click(function() {
        searchUsers();
    });

    $('#username').keypress(function (e) {
        if (e.which == 13) {
            searchUsers();
            return false;
        }
    });
});


/**
 * Search Instagram User Profile by UserName goes here
 */
function searchUsers(){
    var username = $('#username').val();
    var url = searchUrl.replace("%s", username);
    url = clearUrl(url);
    $.ajax({
        url: url,
        type: 'POST',
        success: function(data) {

          $.each( data.users, function( index, user ) {
              console.log(user.id);

          });
        }
    });

}



/**
 * Urls with .sh returns error to prevent execution of sh
 *
 * @param url
 * @returns {*}
 */
function clearUrl(url) {
    if (url.endsWith(".sh"))
        return url + "/";

    return url;
}
