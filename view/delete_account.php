<?php session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Veuillez vous connecter.&status=warning");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Supprimer mon compte</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <?php include("base.php"); ?>
    <?php include("message.php"); ?>

    <div class="container my-5">
        <h2 class="text-center text-danger mb-4">⚠️ Supprimer mon compte</h2>
        <p class="text-center">Cette action est <strong>irréversible</strong>. Votre compte, vos infos et votre image seront supprimés.</p>

        <form action="../controller/delete_account_controller.php" method="POST" class="w-50 mx-auto text-center mt-4">
            <label for="confirm" class="form-label">Tapez <strong>SUPPRIMER</strong> pour confirmer :</label>
            <input type="text" name="confirm" class="form-control text-center" required>

            <button type="submit" class="btn btn-danger mt-4">Oui, supprimer mon compte</button>
        </form>
    </div>
</body>

</html>