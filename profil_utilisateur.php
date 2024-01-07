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

$nomUtilisateur = $_SESSION['nom_utilisateur'];

$stmt = $conn->prepare("SELECT email, adresse FROM utilisateurs WHERE nom = ?");
$stmt->bind_param("s", $nomUtilisateur);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $email = $user['email'];
    $adresse = $user['adresse'];
} else {
    echo "Utilisateur non trouvé.";
    exit;
}

$message_succes = "";
$message_erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailModifie = $conn->real_escape_string($_POST['adresse_email']);
    $adresseModifiee = $conn->real_escape_string($_POST['adresse']);
    $motDePasseModifie = $_POST['mot_de_passe'];

    if (!filter_var($emailModifie, FILTER_VALIDATE_EMAIL)) {
        $message_erreur = "Format d'adresse email invalide.";
    } else {
        if (!empty($motDePasseModifie)) {
            $motDePasseModifie = password_hash($motDePasseModifie, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE utilisateurs SET email = ?, mdp = ?, adresse = ? WHERE nom = ?");
            $stmt->bind_param("ssss", $emailModifie, $motDePasseModifie, $adresseModifiee, $nomUtilisateur);
        } else {
            $stmt = $conn->prepare("UPDATE utilisateurs SET email = ?, adresse = ? WHERE nom = ?");
            $stmt->bind_param("sss", $emailModifie, $adresseModifiee, $nomUtilisateur);
        }

        if ($stmt->execute()) {
            $message_succes = "Informations mises à jour";
        } else {
            $message_erreur = "Erreur lors de la mise à jour des informations: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport's Where</title>
    <link rel="stylesheet" href="CSS/profil_utilisateur.css">
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
                <a href="profil_utilisateur.php?logout">Se déconnecter</a>

            </div>
        </div>
    <?php else: ?>
        <a href="connexion.php" class="login_btn">LOGIN</a>
    <?php endif; ?>
</header>



<section class="home">
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="informations">
            <h1>Informations</h1>
            <?php if ($message_succes): ?>
                <p class="message-succes"><?php echo $message_succes; ?></p>
            <?php endif; ?>
            <?php if ($message_erreur): ?>
                <p class="message-erreur"><?php echo $message_erreur; ?></p>
            <?php endif; ?>
            <h3>Adresse Email</h3>
            <div class="input-box">
                <input type="text" name="adresse_email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <h3>Mot de passe (laisser vide si inchangé)</h3>
            <div class="input-box">
                <input type="password" name="mot_de_passe" placeholder="">
            </div>
            <h3>Adresse postale ou Code postal</h3>
            <div class="input-box">
                <input type="text" name="adresse" value="<?php echo htmlspecialchars($adresse); ?>" required>
            </div>
            <button type="submit" class="btn">Mettre à jour</button>
        </div>
    </form>
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