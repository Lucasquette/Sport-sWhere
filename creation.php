<?php
session_start();

// Script de déconnexion
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: acc.php");
    exit;
}

$message_succes = "";
$message_erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "map_app";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $conn->real_escape_string($_POST['email']);
    $nomUtilisateur = $conn->real_escape_string($_POST['nom_utilisateur']);
    $motDePasse = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $adressePostale = $conn->real_escape_string($_POST['adresse_postale']);

    // Vérifier si l'adresse email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_erreur = "Adresse email invalide.";
    } else {
        // Vérifier si l'utilisateur ou l'email existe déjà
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE nom = ? OR email = ?");
        $stmt->bind_param("ss", $nomUtilisateur, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message_erreur = "Un utilisateur avec ce nom ou cette adresse email existe déjà.";
        } else {
            // Insérer le nouvel utilisateur
            $stmt = $conn->prepare("INSERT INTO utilisateurs (email, nom, mdp, adresse) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $email, $nomUtilisateur, $motDePasse, $adressePostale);

            if ($stmt->execute()) {
                $_SESSION['nom_utilisateur'] = $nomUtilisateur;
                $message_succes = "Compte créé avec succès !";
            } else {
                $message_erreur = "Erreur : " . $conn->error;
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport's Where</title>
    <link rel="stylesheet" href="CSS/creation.css">
    <link rel="stylesheet" href="CSS/menu_déroulant_profil.css">
    


    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
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
    
    <section class="home">
      <div class="formulaire">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <h1>Créer un compte</h1>
          <?php if (!empty($message_succes)) { echo "<p class='message-succes'>$message_succes</p>"; } ?>
          <?php if (!empty($message_erreur)) { echo "<p class='message-erreur'>$message_erreur</p>"; } ?>
          <div class="input-box">
            <input type="text" name="email" placeholder="Adresse e-mail" required>
          </div>
          <div class="input-box">
            <input type="text" name="nom_utilisateur" placeholder="Nom d'utilisateur" required>
          </div>
          <div class="input-box">
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
          </div>
          <div class="input-box">
            <input type="text" name="adresse_postale" placeholder="Adresse postale " required>
          </div>
          <button type="submit" class="btn">Créer mon compte</button>
          <div class="aveccompte">
            <p>Vous possédez un compte ? <a href="connexion.html">Se connecter</a></p>
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

    <script>
        var menu_toggle = document.querySelector('.menu_toggle');
        var menu = document.querySelector('.menu');
        var menu_toggle_span = document.querySelector('.menu_toggle span');

        menu_toggle.onclick = function(){
            menu_toggle.classList.toggle('active');
            menu_toggle_span.classList.toggle('active');
            menu.classList.toggle('responsive');
        }
    </script>
</body>
</html>
