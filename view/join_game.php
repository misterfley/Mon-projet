<?php
session_start();
require_once("../controller/pdo.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Veuillez vous connecter pour rejoindre une partie.&status=warning");
    exit();
}

if (!isset($_GET['game_id']) || !is_numeric($_GET['game_id'])) {
    header("Location: play.php?message=ID invalide.&status=warning");
    exit();
}

$gameId = (int)$_GET['game_id'];
$userId = $_SESSION['user_id'];

// Récup infos de la partie
$stmt = $pdo->prepare("SELECT player_white, player_black FROM game WHERE id_game = ?");
$stmt->execute([$gameId]);
$game = $stmt->fetch();

if (!$game) {
    header("Location: play.php?message=Partie introuvable.&status=danger");
    exit();
}

//  joueur  déjà dans la partie
if ($game['player_white'] == $userId || $game['player_black'] == $userId) {
    header("Location: board.php?mode=multi&game_id=$gameId");
    exit();
}

// Attribuer une place libre (white ou black)
if (empty($game['player_white'])) {
    $update = $pdo->prepare("UPDATE game SET player_white = ? WHERE id_game = ?");
    $update->execute([$userId, $gameId]);
    header("Location: board.php?mode=multi&game_id=$gameId");
    exit();
} elseif (empty($game['player_black'])) {
    $update = $pdo->prepare("UPDATE game SET player_black = ? WHERE id_game = ?");
    $update->execute([$userId, $gameId]);
    header("Location: board.php?mode=multi&game_id=$gameId");
    exit();
} else {
    header("Location: play.php?message=La partie est déjà complète.&status=warning");
    exit();
}
