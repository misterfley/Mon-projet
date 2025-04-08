<?php
session_start();
require_once("../controller/pdo.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Veuillez vous connecter.&status=warning");
    exit();
}

// Récupérer les parties ouvertes
$stmt = $pdo->query("
    SELECT g.id_game, g.player_white, g.player_black,
           pw.nickname_player AS white_nick, pb.nickname_player AS black_nick
    FROM game g
    LEFT JOIN player pw ON g.player_white = pw.id_player
    LEFT JOIN player pb ON g.player_black = pb.id_player
    WHERE g.player_white IS NULL OR g.player_black IS NULL
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
</head>

<body>
    <?php include("base.php"); ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Parties disponibles</h1>

        <?php if (count($games) === 0): ?>
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
                            <td>#<?php echo $game['id_game']; ?></td>
                            <td><?php echo $game['white_nick'] ?? "<em>Libre</em>"; ?></td>
                            <td><?php echo $game['black_nick'] ?? "<em>Libre</em>"; ?></td>
                            <td>
                                <a href="join_game.php?game_id=<?php echo $game['id_game']; ?>" class="btn btn-primary btn-sm">
                                    Rejoindre
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>