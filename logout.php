<?php
/**
 * Déconnexion : on vide et on détruit la session.
 */
session_start();
$_SESSION = [];
session_destroy();
header('Location: login.php');
exit;