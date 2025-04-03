<?php
session_start();

// Supprimer toutes les variables de session
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

// Rediriger avec un message
header("Location: ../view/homepage.php?message=Déconnexion réussie&status=success");
exit();
