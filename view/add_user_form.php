<?php
session_start();
// Génération du token CSRF s’il n’existe pas
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <?php include("header.php"); ?>
    <title>Inscription</title>

</head>

<body>
    <?php include("nav.php"); ?>
    <?php include("message.php");  ?>

    <main>
        <div class="container my-4">
            <h1 class="text-center text-primary">Inscription</h1>

            <form action="../controller/add_user_controller.php"
                method="POST"
                class="w-50 mx-auto mt-4"
                enctype="multipart/form-data">

                <!-- CSRF Token -->
                <input type="hidden"
                    name="csrf_token"
                    value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                <label class="form-label" for="firstname_player">Prénom</label>
                <input class="form-control"
                    id="firstname_player"
                    type="text"
                    name="firstname_player"
                    placeholder="Prénom"
                    value="<?= htmlspecialchars($_POST['firstname_player'] ?? '', ENT_QUOTES) ?>"
                    required>

                <label class="form-label mt-3" for="lastname_player">Nom</label>
                <input class="form-control"
                    id="lastname_player"
                    type="text"
                    name="lastname_player"
                    placeholder="Nom"
                    value="<?= htmlspecialchars($_POST['lastname_player'] ?? '', ENT_QUOTES) ?>"
                    required>

                <label class="form-label mt-3" for="nickname_player">Pseudo</label>
                <input class="form-control"
                    id="nickname_player"
                    type="text"
                    name="nickname_player"
                    placeholder="Pseudo"
                    value="<?= htmlspecialchars($_POST['nickname_player'] ?? '', ENT_QUOTES) ?>"
                    required>

                <label class="form-label mt-3" for="email_player">Email</label>
                <input class="form-control"
                    id="email_player"
                    type="email"
                    name="email_player"
                    placeholder="Email"
                    value="<?= htmlspecialchars($_POST['email_player'] ?? '', ENT_QUOTES) ?>"
                    required>

                <label class="form-label mt-3" for="password_player">Mot de passe</label>
                <input class="form-control"
                    id="password_player"
                    type="password"
                    name="password_player"
                    placeholder="Mot de passe"
                    required>

                <label class="form-label mt-3" for="password_confirm_player">Confirmer le mot de passe</label>
                <input class="form-control"
                    id="password_confirm_player"
                    type="password"
                    name="password_confirm_player"
                    placeholder="Confirmer le mot de passe"
                    required>

                <label class="form-label mt-3" for="image_player">Photo de profil (optionnelle)</label>
                <input class="form-control"
                    id="image_player"
                    type="file"
                    name="image_player"
                    accept="image/*">

                <div class="text-center">
                    <button class="btn btn-primary mt-4" type="submit">
                        S'inscrire
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include("footer.php"); ?>
</body>

</html>