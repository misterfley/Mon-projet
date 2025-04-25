<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark p-0">
    <div class="container">

        <a class="navbar-brand text-light d-flex align-items-center logo" href="<?php echo isset($_SESSION['nickname_player']) ? 'profile.php' : '../index.php'; ?>">
            <img src="../public/img/static/roquenroll7.png" alt="Logo" style="height: 80px; width: auto;" class="me-2">
            ROQUE'N'ROLL
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav align-items-center">
                <?php if (!isset($_SESSION['nickname_player'])): ?>
                    <!-- Non connecté -->
                    <li class="nav-item">
                        <a class="nav-link" href="add_user_form.php">
                            <h5>Inscription</h5>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <h5>Connexion</h5>
                        </a>
                    </li>
                <?php else: ?>
                    <!-- Connecté -->
                    <li class="nav-item">
                        <a class="nav-link" href="play.php">
                            <h5>Jouer</h5>
                        </a>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">
                            <h5>Profil</h5>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../controller/logout_controller.php">
                            <h5>Déconnexion</h5>
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center ms-3">
                        <a class="nav-link d-flex align-items-center" href="view/profile.php">
                            <img src="../public/img/uploads/<?php echo $_SESSION['image_player'] ?? 'default-avatar.png'; ?>"
                                class="rounded-circle me-2" width="35" height="35" alt="Avatar">


                            <span class="text-light"><?php echo htmlspecialchars($_SESSION['nickname_player']); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>