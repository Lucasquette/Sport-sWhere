<!DOCTYPE html>
<html lang='en'>

<head>
  <meta charset='utf-8' />
  <title>Carte</title>
  <meta name='viewport' content='width=device-width, initial-scale=1' />

  <link rel="stylesheet" href="CSS/styleParams.css">
  <link rel="stylesheet" href="CSS/map.css">

 <!--Import Bouton -->
 <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet"/>

  <!-- Import Mapbox GL JS  -->
  <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js'></script>
  <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css' rel='stylesheet' />
  <!-- Import Assembly -->
  <link href='https://api.mapbox.com/mapbox-assembly/v1.3.0/assembly.min.css' rel='stylesheet'>
  <script src='https://api.mapbox.com/mapbox-assembly/v1.3.0/assembly.js'></script>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src='https://cdn.jsdelivr.net/npm/@turf/turf@6'></script>

  
  <style>

    
    body {
      margin: 0;
      padding: 0;
    }

    #map {
      position: absolute;
      top: 0;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>

<body>
    <header>
        
        <div class="menu_toggle">
            <span></span>
        </div>

        <div class="logo">
            <a href="acc.html"><p><span>Sport's</span>Where</p></a>
        </div>
        <ul class="menu">
            <li><a href="acc.html">Accueil</a></li>
            <li><a href="index.php">Chercher</a></li>
            <li><a href="con.html">Contact</a></li>
        </ul>
        <a href="connexion.html" class="login_btn">LOGIN</a>
    </header>

    <div id="map"></div>

    <div class='menu-params'>
        <div class="params" id="params">
              <h4>Adresse</h4>
              <input type="text" id="input-adresse">

              <h4 >Sport</h4>
              <input type="text" id="input-sport">

              <h4>Transport</h4>
              <div class="transport">
                <button class="btnTransport" id="car"><i class="ri-car-line"></i></button>
                <button class="btnTransport" id="bike"><i class="ri-riding-line"></i></button>
                <button class="btnTransport" id="walk"><i class="ri-walk-line"></i></button>
              </div>

              <button class="btn-go" id="btn-go">Go</button>
              <button class="btn-plusloin" id="btn-plusloin">Plus loin</button>
        </div>
        

        <div class="tmps_parcours">
          
        </div>
    </div>  
    
   

  
    <script>
      $(document).ready(function() {
        $('.btnTransport').click(function() {
          // Retire la classe 'active' de tous les boutons de transport
          $('.btnTransport').removeClass('active');
          
          // Ajoute la classe 'active' au bouton cliqué
          $(this).addClass('active');
        });
      });

    </script>
    <script src="JS/initMap.js"></script>
    <script src="JS/getParams.js"></script>
</body>

</html>