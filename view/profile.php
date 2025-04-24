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
    <?php include("message.php"); ?>

    <div class="container mt-5 text-center global">
        <h1 class="mb-4">
            Profil de <?php echo htmlspecialchars($_SESSION['nickname_player'], ENT_QUOTES, 'UTF-8'); ?>
        </h1>
        <div class=" mx-auto" style="max-width:400px">
            <div class="card mx-auto" style="max-width: 400px;">
                <div class="card-body">
                    <?php
                    // On choisit le chemin de l'image : uploads/ si présent, sinon static/default-avatar.webp
                    $imgFile = $_SESSION['image_player'] ?? '';
                    if ($imgFile) {
                        $imgPath = 'uploads/' . $imgFile;
                    } else {
                        $imgPath = 'static/default-avatar.webp';
                    }
                    ?>
                    <img
                        src="../public/img/<?php echo htmlspecialchars($imgPath, ENT_QUOTES, 'UTF-8'); ?>"
                        alt="Avatar de <?php echo htmlspecialchars($_SESSION['nickname_player'], ENT_QUOTES, 'UTF-8'); ?>"
                        class="rounded-circle mb-3"
                        width="150"
                        height="150" />

                    <h4>
                        <?php
                        // Affichage du nom complet
                        $first = $_SESSION['firstname_player'] ?? '';
                        $last  = $_SESSION['lastname_player']  ?? '';
                        echo htmlspecialchars(trim("$first $last"), ENT_QUOTES, 'UTF-8');
                        ?>
                    </h4>

                    <hr>

                    <a href="update_password.php" class="btn btn-outline-primary w-100 mb-2">
                        Modifier le mot de passe
                    </a>
                    <a href="update_image.php" class="btn btn-outline-secondary w-100 mb-2">
                        Changer la photo
                    </a>
                    <a href="delete_account.php" class="btn btn-outline-danger w-100 mb-2">
                        Supprimer mon compte
                    </a>

                    <a href="history.php" class="btn btn-outline-info w-100 mt-2">
                        Historique des parties
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>