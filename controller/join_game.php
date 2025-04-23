<?php
session_start();
require_once("../controller/pdo.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Veuillez vous connecter pour rejoindre une partie.&status=warning");
    exit();
}

if (empty($_GET['game_id']) || !is_numeric($_GET['game_id'])) {
    header("Location: play.php?message=ID invalide.&status=warning");
    exit();
}

$gameId = (int)$_GET['game_id'];
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT player_white, player_black FROM game WHERE id_game = ?");
$stmt->execute([$gameId]);  // ← ajout du $
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    header("Location: play.php?message=Partie introuvable.&status=danger");
    exit();
}


if ($game['player_white'] == $userId || $game['player_black'] == $userId) {
    $side = ($game['player_white'] == $userId) ? 'white' : 'black';
    $_SESSION['game_id']      = $gameId;
    $_SESSION['player_color'] = $side;
    header("Location: ../view/board.php?mode=multi&game_id=$gameId");
    exit();
}


if (empty($game['player_white'])) {
    $side = 'white';
    $update = $pdo->prepare("UPDATE game SET player_white = ? WHERE id_game = ?");
    $update->execute([$userId, $gameId]);
} elseif (empty($game['player_black'])) {
    $side = 'black';
    $update = $pdo->prepare("UPDATE game SET player_black = ? WHERE id_game = ?");
    $update->execute([$userId, $gameId]);
} else {

    header("Location: play.php?message=La partie est déjà complète.&status=warning");
    exit();
}


$pdo->prepare("UPDATE game SET status = 'ongoing' WHERE id_game = ?")
    ->execute([$gameId]);


$_SESSION['game_id']      = $gameId;
$_SESSION['player_color'] = $side;

header("Location: ../view/board.php?mode=multi&game_id=$gameId");
exit();
