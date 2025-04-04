<?php
session_start();
require_once("pdo.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php?message=Accès non autorisé.&status=danger");
    exit();
}
$userId = $_SESSION['user_id'];
$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (empty($current) || empty($new) || empty($confirm)) {
    header("Location: ../view/update_password.php?message=Veuillez remplir tous les champs.&status=warning");
    exit();
}
if ($new !== $confirm) {
    header("Location: ../view/update_password.php?message=Les mots de passe ne correspondent pas.&status=warning");
    exit();
}
$stmt = $pdo->prepare("SELECT password_player FROM player WHERE id_player = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
if (!$user) {
    header("Location: ../view/update_password.php?message=Utilisateur introuvable.&status=danger");
    exit();
}
if (!password_verify($current, $user['password_player'])) {
    header("Location: ../view/update_password.php?message=Mot de passe actuel incorrect.&status=danger");
    exit();
}
$newHashed = password_hash($new, PASSWORD_DEFAULT);
$update = $pdo->prepare("UPDATE player SET password_player = ? WHERE id_player = ?");
$update->execute([$newHashed, $userId]);
header("Location: ../view/profile.php?message=Mot de passe mis à jour avec succès.&status=success");
exit();
