<?php

session_start();
// verifie si l'admin est connecte 
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location:login.php");
    exit ;
}

$host = "localhost";
$user = "root"; 
$pass = ""; 
$dbname = "estimation_gironde"; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Suppression d’une estimation
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo->query("DELETE FROM estimations WHERE id = $id");
    header("Location: admin_estimation.php");
    exit;
}

// Récupération des estimations
$stmt = $pdo->query("SELECT * FROM estimations ORDER BY date_estimation DESC");
$estimations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour sécuriser les champs
function safe($array, $key, $default = '') {
    return isset($array[$key]) ? $array[$key] : $default;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Estimations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   
<div class="container mt-4">
    <h1 class="mb-4 text-center">📊 Tableau des estimations</h1>

    <a href="admin_estimation.php" class="btn btn-primary mb-3">🔄 Rafraîchir</a>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Ville</th>
                <th>Type</th>
                <th>Surface</th>
                <th>Estimation (€)</th>
                <th>Prix/m² (€)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($estimations) > 0): ?>
                <?php foreach ($estimations as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars(safe($row,'id','')) ?></td>
                        <td><?= htmlspecialchars(safe($row,'date_estimation','')) ?></td>
                        <td><?= htmlspecialchars(safe($row,'nom','')) ?></td>
                        <td><?= htmlspecialchars(safe($row,'email','')) ?></td>
                        <td><?= htmlspecialchars(safe($row,'ville','')) ?></td>
                        <td><?= htmlspecialchars(safe($row,'type_bien','')) ?></td>
                        <td><?= htmlspecialchars(safe($row,'surface','')) ?></td>
                        <td><?= number_format(safe($row,'prix_total',0), 0, ',', ' ') ?></td>
                        <td><?= number_format(safe($row,'prix_m2',0), 0, ',', ' ') ?></td>
                        <td>
                            <a href="?delete=<?= safe($row,'id','') ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette estimation ?')">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="11" class="text-center">Aucune estimation enregistrée.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
    <div class="mt-4 d-flex justify-content-center gap-2">
    <a href="../index.html" class="btn btn-primary">🏠 Accueil</a>
    <a href="../estimation.html" class="btn btn-success">📊 Estimation</a>
     <a href="logout.php" class="btn btn-danger btn-sm">Deconnexion</a>

</div>
</body>
</html>
