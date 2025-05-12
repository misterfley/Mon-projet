<?php
session_start();
include "pdo.php";  // Connexion PDO

// 1. CSRF
//hash_equals() est mieux qu’un simple ===, car il est résistant aux attaques par timing (qui pourraient donner des infos sur la validité du token).
if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    exit('Requête invalide (CSRF).');
}
unset($_SESSION['csrf_token']);
$firstname = trim($_POST['firstname_player']        ?? '');
$lastname  = trim($_POST['lastname_player']         ?? '');
$nickname  = trim($_POST['nickname_player']         ?? '');
$email     = trim($_POST['email_player']            ?? '');
$pass      = $_POST['password_player']              ?? '';
$pass2     = $_POST['password_confirm_player']      ?? '';

if (!$firstname || !$lastname || !$nickname  ||  !$email || !$pass || !$pass2) {
    header("Location: ../view/add_user_form.php?message=" . urlencode('Veuillez remplir tous les champs.') . "&status=error");
    exit;
}

if ($pass !== $pass2) {
    header("Location: ../view/add_user_form.php?message=" . urlencode('Les mots de passe ne correspondent pas.') . "&status=error");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../view/add_user_form.php?message=" . urlencode('Email invalide.') . "&status=error");
    exit;
}

$stmt = $pdo->prepare('SELECT 1 FROM player WHERE email_player = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    header("Location: ../view/add_user_form.php?message=" . urlencode('Email déjà utilisé.') . "&status=error");
    exit;
}
$stmt = $pdo->prepare('SELECT 1 FROM player WHERE nickname_player = ?');
$stmt->execute([$nickname]);
if ($stmt->fetch()) {
    header("Location: ../view/add_user_form.php?message="
        . urlencode('Pseudo déjà utilisé.')
        . "&status=error");
    exit;
}


$hash = password_hash($pass, PASSWORD_ARGON2I);

// 8. Upload optionnel
$newName = null;
if (!empty($_FILES['image_player']) && $_FILES['image_player']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['image_player']['error'] !== UPLOAD_ERR_OK) {
        header("Location: ../view/add_user_form.php?message=" . urlencode("Erreur pendant l'upload.") . "&status=error");
        exit;
    }
    if ($_FILES['image_player']['size'] > 2 * 1024 * 1024) {
        header("Location: ../view/add_user_form.php?message=" . urlencode("Le fichier doit être < 2 Mo.") . "&status=error");
        exit;
    }
    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($_FILES['image_player']['tmp_name']);
    $allowed = [
        'image/jpeg'    => 'jpg',
        'image/png'     => 'png',
        'image/gif'     => 'gif',
        'image/webp'    => 'webp',
        'image/svg+xml' => 'svg',
    ];
    if (!isset($allowed[$mime])) {
        header("Location: ../view/add_user_form.php?message=" . urlencode("Format de fichier invalide.") . "&status=error");
        exit;
    }
    $ext     = $allowed[$mime];
    $newName = sha1(uniqid('', true))
        . "_{$firstname}_{$lastname}.$ext";
    if (!move_uploaded_file(
        $_FILES['image_player']['tmp_name'],
        __DIR__ . "/../public/img/uploads/$newName"
    )) {
        header("Location: ../view/add_user_form.php?message=" . urlencode("Échec de l'upload.") . "&status=error");
        exit;
    }
}

// 9. Insertion en base
$sql  = "INSERT INTO player
    (firstname_player, lastname_player, nickname_player, email_player, password_player, image_player)
    VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        $firstname,
        $lastname,
        $nickname,
        $email,
        $hash,
        $newName,
    ]);
} catch (PDOException $e) {
    header("Location: ../view/add_user_form.php?message=" . urlencode("Erreur interne.") . "&status=error");
    exit;
}

// 10. Succès
header("Location: ../view/login.php?message=" . urlencode("Inscription réussie.") . "&status=success");
exit;
