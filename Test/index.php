<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: ORIGIN, Content-Type, Accept, Authorization, X-Request-With, X-CLIENT-ID, X-CLIENT-SECRET");
header("Access-Control-Allow-Credentials: true");

 ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <!-- Search Form -->
    <p>
        <div id="custom-search-input">
            <div class="input-group">
                <input type="text" class="form-control input-lg" name="username" id="username" placeholder="Search by Instagram UserName" />
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-lg" type="button" id="searchButton">Go!</button>
                    </span>
            </div>
        </div>
    </p>

    <script language="JavaScript">
        var searchUrl = 'http://mv-wss.handysofts.com/mv-wss/insprofilefinder/api/v1/user/profile/search/%s';
        var storiesUrl = 'http://mv-wss.handysofts.com/mv-wss/instasavestory/api/v1/user/story/{userId}/{userName}';
    </script>

    <!-- Search Results -->
    <p id="search-results">
    </p>

    <div class="col-xs-8 col-md-10">
          <p><kbd>#user.id#</kbd>&nbsp;&nbsp; @<span class="userNameSpanner">#user.username#</span></p>
    </div>

        <script src="jquery-3.3.1.js"></script>
        <script src="main.js"></script>

  </body>
</html>
