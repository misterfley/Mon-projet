<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Veuillez vous connecter.&status=warning");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu multijoueur</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <script defer src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include('nav.php'); ?>
    <main>
        <div class="container mt-5 ">
            <div class="row justify-content-center">

                <div class="col-md-4 mb-3">
                    <a href="../controller/create_game.php" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-plus-circle"></i> Créer une partie
                    </a>
                </div>


                <div class="col-md-4 mb-3">
                    <form action="../controller/join_game.php" method="GET">
                        <div class="input-group">
                            <input type="number" class="form-control" name="game_id" placeholder="ID de la partie" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Rejoindre
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="text-center mt-4">
                <a href="games_list.php" class="btn btn-outline-info">
                    Voir les parties disponibles
                </a>
            </div>
        </div>
    </main>
    <?php include("footer.php"); ?>
</body>

</html>