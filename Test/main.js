
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
        type: 'GET',
        headers: {"Access-Control-Allow-Origin" : "http://mv-wss.handysofts.com"},
        dataType: 'JSON',
        success: function(data) {

          $.each( data.users, function( index, user ) {
              console.log(user.id);

              /*var searchResult = $('#search-result-item-template').clone();

              if (user.website == null || !user.website.startsWith("http"))
                  searchResult.find('#website').remove();

              searchResult.find("#profilePicture").attr("src", user.profilePicture);
              searchResult = searchResult.html()
                              .replace(/#user.profilePicture#/g, user.profilePicture)
                              .replace(/#user.fullName#/g, user.fullName)
                              .replace(/#user.username#/g, user.username)
                              .replace(/#user.id#/g, user.id)
                              .replace(/#user.bio#/g, user.bio)
                              .replace(/#user.website#/g, user.website);

              $('#search-results').append(searchResult);*/
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
