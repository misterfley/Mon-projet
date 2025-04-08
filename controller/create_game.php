<?php
session_start();
require_once("../controller/pdo.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Veuillez vous connecter.&status=warning");
    exit();
}

// Tirage  pour savoir si  blanc ou noir
$white = null;
$black = null;

if (rand(0, 1) === 0) {
    $white = $_SESSION['user_id'];
} else {
    $black = $_SESSION['user_id'];
}

// Plateau initial en JSON
$initialBoard = json_encode([
    "a1" => "wr",
    "b1" => "wn",
    "c1" => "wb",
    "d1" => "wq",
    "e1" => "wk",
    "f1" => "wb",
    "g1" => "wn",
    "h1" => "wr",
    "a2" => "wp",
    "b2" => "wp",
    "c2" => "wp",
    "d2" => "wp",
    "e2" => "wp",
    "f2" => "wp",
    "g2" => "wp",
    "h2" => "wp",
    "a7" => "bp",
    "b7" => "bp",
    "c7" => "bp",
    "d7" => "bp",
    "e7" => "bp",
    "f7" => "bp",
    "g7" => "bp",
    "h7" => "bp",
    "a8" => "br",
    "b8" => "bn",
    "c8" => "bb",
    "d8" => "bq",
    "e8" => "bk",
    "f8" => "bb",
    "g8" => "bn",
    "h8" => "br"
]);

// Création en base
$stmt = $pdo->prepare("INSERT INTO game (player_white, player_black, current_board, turn) VALUES (?, ?, ?, 'white')");
$stmt->execute([$white, $black, $initialBoard]);

// Récup ID de la partie
$gameId = $pdo->lastInsertId();


header("Location: ../view/board.php?mode=multi&game_id=$gameId");
exit();
