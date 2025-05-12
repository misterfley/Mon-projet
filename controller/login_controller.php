<?php
session_start();
require_once("pdo.php");

if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    exit("Requête CSRF invalide.");
}
unset($_SESSION['csrf_token']);


if (empty($_POST['email_player']) || empty($_POST['password_player'])) {
    header("Location: ../view/login.php?message=" . urlencode('Veuillez remplir tous les champs.') . "&status=error");
    exit;
}

$email    = trim($_POST['email_player']);
$password = $_POST['password_player'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../view/login.php?message=" . urlencode('Email invalide.') . "&status=error");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM player WHERE email_player = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$user || !password_verify($password, $user['password_player'])) {
    header("Location: ../view/login.php?message=" . urlencode('Identifiants incorrects.') . "&status=error");
    exit;
}

// Réinitialisation de l’ID de session pour éviter la fixation
session_regenerate_id(true);


$_SESSION['user_id']         = $user['id_player'];
$_SESSION['firstname_player'] = $user['firstname_player'];
$_SESSION['lastname_player']  = $user['lastname_player'];
$_SESSION['nickname_player']  = $user['nickname_player'];
$_SESSION['image_player']     = $user['image_player'] ?? null;

//  (Optionnel) Génération d’un token CSRF pour les futurs formulaires authentifiés
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

//  Redirection vers le profil
header("Location: ../view/profile.php?message=" . urlencode('Connexion réussie.') . "&status=success");
exit;
