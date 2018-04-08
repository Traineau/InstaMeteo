<?php                    
                    if(isset($_SESSION['lat']) && isset($_SESSION['long'])){
                        $lat = $_SESSION['lat']; // Si la geolocalisation a marché
                        $long = $_SESSION['long'];
            
                        // Si on a pas de ville déja localisé
                        if(!isset($_SESSION['ville'])){
            
                            // Localisation, pour récupérer la ville en fonction de la latitude et la longitude
                            $geocode=file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&sensor=false');
            
                            // On regarde parmis toutes les localisations de l'API si une ville correspond
                            $output= json_decode($geocode);
            
                            // Si on a des resultats pour cette localisation
                            if(isset($output->results[0])){
            
                                // On cherche une ville qui correspond
                                for($j=0;$j<count($output->results[0]->address_components);$j++){
                                    $cn=array($output->results[0]->address_components[$j]->types[0]);
                                    if(in_array("locality", $cn)){
            
                                        // Set la variable de session ville
                                        $ville = $_SESSION['ville'] = $output->results[0]->address_components[$j]->long_name;
                                    }
                                }
            
                            }else{
                                $ville = "Paris";
                            }
                        }else{
                            $ville = $_SESSION['ville'];
                        }
            
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
                        }
            
                        echo
                            '<div class="col-4" style="float:left;">
                            <img width="40px" title="'.$title.'" id="image_meteo" src="images/'.$temps.'.png">
                            </div>
                            <div class="col-8 text-uppercase" style="float:left;margin-top:10px">'.$ville." ".round($data["main"]["temp"]).' °C</div>';
                    }else{
                        $data = null;
                        $temps = "";
                        $title = "";
                        $ville = "Ville non trouvée";
                        echo "Ville non trouvée";
                    } 
?>                