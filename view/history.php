<?php
session_start();
require_once __DIR__ . "/../controller/pdo.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Veuillez vous connecter.&status=warning");
    exit();
}

// On récupère toutes les parties terminées
$stmt = $pdo->prepare("
    SELECT
      g.id_game,
      pw.nickname_player AS white_nick,
      pb.nickname_player AS black_nick,
      g.winner,
      g.ended_at
    FROM game g
    LEFT JOIN player pw ON g.player_white = pw.id_player
    LEFT JOIN player pb ON g.player_black = pb.id_player
    WHERE g.status = 'finished'
      AND (g.player_white = :userId OR g.player_black = :userId)
    ORDER BY g.ended_at DESC
");
$stmt->execute(['userId' => $_SESSION['user_id']]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Historique des parties</title>
    <?php include("header.php"); ?>
</head>

<body>
    <?php include("nav.php"); ?>

    <main>
        <div class="container mt-5">
            <h1 class="mb-4 text-center">Historique des parties</h1>

            <?php if (empty($history)): ?>
                <p class="text-center text-muted">Aucune partie terminée pour l’instant.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped text-center ">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Partie</th>
                                <th>Blanc</th>
                                <th>Noir</th>
                                <th>Vainqueur</th>
                                <th>Terminé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $row): ?>
                                <tr>
                                    <td>#<?= $row['id_game'] ?></td>
                                    <td><?= htmlspecialchars($row['white_nick'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($row['black_nick'] ?? '—') ?></td>
                                    <td>
                                        <?php
                                        $isWhite = ($row['white_nick'] === $_SESSION['nickname_player']);
                                        $isBlack = ($row['black_nick'] === $_SESSION['nickname_player']);

                                        if ($row['winner'] === 'draw') {
                                            echo '⚖️ Match nul';
                                        } elseif ($row['winner'] === 'white') {
                                            echo htmlspecialchars($row['white_nick'] ?? '—');
                                            echo $isWhite ? ' ✅' : ' ❌';
                                        } elseif ($row['winner'] === 'black') {
                                            echo htmlspecialchars($row['black_nick'] ?? '—');
                                            echo $isBlack ? ' ✅' : ' ❌';
                                        } else {
                                            echo '—';
                                        }
                                        ?>
                                    </td>

                                    <td><?= date('d/m/Y H:i', strtotime($row['ended_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include("footer.php"); ?>
</body>

</html>