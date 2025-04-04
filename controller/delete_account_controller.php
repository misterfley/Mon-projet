<?php
session_start();
require_once("pdo.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php?message=Accès refusé.&status=danger");
    exit();
}

$userId = $_SESSION['user_id'];
$confirm = trim($_POST['confirm'] ?? '');

if (strtoupper($confirm) !== 'SUPPRIMER') {
    header("Location: ../view/delete_account.php?message=Confirmation incorrecte.&status=warning");
    exit();
}

// Récupérer l’image avant suppression
$stmt = $pdo->prepare("SELECT image_player FROM player WHERE id_player = ?");
$stmt->execute([$userId]);
$image = $stmt->fetchColumn();

// Supprimer image si pas par défaut
if ($image && $image !== 'default-avatar.png') {
    $imgPath = '../public/img/uploads/' . $image;
    if (file_exists($imgPath)) {
        unlink($imgPath);
    }
}

// Supprimer le compte
$delete = $pdo->prepare("DELETE FROM player WHERE id_player = ?");
$delete->execute([$userId]);

// Nettoyer la session
session_unset();
session_destroy();

// Rediriger vers la homepage
header("Location: ../view/homepage.php?message=Compte supprimé avec succès.&status=success");
exit();
