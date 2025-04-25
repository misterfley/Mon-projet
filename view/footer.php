<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<footer class="bg-dark text-light py-4 mt-4">
    <div class="container text-center">
        <div class="mb-2">
            <ul class="list-inline mb-0">
                <li class="list-inline-item">
                    <a href="homepage.php" class="text-light text-decoration-none">Accueil</a>
                </li>
                <li class="list-inline-item">|</li>
                <li class="list-inline-item">
                    <a href="legal_mentions.php" class="text-light text-decoration-none">Mentions légales</a>
                </li>
                <li class="list-inline-item">|</li>
                <li class="list-inline-item">
                    <a href="rgpd.php" class="text-light text-decoration-none">RGPD</a>
                </li>
                <li class="list-inline-item">|</li>
                <li class="list-inline-item">
                    <a href="cgu.php" class="text-light text-decoration-none">CGU</a>
                </li>
            </ul>
        </div>
        <p class="mb-1">&copy; <?= date('Y') ?> Roque’N’Roll. Tous droits réservés.</p>
        <small>Développé avec ❤️ par MisterFley</small>
    </div>
</footer>