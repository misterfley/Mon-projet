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
    <?php include("message.php"); ?> <!-- Message toast -->

    <div id="thanos" class="text-center">
        <img src="popcorn2.png" class="img-fluid" alt="Popcorn" />
        <h1 class="text-light">"Plongez dans l'univers des échecs, tout en vous amusant."</h1>
    </div>

    <h1 class="text-center mt-5">
        <?php
        if (isset($_SESSION['name'])) {
            echo "Bienvenue, " . $_SESSION['name'] . "!";
        } else {
            echo "Veuillez vous connecter.";
        }
        ?>
    </h1>

</body>

</html>