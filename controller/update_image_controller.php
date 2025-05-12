<?php
session_start();
require_once("pdo.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php?message=Accès refusé.&status=danger");
    exit();
}

$userId = $_SESSION['user_id'];

if (!isset($_FILES['image_player']) || $_FILES['image_player']['error'] !== UPLOAD_ERR_OK) {
    header("Location: ../view/update_image.php?message=Erreur lors de l'envoi du fichier.&status=danger");
    exit();
}

// Limite de poids : 2 Mo
if ($_FILES['image_player']['size'] > 2 * 1024 * 1024) {
    header("Location: ../view/update_image.php?message=Image trop lourde (2 Mo max).&status=warning");
    exit();
}

// Vérifie le vrai type MIME
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($_FILES['image_player']['tmp_name']);
$allowed = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'image/gif'  => 'gif'
];

if (!isset($allowed[$mime])) {
    header("Location: ../view/update_image.php?message=Type de fichier interdit.&status=warning");
    exit();
}

$ext = $allowed[$mime]; //  Déterminer l'extension autorisée
$newFileName = sha1(uniqid('', true)) . "_{$userId}.$ext";
$destination = '../public/img/uploads/' . $newFileName;

if (!move_uploaded_file($_FILES['image_player']['tmp_name'], $destination)) {
    header("Location: ../view/update_image.php?message=Échec de l'enregistrement du fichier.&status=danger");
    exit();
}

// Supprimer l'ancienne image si elle existe (et n’est pas par défaut)
$stmt = $pdo->prepare("SELECT image_player FROM player WHERE id_player = ?");
$stmt->execute([$userId]);
$currentImage = $stmt->fetchColumn();

if ($currentImage && $currentImage !== 'default-avatar.png') {
    $oldPath = '../public/img/uploads/' . $currentImage;
    if (file_exists($oldPath)) {
        unlink($oldPath);
    }
}

// Mettre à jour la base
$update = $pdo->prepare("UPDATE player SET image_player = ? WHERE id_player = ?");
$update->execute([$newFileName, $userId]);

$_SESSION['image_player'] = $newFileName;

header("Location: ../view/profile.php?message=Photo mise à jour avec succès.&status=success");
exit();
