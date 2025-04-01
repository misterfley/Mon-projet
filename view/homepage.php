<?php
include "base.php"; // Inclure base.php où session_start() est déjà appelé
?>

<h1 class="text-center">
    <?php
    if (isset($_SESSION['name'])) {
        // Si la session est active et 'name' est défini, l'utilisateur est connecté
        echo "Bienvenue, " . $_SESSION['name'] . "!";
    } else {
        // Sinon, afficher un message demandant à se connecter
        echo "Veuillez vous connecter.";
    }
    ?>
</h1>

<!-- Le reste de ton contenu de page -->

<h1 class="text-center mt-5">Affrontez vos amis</h1>
<div id="thanos" class="text-center">
    <div id="container2">
        <img src="popcorn2.png" id="original-image" alt="seau de popcorn" class="img-fluid">
    </div>
    <h1 class="text-light">"Plongez dans l'univers des échecs, tout en vous amusant."</h1>
</div>