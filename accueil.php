<?php
/**
 * Page réservée aux utilisateurs connectés.
 */
session_start();

// Pas connecté ? On renvoie vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main>
    <h1>Bonjour <?= htmlspecialchars($_SESSION['user_login']) ?> !</h1>
    <p>Vous êtes connecté(e).</p>
    <a href="logout.php">Se déconnecter</a>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</main>
</body>
</html>