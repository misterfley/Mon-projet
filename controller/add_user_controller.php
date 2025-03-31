<?php

include "pdo.php"; // Connexion à la base de données

// Afficher les fichiers envoyés pour le débogage (peut être supprimé après les tests)
var_dump($_FILES);

if (
    !empty($_POST['firstname_player']) &&
    !empty($_POST['lastname_player']) &&
    !empty($_POST['nickname_player']) &&
    !empty($_POST['email_player']) && // Utilisation de 'email_player' ici pour correspondre au formulaire
    !empty($_POST['password_player'])
) {

    // Hashage du mot de passe avec Argon2I
    $psw = password_hash($_POST["password_player"], PASSWORD_ARGON2I);

    if (isset($_FILES['image_player']) && $_FILES['image_player']['error'] === UPLOAD_ERR_OK) {

        // Récupération des informations sur l'image téléchargée
        $fileName = $_FILES["image_player"]["name"];
        $fileSize = $_FILES["image_player"]["size"];
        $tmpName = $_FILES["image_player"]["tmp_name"];

        // Extraction de l'extension du fichier
        $tabExtension = explode('.', $fileName);
        $extension = strtolower(end($tabExtension)); // On récupère l'extension en minuscule
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']; // Extensions autorisées

        if (in_array($extension, $validExtensions)) {
            if ($fileSize < 2097152) { // Taille inférieure à 2 Mo

                // Générer un nom unique pour l'image
                $newName = sha1(uniqid(mt_rand(), true)) . $_POST['firstname_player'] . '_' . $_POST['lastname_player'] . '.' . $extension;

                // Déplacer l'image téléchargée vers le dossier des uploads
                try {
                    $success = move_uploaded_file($tmpName, "../public/img/uploads/" . $newName);
                } catch (Exception $e) {
                    // En cas d'erreur, rediriger avec le message d'erreur
                    $message_error = $e->getMessage();
                    header("Location: ../view/add_user_form.php?message=$message_error&status=error");
                    exit();
                }

                // Si le fichier a été déplacé avec succès, insérer les données dans la base
                if ($success) {

                    // Requête SQL pour insérer les données dans la table `player`
                    $sql = "INSERT INTO player (firstname_player, lastname_player, nickname_player, email_player, password_player, image_player) 
                            VALUES (?,?,?,?,?,?)";
                    $stmt = $pdo->prepare($sql);
                    $verif = $stmt->execute([
                        $_POST["firstname_player"],
                        $_POST["lastname_player"],
                        $_POST["nickname_player"],
                        $_POST["email_player"], // Utilisation de 'email_player' ici pour correspondre au formulaire
                        $psw,
                        $newName // Nom de l'image téléchargée
                    ]);

                    // Vérification de l'insertion dans la base de données
                    if ($verif) {
                        header("Location: ../view/add_user_form.php?message=Inscription réussie&status=success");
                        exit();
                    } else {
                        // En cas d'erreur SQL
                        header("Location: ../view/add_user_form.php?message=Erreur serveur, veuillez réessayer plus tard.&status=error");
                        exit();
                    }
                } else {
                    // Problème lors du déplacement du fichier
                    header("Location: ../view/add_user_form.php?message=Problème pendant l'upload du fichier&status=error");
                    exit();
                }
            } else {
                // Taille de fichier invalide (plus de 2 Mo)
                header("Location: ../view/add_user_form.php?message=Le fichier doit avoir une taille inférieure à 2 Mo&status=error");
                exit();
            }
        } else {
            // Extension de fichier invalide
            header("Location: ../view/add_user_form.php?message=Format de fichier invalide&status=error");
            exit();
        }
    } else {
        // Si aucun fichier n'a été téléchargé
        header("Location: ../view/add_user_form.php?message=Veuillez télécharger une image valide&status=error");
        exit();
    }
} else {
    // Si le formulaire n'est pas correctement rempli
    header("Location: ../view/add_user_form.php?message=Veuillez remplir tous les champs du formulaire&status=error");
    exit();
}
