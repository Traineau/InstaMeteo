<?php
	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
	    <title>Weather</title>
	</head>
	<body>
	    	<div class="collapse navbar-collapse" id="navbarResponsive">
	          <ul class="navbar-nav ml-auto">
				<div class="col-lg-6">
					<form id ="search" method="post"> 
							<input id="ville" type="text" class="form-control" placeholder="Chercher une personne" name="ville">
							<span class="input-group-btn">
								  <input id="submit" type="submit" value="GO!" onclick="envoi()">
							</span>
					</form>

					<?php

						//$_SESSION['ville'] = $_POST['ville'];

						if(!isset($_SESSION['ville'])){
							$ville = "Paris";
						}
		                else{
		                	$ville = $_SESSION['ville'];
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

			    	            echo'<div class="col-4" style="float:left;">
    	        						<img width="60px" title="'.$title.'" id="image_meteo" src="images/'.$temps.'.png">
    	        					</div>
    	        					<div class="col-8 text-uppercase" style="float:left;margin-top:20px">'.$ville." ".$data["main"]["temp"].' °C</div>';
		    	        	}
		    	     	}

	    	        	
      				?>
      				
				</div>
			  </ul>
		    </div>

		    <div id="temperature"></div>

		    <script src="jquery-3.3.1.js"></script>
    <script src="jquery-ui.min.js"></script>
    <script src="main.js"></script>
	</body>
</html>



