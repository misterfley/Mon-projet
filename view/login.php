<?php
include "base.php"; // Inclusion du fichier de base (par exemple, pour la navbar)
include "message.php"; // Inclusion du fichier pour afficher les messages (erreur ou succès)
?>

<h1 class="text-center">Connexion</h1>

<form class="w-25 mx-auto" action="controller/login_controller.php" method="POST">
    <label class="form-label" for="email_player">Email de connexion</label>
    <input type="email" class="form-control" name="email_player" required>

    <label class="form-label" for="password_player">Mot de passe</label>
    <input class="form-control" type="password" name="password_player" required>

    <div class="text-center my-4">
        <input class="btn btn-primary" type="submit" value="Connexion">
    </div>
</form>

</body>

</html>