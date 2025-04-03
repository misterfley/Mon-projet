<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <?php include("base.php"); ?>

    <div class="container mt-5 text-center">
        <h1 class="mb-4">Profil de <?php echo $_SESSION['nickname_player']; ?></h1>

        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-body">
                <img src="../uploads/<?php echo $_SESSION['image_player'] ?? 'default-avatar.png'; ?>" class="rounded-circle mb-3" width="120" alt="Photo de profil">

                <h4><?php echo $_SESSION['firstname_player'] . ' ' . $_SESSION['lastname_player']; ?></h4>
                <p class="text-muted"><?php echo $_SESSION['email_player'] ?? 'Adresse non disponible'; ?></p>

                <hr>

                <a href="update_password.php" class="btn btn-outline-primary w-100 mb-2">Modifier le mot de passe</a>
                <a href="update_photo.php" class="btn btn-outline-secondary w-100 mb-2">Changer la photo</a>
                <a href="delete_account.php" class="btn btn-outline-danger w-100">Supprimer mon compte</a>
            </div>
        </div>
    </div>
</body>

</html>