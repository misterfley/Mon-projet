<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg bg-dark">
    <div class="container">
        <a class="navbar-brand text-light" href="<?php echo isset($_SESSION['nickname_player']) ? 'game.php' : 'index.php'; ?>">
            ECHECMANIA <img src="logo.png" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (!isset($_SESSION['nickname_player'])) { ?>
                    <!-- Non connecté -->
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
                    <!-- Connecté -->
                    <li class="nav-item">
                        <a class="nav-link text-light" href="game.php">
                            <h5>Jouer</h5>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="../controller/logout_controller.php">
                            <h5>Déconnexion</h5>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>