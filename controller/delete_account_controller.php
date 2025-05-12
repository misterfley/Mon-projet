<?php
session_start();
require_once("pdo.php");
if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    exit("Requête CSRF invalide.");
}
unset($_SESSION['csrf_token']); // Supprime le token après usage

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

$stmt = $pdo->prepare("SELECT image_player FROM player WHERE id_player = ?");
$stmt->execute([$userId]);
$image = $stmt->fetchColumn();

if ($image && $image !== 'default-avatar.png') {
    $imgPath = '../public/img/uploads/' . $image;
    if (file_exists($imgPath)) {
        unlink($imgPath);
    }
}


$delete = $pdo->prepare("DELETE FROM player WHERE id_player = ?");
$delete->execute([$userId]);

session_unset();
session_destroy();


header("Location: ../view/homepage.php?message=Compte supprimé avec succès.&status=success");
exit();
