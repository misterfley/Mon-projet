<?php
session_start();
include "../controller/pdo.php";
if (!empty($_POST['email_player']) && !empty($_POST['password_player'])) {
    $email = trim($_POST['email_player']);
    $password = $_POST['password_player'];
    // pour éviter les injections SQL
    $sql = "SELECT * FROM player WHERE email_player = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password_player'])) {
        // Authentification OK, création des variables de session
        $_SESSION['firstname_player'] = $user['firstname_player'];
        $_SESSION['lastname_player'] = $user['lastname_player'];
        $_SESSION['nickname_player'] = $user['nickname_player'];
        $_SESSION['user_id'] = $user['id_player'];
        $_SESSION['image_player'] = $user['image_player'] ?? null;
        $_SESSION['token'] = bin2hex(random_bytes(16)); // Protection CSRF
        header("Location: ../view/profile.php");
        exit();
    } else
        header("Location: ../view/login.php?message=Identifiants incorrects.&status=error");
    exit();
} else {
    header("Location: ../view/login.php?message=Veuillez remplir tous les champs.&status=error");
    exit();
}
