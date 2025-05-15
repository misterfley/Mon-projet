<?php
session_start();
require_once __DIR__ . '/pdo.php';

// 1. Authentification et méthode POST
if (empty($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 403 Forbidden');
    exit('Accès non autorisé.');
}

// 2. Vérification CSRF
if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    http_response_code(403);
    exit('Requête invalide (CSRF).');
}
unset($_SESSION['csrf_token']);

$userId = $_SESSION['user_id'];
$gameId = filter_input(INPUT_POST, 'game_id', FILTER_VALIDATE_INT);

if (!$gameId) {
    header("Location: ../view/board.php?message=" . urlencode('ID de partie invalide.') . "&status=error");
    exit;
}

// 3. Vérifier que la partie est en cours et que l’utilisateur y participe
$stmt = $pdo->prepare("
    SELECT player_white, player_black
    FROM game
    WHERE id_game = ? AND status = 'ongoing'
");
$stmt->execute([$gameId]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game || ($game['player_white'] !== $userId && $game['player_black'] !== $userId)) {
    header("Location: ../view/board.php?mode=multi&game_id={$gameId}&message="
        . urlencode('Impossible de forfaiter cette partie.')
        . "&status=error");
    exit;
}

// 4. Calcul du gagnant et mise à jour
$loser  = ($game['player_white'] === $userId) ? 'white' : 'black';
$winner = ($loser === 'white') ? 'black' : 'white';

$upd = $pdo->prepare("
    UPDATE game
    SET status   = 'finished',
        winner   = ?,
        ended_at = NOW()
    WHERE id_game = ?
");
try {
    $upd->execute([$winner, $gameId]);
} catch (PDOException $e) {
    header("Location: ../view/board.php?mode=multi&game_id={$gameId}&message="
        . urlencode('Erreur lors du forfait.')
        . "&status=error");
    exit;
}

// 5. Redirection vers l’historique avec message clair
header("Location: ../view/history.php?message=" . urlencode("Vous avez abandonné la partie.") . "&status=warning");
exit;
