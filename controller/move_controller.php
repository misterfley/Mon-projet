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
$stmt = $pdo->prepare("SELECT current_board, turn, whiteKingMoved, blackKingMoved, whiteRookLeftMoved, whiteRookRightMoved, blackRookLeftMoved, blackRookRightMoved FROM game WHERE id_game = ?");
$stmt->execute([$gameId]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    echo json_encode(['error' => 'Partie introuvable.']);
    exit();
}

$board = json_decode($game['current_board'], true);
$turn = $game['turn'];

// Variables pour suivre les mouvements du roi et des tours
$whiteKingMoved = $game['whiteKingMoved'];
$blackKingMoved = $game['blackKingMoved'];
$whiteRookLeftMoved = $game['whiteRookLeftMoved'];
$whiteRookRightMoved = $game['whiteRookRightMoved'];
$blackRookLeftMoved = $game['blackRookLeftMoved'];
$blackRookRightMoved = $game['blackRookRightMoved'];

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

// Mise à jour  plateau
unset($board[$from]);
$board[$to] = $movingPiece;

// Si c'est un roque, met à jour les informations ET déplace aussi la tour
if (strpos($movingPiece, 'k') !== false && abs(ord($from[0]) - ord($to[0])) == 2) {
    if ($turn === 'white') {
        $whiteKingMoved = true;
        if ($from === 'e1' && $to === 'g1') {
            // Petit roque  
            $whiteRookRightMoved = true;
            unset($board['h1']);
            $board['f1'] = 'wr';
        } elseif ($from === 'e1' && $to === 'c1') {
            // Grand roque 
            unset($board['a1']);
            $board['d1'] = 'wr';
        }
    } else {
        $blackKingMoved = true;
        if ($from === 'e8' && $to === 'g8') {
            $blackRookRightMoved = true;
            unset($board['h8']);
            $board['f8'] = 'br';
        } elseif ($from === 'e8' && $to === 'c8') {
            $blackRookLeftMoved = true;
            unset($board['a8']);
            $board['d8'] = 'br';
        }
    }
}



// Changement de tour
$newTurn = ($turn === 'white') ? 'black' : 'white';

// Mise à jour en base de données
$update = $pdo->prepare("UPDATE game SET current_board = ?, turn = ?, whiteKingMoved = ?, whiteRookLeftMoved = ?, whiteRookRightMoved = ?, blackKingMoved = ?, blackRookLeftMoved = ?, blackRookRightMoved = ? WHERE id_game = ?");
$update->execute([
    json_encode($board),
    $newTurn,
    $whiteKingMoved,
    $whiteRookLeftMoved,
    $whiteRookRightMoved,
    $blackKingMoved,
    $blackRookLeftMoved,
    $blackRookRightMoved,
    $gameId
]);

echo json_encode([
    'success' => true,
    'new_turn' => $newTurn,
    'board' => $board
]);
