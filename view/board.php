<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php?message=Veuillez vous connecter.&status=warning");
  exit();
}

require_once __DIR__ . '/../controller/pdo.php';

$mode   = $_GET['mode'] ?? 'solo';
$gameId = isset($_GET['game_id']) && is_numeric($_GET['game_id'])
  ? (int)$_GET['game_id']
  : null;

// Valeurs par défaut
$whiteNick   = $blackNick   = 'Libre';
$whiteAvatar = $blackAvatar = 'static/default-avatar.webp';
$turn        = 'white';

if ($mode === 'multi') {
  if (!$gameId) {
    die("ID de partie manquant.");
  }
  // On récupère tout, y compris player_white/player_black
  $stmt = $pdo->prepare("
    SELECT g.current_board, g.turn,
           g.player_white, g.player_black,
           pw.nickname_player AS white_nick, pw.image_player AS image_white,
           pb.nickname_player AS black_nick, pb.image_player AS image_black
    FROM game g
    LEFT JOIN player pw ON g.player_white = pw.id_player
    LEFT JOIN player pb ON g.player_black = pb.id_player
    WHERE g.id_game = ?
  ");
  $stmt->execute([$gameId]);
  $game = $stmt->fetch(PDO::FETCH_ASSOC) ?: die("Partie introuvable.");

  // ————— NOUVEAU : bloque le 3ᵉ joueur —
  $isWhite  = ($_SESSION['user_id'] == $game['player_white']);
  $isBlack  = ($_SESSION['user_id'] == $game['player_black']);
  $hasWhite = !is_null($game['player_white']);
  $hasBlack = !is_null($game['player_black']);
  if ($hasWhite && $hasBlack && !$isWhite && !$isBlack) {
    header("Location: play.php?message=Cette partie est complète.&status=warning");
    exit();
  }


  $whiteNick   = $game['white_nick'] ?? 'Libre';
  $blackNick   = $game['black_nick'] ?? 'Libre';
  if ($game['image_white']) $whiteAvatar = 'uploads/' . $game['image_white'];
  if ($game['image_black']) $blackAvatar = 'uploads/' . $game['image_black'];
  $turn = $game['turn'];

  $_SESSION['game_id']      = $gameId;
  $_SESSION['player_color'] = $isWhite ? 'white' : 'black';
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>ECHECMANIA</title>
  <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../public/css/style.css">
  <link rel="stylesheet" href="../public/css/board.css">
  <script defer src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="board-page">
  <?php include 'nav.php'; ?>

  <main class="board-zone">

    <div class="theme-switcher mb-3">
      <label for="theme">Thème :</label>
      <select id="theme" class="form-select form-select-sm d-inline-block w-auto ms-2">
        <option value="">Par défaut</option>
        <option value="theme-sand">Plage</option>
        <option value="theme-forest">Forêt</option>
        <option value="theme-ocean">Ocean</option>
        <option value="theme-cerisier">Cerisier</option>
      </select>
    </div>

    <div class="board-wrapper">
      <?php if ($mode === 'multi'): ?>
        <div class="player-card white-card">
          <img src="../public/img/<?php echo htmlspecialchars($whiteAvatar) ?>"
            class="avatar-sm" alt="Blanc">
          <span class="player-name"><?php echo htmlspecialchars($whiteNick) ?></span>
        </div>
      <?php endif; ?>

      <div class="player-indicator">
        <?php echo ($turn === 'white') ? 'Blanc joue' : 'Noir joue'; ?>
      </div>
      <div class="board" id="board">
        <?php
        $files = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
        $initial = [
          'a8' => '♜',
          'b8' => '♞',
          'c8' => '♝',
          'd8' => '♛',
          'e8' => '♚',
          'f8' => '♝',
          'g8' => '♞',
          'h8' => '♜',
          'a7' => '♟',
          'b7' => '♟',
          'c7' => '♟',
          'd7' => '♟',
          'e7' => '♟',
          'f7' => '♟',
          'g7' => '♟',
          'h7' => '♟',
          'a2' => '♙',
          'b2' => '♙',
          'c2' => '♙',
          'd2' => '♙',
          'e2' => '♙',
          'f2' => '♙',
          'g2' => '♙',
          'h2' => '♙',
          'a1' => '♖',
          'b1' => '♘',
          'c1' => '♗',
          'd1' => '♕',
          'e1' => '♔',
          'f1' => '♗',
          'g1' => '♘',
          'h1' => '♖',
        ];
        for ($r = 8; $r >= 1; $r--) {
          foreach ($files as $i => $f) {
            $id = $f . $r;
            $dark = (($i + $r) % 2) ? 'dark' : 'light';
            echo "<div class=\"square $dark\" id=\"$id\">";
            if ($mode !== 'multi' && isset($initial[$id])) {
              $c = $initial[$id];
              $cls = in_array($c, ['♖', '♘', '♗', '♕', '♔', '♙'])
                ? 'white-piece' : 'black-piece';
              echo "<span class=\"$cls\">$c</span>";
            }
            echo "</div>";
          }
        }
        ?>
      </div>

      <?php if ($mode === 'multi'): ?>
        <div class="player-card black-card">
          <img src="../public/img/<?php echo htmlspecialchars($blackAvatar) ?>"
            class="avatar-sm" alt="Noir">
          <span class="player-name"><?php echo htmlspecialchars($blackNick) ?></span>
        </div>
      <?php endif; ?>
    </div>

    <div id="game-status" style="display:none;color:red;"></div>
    <div id="game-message" style="display:none;"></div>
  </main>
  <?php include("footer.php"); ?>
  <script>
    window.gameConfig = {
      gameId: <?php echo json_encode($gameId) ?>,
      playerColor: <?php echo json_encode($_SESSION['player_color'] ?? null) ?>
    };
  </script>
  <script src="../public/js/chess_rules.js" defer></script>
  <?php if ($mode === 'multi'): ?>
    <script src="../public/js/multiplayer.js" defer></script>
  <?php else: ?>
    <script src="../public/js/main.js" defer></script>
  <?php endif; ?>

  <script>
    const themeSelect = document.getElementById('theme');

    // 1) Au chargement : applique le thème sauvegardé si présent
    const saved = localStorage.getItem('echecmania-theme');
    if (saved) {
      document.body.classList.add(saved);
      themeSelect.value = saved;
    }

    // 2) À chaque changement de select : bascule la classe, sauvegarde
    themeSelect.addEventListener('change', e => {
      // retire toutes les classes de thème
      [...themeSelect.options]
      .map(o => o.value)
        .filter(v => v)
        .forEach(v => document.body.classList.remove(v));

      const theme = e.target.value;
      if (theme) {
        document.body.classList.add(theme);
        localStorage.setItem('echecmania-theme', theme);
      } else {
        localStorage.removeItem('echecmania-theme');
      }
    });
    // Forfait on close
    window.addEventListener("beforeunload", () => {
      navigator.sendBeacon?.("../controller/leave_game.php");
    });
  </script>
</body>

</html>