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
    <link rel="stylesheet" href="CSS/con.css">

    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="CSS/menu_déroulant_profil.css">
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
        <div class="contact A">
            <h1>Formulaire</h1>
            <form class="contact-form" action="contact.html" method="post">
                <input type="text" class="contact-form-text" placeholder="Nom" required>
                <input type="text" class="contact-form-text" placeholder="Prenom" required>
                <input type="text" class="contact-form-text" placeholder="Adresse e-mail" required>
                <textarea class="contact-form-text" placeholder="Votre message" required></textarea>
                <input type="submit" class="contact-form-btn" value="Envoyer" id="button">
            </form>
        </div>

        <div class="contact info">
            <h3>Nous contacter</h3>
            <div class="infoBox ">
                <div>
                    <span><img src="Images/viber.png"></span>
                    <p>+33 1 23 45 67 89</p>
                </div>

                <div>
                    <span><img src="Images/horloge-et-calendrier.png"></span>
                    <p> Lundi - Vendredi <br> 7H30-13H | 14H30-17H </p>
                </div>
            </div>

            <div class="carte">
                <p><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.8487693927414!2d2.26528171083557!3d48.842023271210394!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e67abe049465cd%3A0x497f02af0718f372!2sIUT%20de%20Paris%20-%20Rives%20de%20Seine%20-%20Universit%C3%A9%20Paris%20Cit%C3%A9!5e0!3m2!1sfr!2sfr!4v1703872678550!5m2!1sfr!2sfr" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></p>
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
        document.addEventListener('DOMContentLoaded', function() {
            var info = document.querySelector('.contact.info'); 
            var elementA = document.querySelector('.contact.A'); 
            var elementB = document.getElementsByClassName('input');
            var inputsTexte = document.querySelectorAll('.contact-form-text');

            info.addEventListener('mouseover', function() {
                elementA.style.transform = 'translateY(40px)';
                info.style.transform='translateY(-60px)';
                elementA.style.transition = 'transform 0.3s ease';
                elementA.style.backgroundColor = 'rgba(253, 252, 252, 0.76)';
                info.style.color = 'white';
                inputsTexte.forEach(function(input) {
                    input.style.background = 'white';
                });

                info.style.transform = 'translateY(-20px)';
                info.style.transition = 'transform 0.3s ease';
            });

            info.addEventListener('mouseout', function() {
                elementA.style.transform = 'translateY(0)';
                elementA.style.backgroundColor = '#0e2c4a';
       
                info.style.transform = 'translateY(0)';
                inputsTexte.forEach(function(input) {
                    input.style.background = ' #0b2743';
                });
            });
        });


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