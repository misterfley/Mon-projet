<?php
session_start();
require_once("pdo.php");

header('Content-Type: application/json');

$gameId = $_GET['game_id'] ?? null;

if (!$gameId) {
    echo json_encode(['error' => 'ID de partie manquant.']);
    exit();
}

$stmt = $pdo->prepare("SELECT current_board, turn FROM game WHERE id_game = ?");
$stmt->execute([$gameId]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    echo json_encode(['error' => 'Partie introuvable.']);
    exit();
}

// Renvoie l'état sous forme JSON
echo json_encode([
    'board' => json_decode($game['current_board'], true),
    'turn' => $game['turn']
]);
