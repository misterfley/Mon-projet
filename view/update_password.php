<?php session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Veuillez vous connecter pour accéder à cette page.&status=warning");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Modifier mon mot de passe</title>
    <?php include("header.php"); ?>
</head>

<body>
    <?php include("nav.php"); ?>
    <?php include("message.php"); ?>
    <main>
        <div class="container my-5">
            <h2 class="text-center mb-4">Modifier mon mot de passe</h2>

            <form action="../controller/update_password_controller.php" method="POST" class="w-50 mx-auto">
                <label for="current_password" class="form-label">Mot de passe actuel</label>
                <input type="password" name="current_password" class="form-control" required>

                <label for="new_password" class="form-label mt-3">Nouveau mot de passe</label>
                <input type="password" name="new_password" class="form-control" required>

                <label for="confirm_password" class="form-label mt-3">Confirmer le nouveau mot de passe</label>
                <input type="password" name="confirm_password" class="form-control" required>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>

        </div>
    </main>
    <?php include("footer.php"); ?>
</body>

</html>