<?php
header('Content-Type: application/json; charset=UTF-8');
session_start();
require_once __DIR__ . '/pdo.php';

// 1) Validation de game_id
if (empty($_GET['game_id']) || !is_numeric($_GET['game_id'])) {
    echo json_encode(['error' => 'game_id invalide']);
    exit;
}
$gameId = (int)$_GET['game_id'];

// 2) Chargement des données de la partie
$stmt = $pdo->prepare("
    SELECT current_board, turn, player_white, player_black
      FROM game
     WHERE id_game = ?
");
$stmt->execute([$gameId]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    echo json_encode(['error' => 'Partie introuvable']);
    exit;
}

// 3) Préparation des variables
$board        = json_decode($game['current_board'], true);
$turn         = $game['turn'];
$player_color = $_SESSION['player_color'] ?? null;

// 4) Préparation de la requête joueur
$stmtPlayer = $pdo->prepare("
    SELECT nickname_player, image_player
      FROM player
     WHERE id_player = ?
");



// 5) Récupération blanc
if ($game['player_white']) {
    $stmtPlayer->execute([$game['player_white']]);
    $pw = $stmtPlayer->fetch(PDO::FETCH_ASSOC);
    if ($pw) {
        $white_nick = $pw['nickname_player'];
        // si y a bien un fichier, on le préfixe ; sinon on utilise le default
        $white_avatar = $pw['image_player']
            ? 'uploads/' . $pw['image_player']
            : 'static/default-avatar.webp';
    }
} else {
    // pas encore de joueur blanc
    $white_nick   = null;
    $white_avatar = 'static/default-avatar.webp';
}

// 6) Récupération noir
if ($game['player_black']) {
    $stmtPlayer->execute([$game['player_black']]);
    $pb = $stmtPlayer->fetch(PDO::FETCH_ASSOC);
    if ($pb) {
        $black_nick = $pb['nickname_player'];
        $black_avatar = $pb['image_player']
            ? 'uploads/' . $pb['image_player']
            : 'static/default-avatar.webp';
    }
} else {
    $black_nick   = null;
    $black_avatar = 'static/default-avatar.webp';
}


// 7) Envoi du JSON
echo json_encode([
    'board'         => $board,
    'turn'          => $turn,
    'player_color'  => $player_color,
    'white_nick'    => $white_nick,
    'white_avatar'  => $white_avatar,
    'black_nick'    => $black_nick,
    'black_avatar'  => $black_avatar,
]);
