<?php
session_start();
require_once __DIR__ . '/../controller/pdo.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Veuillez vous connecter.&status=warning");
    exit();
}

$mode   = $_GET['mode']   ?? 'solo';
$gameId = $_GET['game_id'] ?? null;

if ($mode === 'multi') {
    if (!$gameId || !is_numeric($gameId)) {
        header("Location: play.php?message=ID de partie invalide.&status=warning");
        exit();
    }
    $gameId = (int)$gameId;

    // Récupère qui est inscrit dans la partie
    $stmt = $pdo->prepare("
      SELECT player_white, player_black
      FROM game
      WHERE id_game = ?
    ");
    $stmt->execute([$gameId]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

    if (!$game) {
        header("Location: play.php?message=Partie introuvable.&status=danger");
        exit();
    }

    $isWhite  = $game['player_white']  === $_SESSION['user_id'];
    $isBlack  = $game['player_black']  === $_SESSION['user_id'];
    $hasWhite = !is_null($game['player_white']);
    $hasBlack = !is_null($game['player_black']);

    // Si la partie a déjà deux joueurs  on bloque
    if ($hasWhite && $hasBlack && !$isWhite && !$isBlack) {
        header("Location: play.php?message=Cette partie est complète.&status=warning");
        exit();
    }
}

// Récupérer les parties ouvertes (status = 'open')
// games_list.php

$stmt = $pdo->query("
  SELECT
    g.id_game,
    g.player_white,
    g.player_black,
    pw.nickname_player AS white_nick,
    pb.nickname_player AS black_nick
  FROM game g
  LEFT JOIN player pw ON g.player_white = pw.id_player
  LEFT JOIN player pb ON g.player_black = pb.id_player
  WHERE g.status = 'open'
    AND (
      g.player_white IS NULL
      OR g.player_black IS NULL
    )
  ORDER BY g.id_game DESC
");
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Parties disponibles</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <script defer src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <?php include("nav.php"); ?>
    <main>
        <div class="container mt-5">
            <h1 class="text-center mb-4">Parties disponibles</h1>

            <?php if (empty($games)): ?>
                <p class="text-center text-muted">Aucune partie disponible pour le moment.</p>
            <?php else: ?>
                <table class="table table-striped text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Joueur Blanc</th>
                            <th>Joueur Noir</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($games as $game): ?>
                            <tr>
                                <td>#<?= $game['id_game'] ?></td>
                                <td><?= $game['white_nick'] ? htmlspecialchars($game['white_nick']) : '<em>Libre</em>' ?></td>
                                <td><?= $game['black_nick'] ? htmlspecialchars($game['black_nick']) : '<em>Libre</em>' ?></td>

                                <td>
                                    <?php if (is_null($game['player_white']) || is_null($game['player_black'])): ?>
                                        <a href="../controller/join_game.php?game_id=<?= $game['id_game'] ?>"
                                            class="btn btn-primary btn-sm">
                                            Rejoindre
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>Complet</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
    <?php include("footer.php"); ?>
</body>

</html>