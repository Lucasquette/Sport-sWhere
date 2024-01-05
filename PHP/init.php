<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map_app";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Définir l'encodage de la connexion à UTF-8
$conn->query("SET NAMES 'utf8'");

// Chemin du fichier CSV
$csvFile = 'C:\Users\malab\OneDrive\Desktop\jeuxDeDonnees.csv';

// Lecture du fichier CSV
if (($handle = fopen($csvFile, "r")) !== FALSE) {
    if (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
        $sql = "CREATE TABLE infra_sport (";
        $colNum = 1;
        foreach ($data as $column) {
            // Nommer les colonnes comme col1, col2, ..., colN
            $sql .= "col" . $colNum++ . " TEXT,";
        }
        $sql = rtrim($sql, ',') . ") CHARACTER SET utf8 COLLATE utf8_general_ci;";
        
        // Exécuter la requête pour créer la table
        if ($conn->query($sql) === TRUE) {
            echo "Table créée avec succès\n";
        } else {
            echo "Erreur lors de la création de la table : " . $conn->error . "\n";
        }
    }
    fclose($handle);
}

// Réouverture du fichier pour l'importation des données
if (($handle = fopen($csvFile, "r")) !== FALSE) {
    $row = 0;
    while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
        if ($row == 0) { // Ignorer les en-têtes de colonne
            $row++;
            continue;
        }
        // Préparer la requête SQL pour l'insertion des données
        $sql = "INSERT INTO infra_sport VALUES ('" . join("','", array_map(function($value) use ($conn) {
            // Gérer l'encodage des données
            return $conn->real_escape_string(utf8_encode($value));
        }, $data)) . "');";
        if ($conn->query($sql) === TRUE) {
            echo "Données importées avec succès\n";
        } else {
            echo "Erreur lors de l'importation des données : " . $conn->error . "\n";
        }
        $row++;
    }
    fclose($handle);
}

// Fermer la connexion
$conn->close();
?>
