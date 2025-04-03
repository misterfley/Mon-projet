<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jeu d'échecs</title>

    <!-- Bootstrap & icônes -->
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.min.css">

    <!-- Tes styles -->
    <link rel="stylesheet" href="../public/css/style.css"> <!-- style général du site -->
    <link rel="stylesheet" href="../public/css/board.css"> <!-- spécifique au plateau -->

    <!-- Scripts -->
    <script defer src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</head>


<body>
    <?php include("base.php"); ?>
    <?php if (isset($_GET['message']) && isset($_GET['status'])): ?>
        <div class="alert alert-<?php echo $_GET['status']; ?> text-center m-3" role="alert">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>
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