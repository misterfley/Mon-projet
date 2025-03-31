<?php

require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

// Charger le fichier .env à la racine du projet
$dotenv = Dotenv::createImmutable("../");
$dotenv->load();

// Récupération des variables d'environnement
$host = $_ENV['DB_HOST'];
$name = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$psw = $_ENV['DB_PSW'];

// Connexion à la base de données avec gestion des erreurs
try {
    $pdo = new PDO("mysql:host=$host;dbname=$name", $user, $psw);
    // Configurer PDO pour lancer des exceptions en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Par défaut, on souhaite obtenir des résultats sous forme de tableau associatif
} catch (PDOException $e) {
    // En cas d'erreur, on enregistre l'erreur dans un fichier log et on redirige l'utilisateur
    error_log($e->getMessage());
    header('Location: error_page.php'); // Remplace par la page d'erreur de ton choix
    exit();
}
