<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Accueil</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <script defer src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <?php include("base.php"); ?>
    <?php include("message.php"); ?>

    <div class="container text-center mt-5 formal">

        <?php if (!isset($_SESSION['nickname_player'])): ?>
            <h2 class="text-light mb-4">Identifie-toi pour entrer dans la partie.</h2>
        <?php else: ?>
            <h2 class="text-success mb-4">Bienvenue, <?php echo htmlspecialchars($_SESSION['nickname_player']); ?> !</h2>
        <?php endif; ?>

        <img src="../public/img/static/roquenroll8.png" class="img-fluid mb-4 logo-home-animated" alt="Logo Echecs" style="max-width: 400px;">



        <h4 class="text-muted mt-3">"Connecté, Fun Addictif,</h4>
        <h4 class="text-muted mt-3">Rejoins Roque'N'Roll, là où les pions deviennent rois."</h4>
    </div>



</body>

</html>