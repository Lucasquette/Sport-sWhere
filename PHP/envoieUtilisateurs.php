<?php
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

// Récupérer les données du formulaire
$email = $_GET['email'];
$nomUtilisateur = $_GET['nom_utilisateur'];
$motDePasse = $_GET['mot_de_passe']; // Pensez à sécuriser le mot de passe
$adressePostale = $_GET['adresse_postale'];

// Préparer la requête SQL
$sql = "INSERT INTO utilisateurs (email, nom, mdp, adresse)
VALUES ('$email', '$nomUtilisateur', '$motDePasse', '$adressePostale')";

// Exécuter la requête SQL
if ($conn->query($sql) === TRUE) {
  // Définir un message de succès dans la variable de session
  $_SESSION['message_succes'] = "Compte créé avec succès !";
  header("Location: ../creation.php");
  exit;
} else {
  echo "Erreur : " . $sql . "<br>" . $conn->error;
}


// Fermer la connexion
$conn->close();
?>
