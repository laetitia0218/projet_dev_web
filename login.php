<?php
/**
 * Page de connexion.
 * Affiche le formulaire et, quand il est envoyé, vérifie l'identifiant et le mot de passe.
 */
session_start(); // la session permet de "se souvenir" que l'utilisateur est connecté
require_once __DIR__ . '/config/database.php';

$erreur = '';

// Le formulaire a été envoyé ?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');       // trim enlève les espaces au début et à la fin
    $password = $_POST['password'] ?? '';

    // 1. On cherche l'utilisateur par son login OU son e-mail.
    // Requête préparée : les valeurs sont envoyées à part, ce qui empêche l'injection SQL.
    $stmt = getPDO()->prepare(
        'SELECT user_id, user_login, user_password FROM user
         WHERE user_login = :login OR user_mail = :mail LIMIT 1'
    );
    $stmt->execute(['login' => $login, 'mail' => $login]);
    $user = $stmt->fetch();

    // 2. password_verify compare le mot de passe tapé avec le hash stocké
    if ($user && password_verify($password, $user['user_password'])) {
        session_regenerate_id(true); // nouvel identifiant de session (sécurité)
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_login'] = $user['user_login'];

        // 3. On met à jour la date de dernière connexion
        $maj = getPDO()->prepare('UPDATE user SET user_date_login = NOW() WHERE user_id = :id');
        $maj->execute(['id' => $user['user_id']]);

        header('Location: accueil.php'); // redirection vers la page protégée
        exit;
    }

    // Message volontairement vague : on ne dit pas si c'est le login ou le mot de passe qui est faux
    $erreur = 'Identifiant ou mot de passe incorrect.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main>
    <h1>Connexion</h1>

    <?php if ($erreur): ?>
        <!-- htmlspecialchars empêche l'injection de code HTML/JS (faille XSS) -->
        <p role="alert"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
        <label for="login">Identifiant ou e-mail</label>
        <input type="text" id="login" name="login" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>
</main>
</body>
</html>