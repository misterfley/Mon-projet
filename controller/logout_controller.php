<?php
session_start();
require_once __DIR__ . "/pdo.php";

$userId = $_SESSION['user_id'] ?? null;

if ($userId) {
    // 1) Cherche une partie en cours où il est blanc ou noir
    $sql = "SELECT id_game, player_white, player_black
            FROM game
            WHERE status = 'ongoing'
              AND (player_white = ? OR player_black = ?)
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $userId]);
    if ($game = $stmt->fetch()) {
        // déterminer le gagnant
        $loser  = ($game['player_white'] == $userId) ? 'white' : 'black';
        $winner = ($loser === 'white') ? 'black' : 'white';

        // 2) Met à jour la partie
        $upd = $pdo->prepare(
            "UPDATE game
             SET status   = 'finished',
                 winner   = ?,
                 ended_at = NOW()
           WHERE id_game = ?"
        );
        $upd->execute([$winner, $game['id_game']]);
    }
}

// 3) Puis on détruit la session comme avant
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $p = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $p['path'],
        $p['domain'],
        $p['secure'],
        $p['httponly']
    );
}
session_destroy();

header("Location: ../view/homepage.php?message=Déconnexion réussie&status=success");
exit();
