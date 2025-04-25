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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer ma photo</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <script defer src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <?php include("nav.php"); ?>
    <?php include("message.php"); ?>
    <main>
        <div class="container my-5 text-center">
            <h2 class="mb-4">Changer ma photo de profil</h2>

            <img src="../public/img/uploads/<?php echo $_SESSION['image_player'] ?? 'default-avatar.png'; ?>" width="120" class="rounded-circle mb-3" alt="Photo actuelle">

            <form action="../controller/update_image_controller.php" method="POST" enctype="multipart/form-data" class="w-50 mx-auto">
                <div class="mb-3">
                    <input type="file" name="image_player" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </main>
    <?php include("footer.php"); ?>
</body>

</html>