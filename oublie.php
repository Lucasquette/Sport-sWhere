<?php
session_start(); // Assurez-vous que la session est démarrée sur toutes les pages où vous utilisez les sessions

// Script de déconnexion
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: acc.php"); // Redirige vers la page d'accueil après la déconnexion
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport's Where</title>
    <link rel="stylesheet" href="CSS/oublie.css">
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
        <form method="GET" action="01_target.php">
          <h1>Réinitialiser votre mot de passe</h1>
          <p>Veuillez saisir votre adresse e-mail ci-dessous. Nous vous enverrons les instructions pour créer un nouveau mot de passe.</p>
          <div class="input-box">
            <input type="text" placeholder="Adresse e-mail" required>
          </div>
          <button type="submit" class="btn">Réinitialiser</button>
          <div class="sanscompte">
            <p>Vous n'avez pas de compte ? <a href="creation.html">Créer un compte</a></p>
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
            menu.classList.toggle('responsive') ;
        }

    </script>
</body>
</html>