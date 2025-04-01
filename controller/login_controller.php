<?php
include "../controller/pdo.php"; // Connexion à la base de données


if (!empty($_POST['email_player']) && !empty($_POST['password_player'])) {

    $email = $_POST['email_player'];
    $sql = "SELECT * FROM player WHERE email_player=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    // Si l'utilisateur est trouvé dans la base de données
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérification du mot de passe
        if (password_verify($_POST['password_player'], $user['password_player'])) {
            session_start(); // Démarre la session

            // Crée les variables de session nécessaires
            $_SESSION['firstname_player'] = $user['firstname_player'];
            $_SESSION['lastname_player'] = $user['lastname_player'];
            $_SESSION['nickname_player'] = $user['nickname_player'];
            $_SESSION['token'] = bin2hex(random_bytes(16)); // Protéger contre les attaques CSRF
            $_SESSION['user_id'] = $user['id_player']; // ID unique de l'utilisateur
            $_SESSION['image_player'] = $user['image_player']; // Si l'utilisateur a une image de profil

            // Redirige vers la page d'accueil ou autre page après la connexion
            header("Location: ../view/board.php");
            exit(); // Toujours appeler exit() après une redirection
        } else {
            // Mot de passe incorrect
            header("Location: login.php?message=Identifiants incorrects.&status=error");
            exit();
        }
    } else {
        // L'email n'existe pas dans la base de données
        header("Location: login.php?message=Identifiants incorrects.&status=error");
        exit();
    }
} else {
    // Si le formulaire n'est pas bien rempli
    header("Location: login.php?message=Entrez vos identifiants correctement.&status=error");
    exit();
}
