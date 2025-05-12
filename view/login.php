<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
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
    <script defer src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body class="d-flex flex-column min-vh-100">
    <?php include("nav.php"); ?>

    <main class="flex-fill">
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                    <h1 class="text-center text-primary mb-4">Connexion</h1>

                    <form
                        action="../controller/login_controller.php"
                        method="POST"
                        class="needs-validation"
                        novalidate>
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <div class="mb-3">
                            <label for="email_player" class="form-label">Email de connexion</label>
                            <input
                                type="email"
                                id="email_player"
                                name="email_player"
                                class="form-control"
                                placeholder="votre@exemple.com"
                                required>
                            <div class="invalid-feedback">Veuillez saisir un email valide.</div>
                        </div>

                        <div class="mb-4">
                            <label for="password_player" class="form-label">Mot de passe</label>
                            <input
                                type="password"
                                id="password_player"
                                name="password_player"
                                class="form-control"
                                placeholder="Mot de passe"
                                required>
                            <div class="invalid-feedback">Le mot de passe est requis.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Connexion</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include("footer.php"); ?>

    <script>
        // Activation de la validation Bootstrap
        (function() {
            'use strict';
            document.querySelectorAll('.needs-validation').forEach(form => {
                form.addEventListener('submit', e => {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });
        })();
    </script>
</body>

</html>