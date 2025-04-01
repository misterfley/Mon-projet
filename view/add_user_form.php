<?php
include "base.php"; // Inclusion du fichier de base
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Inscription</title>
</head>

<body>
    <div class="container">
        <h1 class="text-center text-primary">Inscription</h1>

        <?php
        // Affichage des messages d'erreur ou de succès
        if (isset($_GET['message'])) {
            $status = $_GET['status'] ?? 'info';
            $message = htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8');
            echo "<div class='alert alert-$status text-center'>$message</div>";
        }
        ?>

        <form action="controller/add_user_controller.php" method="POST" class="w-50 mx-auto" enctype="multipart/form-data">
            <label class="form-label" for="firstname_player">Prénom</label>
            <input class="form-control" type="text" name="firstname_player" placeholder="Prénom" required>

            <label class="form-label" for="lastname_player">Nom</label>
            <input class="form-control" type="text" name="lastname_player" placeholder="Nom" required>

            <label class="form-label" for="nickname_player">Pseudo</label>
            <input class="form-control" type="text" name="nickname_player" placeholder="Pseudo" required>

            <label class="form-label" for="email_player">Email</label>
            <input class="form-control" type="email" name="email_player" placeholder="Email" required>

            <label class="form-label" for="password_player">Mot de passe</label>
            <input class="form-control" type="password" name="password_player" placeholder="Mot de passe" required>

            <label class="form-label" for="password_confirm_player">Confirmer le mot de passe</label>
            <input class="form-control" type="password" name="password_confirm_player" placeholder="Confirmer le mot de passe" required>

            <label class="form-label" for="image_player">Photo de profil</label>
            <input class="form-control" type="file" name="image_player">

            <div class="text-center">
                <input class="btn btn-primary mt-3" type="submit" value="S'inscrire">
            </div>
        </form>
    </div>
</body>

</html>