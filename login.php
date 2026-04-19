<?php
// demarrer une session 
session_start();
$adminUser = "abass";
$adminPass = "abass";
if($_SERVER['REQUEST_METHOD']=== 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if($username === $adminUser && $password === $adminPass) {
        $_SESSION['admin_logged_in'] = true ;
        header("Location: admin_estimation.php");
        exit;
    } else {
        $error = "Identification ou Mot de passe Incorrect" ;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Connexion Admin</title>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width: 350px;">
        <h3 class="text-center mb-3">Connexion Admin</h3>
            <?php if(isset($error)) : ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php endif ; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Se Connecter</button>
        </form>

         <div class="mt-4 d-flex justify-content-center gap-2">
            <a href="../index.html" class="btn btn-primary">🏠 Retour à la page d'Accueil</a>
        </div>

    </div>
    
</body>
</html>