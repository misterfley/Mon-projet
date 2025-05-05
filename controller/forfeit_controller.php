<?php
session_start();
require_once __DIR__ . "/pdo.php";

$userId = $_SESSION['user_id'] ?? null;
$gameId = $_POST['game_id'] ?? null;

if ($userId && $gameId) {

    $stmt = $pdo->prepare(
        "SELECT player_white, player_black
         FROM game
         WHERE id_game = ?
           AND status = 'ongoing'"
    );
    $stmt->execute([$gameId]);
    if ($g = $stmt->fetch()) {
        $loser  = ($g['player_white'] == $userId) ? 'white' : 'black';
        $winner = ($loser === 'white') ? 'black' : 'white';

        $upd = $pdo->prepare(
            "UPDATE game
             SET status   = 'finished',
                 winner   = ?,
                 ended_at = NOW()
           WHERE id_game = ?"
        );
        $upd->execute([$winner, $gameId]);
    }
}
