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
    <title>Menu multijoueur</title>
    <?php include("header.php"); ?>
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