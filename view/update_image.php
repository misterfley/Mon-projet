<?php session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Veuillez vous connecter.&status=warning");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Changer ma photo</title>
    <?php include("header.php"); ?>
</head>

<body>
    <?php include("nav.php"); ?>
    <?php include("message.php"); ?>
    <main>
        <div class="container my-5 text-center">
            <h2 class="mb-4">Changer ma photo de profil</h2>

            <img src="../public/img/uploads/<?php echo $_SESSION['image_player'] ?? 'default-avatar.png'; ?>" width="180" class=" mb-3" alt="Photo actuelle">

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