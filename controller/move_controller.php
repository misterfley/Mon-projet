<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Vous devez être connecté pour jouer.']);
    exit();
}

require_once("pdo.php");
header("Content-Type: application/json");

// Récupération des données POST
$gameId = $_POST['game_id'] ?? null;
$from   = $_POST['from'] ?? null;
$to     = $_POST['to'] ?? null;

if (!$gameId || !$from || !$to) {
    echo json_encode(['error' => 'Paramètres manquants.']);
    exit();
}

// Récupération de la partie
$stmt = $pdo->prepare("SELECT current_board, turn FROM game WHERE id_game = ?");
$stmt->execute([$gameId]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    echo json_encode(['error' => 'Partie introuvable.']);
    exit();
}

$board = json_decode($game['current_board'], true);
$turn = $game['turn'];

$movingPiece = $board[$from] ?? null;
$targetPiece = $board[$to] ?? null;

if (!$movingPiece) {
    echo json_encode(['error' => 'Aucune pièce à déplacer.']);
    exit();
}

// Vérifie si la pièce appartient bien au joueur du tour actuel
if (
    ($turn === 'white' && strpos($movingPiece, 'w') !== 0) ||
    ($turn === 'black' && strpos($movingPiece, 'b') !== 0)
) {
    echo json_encode(['error' => "Ce n'est pas à vous de jouer."]);
    exit();
}

// Mise à jour du plateau
unset($board[$from]);
$board[$to] = $movingPiece;

// Changement de tour
$newTurn = ($turn === 'white') ? 'black' : 'white';

// Mise à jour en base de données
$update = $pdo->prepare("UPDATE game SET current_board = ?, turn = ? WHERE id_game = ?");
$update->execute([json_encode($board), $newTurn, $gameId]);

echo json_encode(['success' => true, 'new_turn' => $newTurn]);
