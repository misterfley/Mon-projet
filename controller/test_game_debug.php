<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("pdo.php");

$gameId = 1; // Remplace par un ID existant

$stmt = $pdo->prepare("SELECT current_board FROM game WHERE id_game = ?");
$stmt->execute([$gameId]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<pre>";
print_r(json_decode($data['current_board'], true));
echo "</pre>";
