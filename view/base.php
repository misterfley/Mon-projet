<?php session_start();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="http://localhost/Mon-Projet/">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <title>ECHECMANIA</title>
</head>

<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg bg-dark">
        <div class="container">
            <a class="navbar-brand text-light" href="<?php echo isset($_SESSION['name']) ? 'game.php' : 'index.php'; ?>">
                ECHECMANIA <img src="logo.png" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php if (!isset($_SESSION['name'])) { ?>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="view/add_user_form.php">
                                <h5>Inscription</h5>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="view/login.php">
                                <h5>Connexion</h5>
                            </a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="controller/logout_controller.php">
                                <h5>Déconnexion</h5>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="game.php">
                                <h5>Jouer</h5>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>