<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map_app";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sport = $_POST['sport'];

    try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sportPattern = "%$sport%";
        $stmt = $db->prepare("SELECT col94, col96, col2 FROM infra_sport WHERE col24 LIKE :sportPattern");
        $stmt->bindParam(':sportPattern', $sportPattern);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result);
    } catch(PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
        exit;
    }
}
?>
