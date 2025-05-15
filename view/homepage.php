<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Accueil</title>
    <?php include("header.php"); ?>
</head>

<body>

    <?php include("nav.php"); ?>
    <?php include("message.php"); ?>
    <main>
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
    </main>
    <?php include("footer.php"); ?>

</body>

</html>