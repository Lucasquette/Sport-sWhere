<?php
session_start();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: acc.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$adresse = '';
if (isset($_SESSION['nom_utilisateur'])) {
    $nomUtilisateur = $_SESSION['nom_utilisateur'];
    $stmt = $conn->prepare("SELECT adresse FROM utilisateurs WHERE nom = ?");
    $stmt->bind_param("s", $nomUtilisateur);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $adresse = $user['adresse'];
    }
    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Carte</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" href="CSS/styleParams.css">
  <link rel="stylesheet" href="CSS/acc.css"> <!-- Ajoutez le lien vers le fichier CSS du header -->
  <link rel="stylesheet" href="CSS/menu_déroulant_profil.css">


  <!-- Import Bouton -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />

  <!-- Import Mapbox GL JS  -->
  <script src="https://api.tiles.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>
  <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet" />
  <!-- Import Assembly -->
  <link href="https://api.mapbox.com/mapbox-assembly/v1.3.0/assembly.min.css" rel="stylesheet">
  <script src="https://api.mapbox.com/mapbox-assembly/v1.3.0/assembly.js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6"></script>

  <style>
    body {
      margin: 0;
      padding: 0;
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background-color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 8%;
      height: 11%;
      box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2);
      z-index: 4;
    }

    header .logo {
      font-size: 27px;
      font-weight: bold;
    }

    header .logo p {
      color: #174b7f;
    }

    header .logo span {
      color: #000;
    }

    .menu {
      display: flex;
    }

    .menu li {
      list-style: none;
      margin: 0 30px;
    }

    .menu li a {
      font-size: 17px;
      color: #000;
      font-weight: bold;
      transition: 0.2s;
    }

    header .login_btn {
      background-color: #174b7f;
      cursor: pointer;
      color: #fff;
      padding: 5px 25px;
    }

    #map {
      position: absolute;
      top: 11%; /* Ajustez la position pour éviter de chevaucher le header */
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
        <a href="acc.php"><p><span>Sport's</span>Where</p></a>
    </div>
    <ul class="menu">
        <li><a href="acc.php">Accueil</a></li>
        <li><a href="carte.php">Chercher</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
    <?php if (isset($_SESSION['nom_utilisateur'])): ?>
        <div class="dropdown">
            <button class="dropbtn"><i class="ri-user-3-fill"></i><?php echo htmlspecialchars($_SESSION['nom_utilisateur']); ?></button>
            <div class="dropdown-content">
                <a href="profil_utilisateur.php">Profil</a>
                <a href="?logout">Se déconnecter</a>
            </div>
        </div>
    <?php else: ?>
        <a href="connexion.php" class="login_btn">LOGIN</a>
    <?php endif; ?>
</header>

  <div id="map"></div>

  <div class='menu-params'>
    <div class="params" id="params">
          <h4>Adresse</h4>
          <input type="text" id="input-adresse" value="<?php echo htmlspecialchars($adresse); ?>">

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

    <div class="tmps_parcours"></div>
    
  </div>

  <script>
    $(document).ready(function () {
      $('.btnTransport').click(function () {
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