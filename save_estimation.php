<?php
// Active les erreurs pendant le développement
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// --- CONFIGURATION DE LA BASE DE DONNÉES ---
$host = "localhost";       // ou ton hôte MySQL
$dbname = "estimation_gironde";
$username = "root";        // à adapter si ton serveur a un autre utilisateur
$password = "";            // à adapter également

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Connexion échouée : " . $e->getMessage()]);
    exit;
}

// --- TRAITEMENT DE LA REQUÊTE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Aucune donnée reçue"]);
        exit;
    }

    // Récupération des infos du formulaire
    $nom = htmlspecialchars($data['name'] ?? 'Inconnu');
    $email = htmlspecialchars($data['email'] ?? 'Non précisé');
    $ville = htmlspecialchars($data['city'] ?? 'Non précisée');
    $type = htmlspecialchars($data['propertyType'] ?? 'Non précisé');
    $surface = intval($data['surface'] ?? 0);
    $prix = intval($data['estimation'] ?? 0);
    $prixm2 = intval($data['prixM2'] ?? 0);
    $details = json_encode($data['details'] ?? []);
    $date = date('Y-m-d H:i:s');

    // Enregistrement en base
    try {
        $stmt = $pdo->prepare("INSERT INTO estimations (date_estimation, nom, email, ville, type_bien, surface, prix_total, prix_m2, details)
                               VALUES (:date, :nom, :email, :ville, :type, :surface, :prix, :prixm2, :details)");
        $stmt->execute([
            ':date' => $date,
            ':nom' => $nom,
            ':email' => $email,
            ':ville' => $ville,
            ':type' => $type,
            ':surface' => $surface,
            ':prix' => $prix,
            ':prixm2' => $prixm2,
            ':details' => $details
        ]);

        echo json_encode(["status" => "success", "message" => "Estimation enregistrée avec succès ✅"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Erreur SQL : " . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Méthode non autorisée"]);
}
?>
