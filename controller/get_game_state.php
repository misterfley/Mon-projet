<?php
session_start();
require_once("pdo.php");

// Vérifie que l'ID de la partie est bien fourni dans l'URL
if (!isset($_GET['game_id'])) {
    echo json_encode(["error" => "game_id manquant"]);
    exit;
}

$gameId = $_GET['game_id'];

// On récupère les infos de la partie dans la base de données
$stmt = $pdo->prepare("SELECT * FROM game WHERE id_game = ?");
$stmt->execute([$gameId]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

// Si la partie n'existe pas
if (!$game) {
    echo json_encode(["error" => "Partie introuvable"]);
    exit;
}

// On récupère l'utilisateur connecté via la session
$userId = $_SESSION['user_id'] ?? null;

// On détermine la couleur du joueur actuel
$playerColor = null;
if ($userId) {
    if ($userId == $game['player_white']) {
        $playerColor = "white";
    } elseif ($userId == $game['player_black']) {
        $playerColor = "black";
    }
}

// Réponse JSON envoyée au frontend (multiplayer.js)
echo json_encode([
    "board" => json_decode($game['current_board'], true), // l'état de l'échiquier
    "turn" => $game['turn'],                              // couleur du joueur dont c'est le tour
    "player_color" => $playerColor                        // couleur du joueur connecté
]);
