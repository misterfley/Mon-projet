<?php
session_start();
require_once __DIR__ . "/pdo.php";

// Si on n’est pas dans une partie, on arrête tout
if (empty($_SESSION['game_id']) || empty($_SESSION['player_color'])) {
    http_response_code(400);
    exit("No game to leave");
}

$gameId      = (int) $_SESSION['game_id'];
$leavingSide = $_SESSION['player_color'];
$winner      = $leavingSide === 'white' ? 'black' : 'white';


$stmt = $pdo->prepare("
    UPDATE game
       SET status   = 'finished',
           winner   = ?,
           ended_at = NOW()
     WHERE id_game  = ?
       AND status  != 'finished'
");
$stmt->execute([$winner, $gameId]);


unset($_SESSION['game_id'], $_SESSION['player_color']);

// (On ne détruit PAS toute la session, pour que l’utilisateur reste connecté.)
