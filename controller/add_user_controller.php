<?php
include "pdo.php";
if (
    !empty($_POST['firstname_player']) &&
    !empty($_POST['lastname_player']) &&
    !empty($_POST['nickname_player']) &&
    !empty($_POST['email_player']) &&
    !empty($_POST['password_player'])
) {
    if (!filter_var($_POST['email_player'], FILTER_VALIDATE_EMAIL)) {
        header("Location: ../view/add_user_form.php?message=Email invalide&status=error");
        exit();
    }
    $psw = password_hash($_POST["password_player"], PASSWORD_ARGON2I);
    if (isset($_FILES['image_player']) && $_FILES['image_player']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES["image_player"]["name"];
        $fileSize = $_FILES["image_player"]["size"];
        $tmpName = $_FILES["image_player"]["tmp_name"];
        $tabExtension = explode('.', $fileName);
        $extension = strtolower(end($tabExtension));
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        if (in_array($extension, $validExtensions)) {
            if ($fileSize < 2097152) { // Taille inférieure à 2 Mo
                $newName = sha1(uniqid(mt_rand(), true)) . $_POST['firstname_player'] . '_' . $_POST['lastname_player'] . '.' . $extension;
                try {
                    $success = move_uploaded_file($tmpName, "../public/img/uploads/" . $newName);
                } catch (Exception $e) {
                    $message_error = $e->getMessage();
                    header("Location: ../view/add_user_form.php?message=$message_error&status=error");
                    exit();
                }
                if ($success) {
                    $sql = "INSERT INTO player (firstname_player, lastname_player, nickname_player, email_player, password_player, image_player) 
                            VALUES (?,?,?,?,?,?)";
                    $stmt = $pdo->prepare($sql);
                    $verif = $stmt->execute([
                        $_POST["firstname_player"],
                        $_POST["lastname_player"],
                        $_POST["nickname_player"],
                        $_POST["email_player"],
                        $psw,
                        $newName
                    ]);
                    if ($verif) {
                        header("Location: ../view/login.php?message=Inscription réussie&status=success");
                        exit();
                    } else {
                        // En cas d'erreur SQL
                        $errorInfo = $stmt->errorInfo();
                        header("Location: ../view/add_user_form.php?message=Erreur SQL: " . $errorInfo[2] . "&status=error");
                        exit();
                    }
                } else {
                    header("Location: ../view/add_user_form.php?message=Problème pendant l'upload du fichier&status=error");
                    exit();
                }
            } else {
                header("Location: ../view/add_user_form.php?message=Le fichier doit avoir une taille inférieure à 2 Mo&status=error");
                exit();
            }
        } else {
            header("Location: ../view/add_user_form.php?message=Format de fichier invalide&status=error");
            exit();
        }
    } else {
        header("Location: ../view/add_user_form.php?message=Veuillez télécharger une image valide&status=error");
        exit();
    }
} else {
    header("Location: ../view/add_user_form.php?message=Veuillez remplir tous les champs du formulaire&status=error");
    exit();
}
