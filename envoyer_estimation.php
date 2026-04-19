<?php
require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Lire le JSON envoyé par fetch
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['email']) || empty($input['estimation'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données incomplètes']);
    exit;
}

$name = htmlspecialchars($input['name'] ?? 'Client');
$email = filter_var($input['email'], FILTER_VALIDATE_EMAIL);
$estimation = number_format($input['estimation'], 0, ',', ' ');
$city = htmlspecialchars($input['city'] ?? '');
$type = htmlspecialchars($input['propertyType'] ?? '');
$surface = htmlspecialchars($input['surface'] ?? '');

if (!$email) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email invalide']);
    exit;
}

$mail = new PHPMailer(true);

try {
    // CONFIGURATION SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';   // ← ton serveur SMTP
    $mail->SMTPAuth   = 'true'; // ← ton adresse
    $mail->Password   = 'fucubueqsxctqaom';          // ← ton mot de passe SMTP
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // EXPÉDITEUR & DESTINATAIRE
    $mail->setFrom('u3735309927@gmail.com', 'Estimation Gironde');
    $mail->addAddress($email, $name);

    // CONTENU DU MESSAGE
    $mail->isHTML(true);
    $mail->Subject = "Votre estimation immobilière - Estimation Gironde";
    $mail->Body = "
        <p>Bonjour <strong>{$name}</strong>,</p>
        <p>Voici le montant estimé de votre bien situé à <strong>{$city}</strong> :</p>
        <h2 style='color:#007bff;'>{$estimation} €</h2>
        <p>Détails :</p>
        <ul>
            <li>Type de bien : {$type}</li>
            <li>Surface : {$surface} m²</li>
        </ul>
        <p>Merci d’avoir utilisé notre service d’estimation.<br>
        L’équipe <strong>Estimation Gironde</strong>.</p>
    ";
    $mail->AltBody = "Bonjour {$name},\nVotre estimation est de {$estimation} € pour un {$type} à {$city}.";

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Email envoyé avec succès.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => "Erreur d'envoi : {$mail->ErrorInfo}"]);
}
