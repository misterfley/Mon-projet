<?php session_start();
include("message.php"); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <?php include("base.php"); ?>

    <div class="container my-4">
        <h1 class="text-center text-primary">Inscription</h1>
        <?php if (isset($_GET['message']) && isset($_GET['status'])): ?>
            <div class="alert alert-<?php echo $_GET['status']; ?> text-center" role="alert">
                <?php echo htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="../controller/add_user_controller.php" method="POST" class="w-50 mx-auto mt-4" enctype="multipart/form-data">
            <label class="form-label" for="firstname_player">Prénom</label>
            <input class="form-control" type="text" name="firstname_player" placeholder="Prénom" required>

            <label class="form-label mt-3" for="lastname_player">Nom</label>
            <input class="form-control" type="text" name="lastname_player" placeholder="Nom" required>

            <label class="form-label mt-3" for="nickname_player">Pseudo</label>
            <input class="form-control" type="text" name="nickname_player" placeholder="Pseudo" required>

            <label class="form-label mt-3" for="email_player">Email</label>
            <input class="form-control" type="email" name="email_player" placeholder="Email" required>

            <label class="form-label mt-3" for="password_player">Mot de passe</label>
            <input class="form-control" type="password" name="password_player" placeholder="Mot de passe" required>

            <label class="form-label mt-3" for="password_confirm_player">Confirmer le mot de passe</label>
            <input class="form-control" type="password" name="password_confirm_player" placeholder="Confirmer le mot de passe" required>

            <label class="form-label mt-3" for="image_player">Photo de profil</label>
            <input class="form-control" type="file" name="image_player">

            <div class="text-center">
                <input class="btn btn-primary mt-4" type="submit" value="S'inscrire">
            </div>
        </form>
    </div>
</body>

</html>