<?php
session_start();
include("message.php");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <?php include("base.php"); ?>
    <?php include("message.php"); ?>

    <h1 class="text-center mt-4">Connexion</h1>

    <form class="w-25 mx-auto mt-4" action="../controller/login_controller.php" method="POST">
        <label class="form-label" for="email_player">Email de connexion</label>
        <input type="email" class="form-control" name="email_player" required>

        <label class="form-label mt-3" for="password_player">Mot de passe</label>
        <input class="form-control" type="password" name="password_player" required>

        <div class="text-center my-4">
            <input class="btn btn-primary" type="submit" value="Connexion">
        </div>
    </form>
</body>

</html>