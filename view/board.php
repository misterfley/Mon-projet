<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php?message=Veuillez vous connecter.&status=warning");
  exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jeu d'échecs</title>
  <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../public/css/style.css">
  <link rel="stylesheet" href="../public/css/board.css">
  <script defer src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="board-page">
  <?php include('base.php'); ?>

  <main class="board-zone">
    <div class="player-indicator">White's turn</div>

    <div class="board">
      <?php
      $colors = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
      for ($row = 8; $row >= 1; $row--) {
        foreach ($colors as $i => $col) {
          $id = $col . $row;
          $isDark = ($i + $row) % 2 === 1;
          $class = $isDark ? "square dark" : "square light";
          echo "<div class=\"$class\" id=\"$id\">";
          if (!isset($_GET['mode']) || $_GET['mode'] !== 'multi') {
            // Pièces initiales en solo
            $pieces = [
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
              'h1' => '♖'
            ];
            if (array_key_exists($id, $pieces)) {
              $char = $pieces[$id];
              $whitePieces = ['♖', '♘', '♗', '♕', '♔', '♙'];
              $blackPieces = ['♜', '♞', '♝', '♛', '♚', '♟'];

              if (in_array($char, $whitePieces)) {
                $classPiece = 'white-piece';
              } elseif (in_array($char, $blackPieces)) {
                $classPiece = 'black-piece';
              } else {
                $classPiece = '';
              }

              echo "<span class=\"$classPiece\">$char</span>";
            }
          }
          echo "</div>";
        }
      }
      ?>
    </div>
    <div id="end-buttons" style="display: none; margin-top: 20px;" class="text-center">
      <button id="replay-btn" class="btn btn-success me-2">Rejouer</button>
      <a href="play.php" class="btn btn-secondary">Retour au menu</a>
    </div>

    <div id="game-status" style="display: none; color: red;"></div>
    <div id="game-message" style="display: none;"></div>

  </main>
  <script src="../public/js/chess_rules.js"></script>
  <?php if ($_GET['mode'] === 'multi'): ?>
    <script src="../public/js/multiplayer.js"></script>
  <?php else: ?>
    <script src="../public/js/main.js"></script>
  <?php endif; ?>
</body>

</html>