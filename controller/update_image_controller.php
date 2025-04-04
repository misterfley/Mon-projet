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

$file = $_FILES['image_player'];
$allowed = ['jpg', 'jpeg', 'png', 'webp'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    header("Location: ../view/update_image.php?message=Format d'image non autorisé.&status=warning");
    exit();
}

$newFileName = uniqid('photo_') . '.' . $ext;
$destination = '../public/img/uploads/' . $newFileName;



if (!move_uploaded_file($file['tmp_name'], $destination)) {
    header("Location: ../view/update_image.php?message=Erreur lors de l'enregistrement du fichier.&status=danger");
    exit();
}


$stmt = $pdo->prepare("SELECT image_player FROM player WHERE id_player = ?");
$stmt->execute([$userId]);
$currentImage = $stmt->fetchColumn();

if ($currentImage && $currentImage !== 'default-avatar.png') {
    $oldPath = '../public/img/uploads/' . $currentImage;
    if (file_exists($oldPath)) {
        unlink($oldPath);
    }
}


$update = $pdo->prepare("UPDATE player SET image_player = ? WHERE id_player = ?");
$update->execute([$newFileName, $userId]);

// Mettre à jour la session
$_SESSION['image_player'] = $newFileName;

header("Location: ../view/profile.php?message=Photo mise à jour avec succès.&status=success");
exit();
