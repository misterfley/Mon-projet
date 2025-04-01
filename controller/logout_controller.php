<?php

session_start();

// Supprimer toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Rediriger vers la page d'accueil après la déconnexion
header('Location: ../view/homepage.php');
exit();
