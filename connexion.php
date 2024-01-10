<?php
session_start();

// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map_app";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomUtilisateur = $conn->real_escape_string($_POST['nom_utilisateur']);
    $motDePasseFourni = $_POST['mot_de_passe'];

    // Préparer la requête SQL pour récupérer le mot de passe haché de l'utilisateur
    $stmt = $conn->prepare("SELECT mdp FROM utilisateurs WHERE nom = ?");
    $stmt->bind_param("s", $nomUtilisateur);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $motDePasseStocke = $user['mdp'];

        // Utiliser password_verify pour comparer le mot de passe fourni avec le mot de passe haché
        if (password_verify($motDePasseFourni, $motDePasseStocke)) {
            $_SESSION['nom_utilisateur'] = $nomUtilisateur;
            header("Location: acc.php"); // Redirige vers la page d'accueil après la connexion réussie
            exit;
        } else {
            // Gestion de l'erreur de mot de passe incorrect
            echo "Mot de passe incorrect.";
        }
    } else {
        // Gestion de l'erreur d'utilisateur non trouvé
        echo "Nom d'utilisateur non trouvé.";
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport's Where</title>
    <link rel="stylesheet" href="CSS/connexion.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
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
            <li><a href="acc.php">Accueil</a></li>
            <li><a href="carte.php">Chercher</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <?php if (isset($_SESSION['nom_utilisateur'])): ?>
            <a href="profil_utilisateur.php"><i class="ri-user-3-fill"></i><?php echo htmlspecialchars($_SESSION['nom_utilisateur']); ?></a>
        <?php else: ?>
            <a href="connexion.php" class="login_btn">LOGIN</a>
        <?php endif; ?>
    </header>

     
    <section class="home">
      <div class="formulaire">
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <h1>Connexion</h1>
          <div class="input-box">
          <input type="text" name="nom_utilisateur" placeholder="Nom d'utilisateur" required>
          </div>
          <div class="input-box">
          <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <div class="password-icon">
              <i data-feather="eye"></i>
              <i data-feather="eye-off"></i>
            </div>
          </div>
          
          <div class="souvenir">
            
            <a href="oublie.html">Mot de passe oublié</a>
          </div>
          
          <button type="submit" class="btn">Connexion</button>
          <div class="sanscompte">
            <p>Vous n'avez pas de compte ? <a href="creation.php">Créer un compte</a></p>
          </div>
        </form>
      </div>    
    </section>



    <section class="footer">
        <div class="footer-box">
            <h3>Services</h3>
            <a href="#">Recrutement</a>
            <a href="#">Marchés publics</a>
        </div>

        <div class="footer-box">
            <h3>Informations générales</h3>
            <a href="#">Siège</a>
            <a href="#">Plan du site</a>
        </div>

        <div class="footer-box">
            <h3>Informations légales</h3>
            <a href="#">Mentions légales</a>
            <a href="#">Gestions des cookies</a>
        </div>

    </section>

    <script src="https://unpkg.com/feather-icons">
    </script>
    <script>
      feather.replace();
    </script>

    <script>

        var menu_toggle = document.querySelector('.menu_toggle');
        var menu = document.querySelector('.menu');
        var menu_toggle_span = document.querySelector('.menu_toggle span');

        menu_toggle.onclick = function(){
            menu_toggle.classList.toggle('active');
            menu_toggle_span.classList.toggle('active');
            menu.classList.toggle('responsive') ;
        }

        const eye=document.querySelector('.feather-eye');
        const eyeoff=document.querySelector('.feather-eye-off');
        const passwordField=document.querySelector('input[type=password]');

        eye.addEventListener('click', () => {
          eye.style.display="none";
          eyeoff.style.display="block";
          passwordField.type="text";
        });

        eyeoff.addEventListener('click', () => {
          eyeoff.style.display="none";
          eye.style.display="block";
          passwordField.type="password";
        });
    </script>
</body>
</html>