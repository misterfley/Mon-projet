<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jeu d'échecs</title>

  <!-- Bootstrap + icônes -->
  <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.min.css">

  <!-- Styles CSS -->
  <link rel="stylesheet" href="../public/css/style.css"> <!-- styles globaux (navbar, couleurs...) -->
  <link rel="stylesheet" href="../public/css/board.css"> <!-- styles spécifiques au board -->

  <!-- JS -->
  <script defer src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</head>


<body class="board-page">

  <?php include('base.php'); ?> <!-- Affiche la navbar -->

  <main class="board-zone">
    <div class="player-indicator">White's turn</div>
    <div class="board">
      <!-- Rangée 1 pour les pièces noires -->
      <div class="square light" id="a8"><span class="black-piece">♜</span></div>
      <div class="square dark" id="b8"><span class="black-piece">♞</span></div>
      <div class="square light" id="c8"><span class="black-piece">♝</span></div>
      <div class="square dark" id="d8"><span class="black-piece">♛</span></div>
      <div class="square light" id="e8"><span class="black-piece">♚</span></div>
      <div class="square dark" id="f8"><span class="black-piece">♝</span></div>
      <div class="square light" id="g8"><span class="black-piece">♞</span></div>
      <div class="square dark" id="h8"><span class="black-piece">♜</span></div>

      <!-- Rangée 2 pour les pions noirs -->
      <div class="square dark" id="a7"><span class="black-piece">♟</span></div>
      <div class="square light" id="b7"><span class="black-piece">♟</span></div>
      <div class="square dark" id="c7"><span class="black-piece">♟</span></div>
      <div class="square light" id="d7"><span class="black-piece">♟</span></div>
      <div class="square dark" id="e7"><span class="black-piece">♟</span></div>
      <div class="square light" id="f7"><span class="black-piece">♟</span></div>
      <div class="square dark" id="g7"><span class="black-piece">♟</span></div>
      <div class="square light" id="h7"><span class="black-piece">♟</span></div>

      <!-- Rangée 3 -->
      <div class="square light" id="a6"></div>
      <div class="square dark" id="b6"></div>
      <div class="square light" id="c6"></div>
      <div class="square dark" id="d6"></div>
      <div class="square light" id="e6"></div>
      <div class="square dark" id="f6"></div>
      <div class="square light" id="g6"></div>
      <div class="square dark" id="h6"></div>

      <!-- Rangée 4 -->
      <div class="square dark" id="a5"></div>
      <div class="square light" id="b5"></div>
      <div class="square dark" id="c5"></div>
      <div class="square light" id="d5"></div>
      <div class="square dark" id="e5"></div>
      <div class="square light" id="f5"></div>
      <div class="square dark" id="g5"></div>
      <div class="square light" id="h5"></div>

      <!-- Rangée 5 -->
      <div class="square light" id="a4"></div>
      <div class="square dark" id="b4"></div>
      <div class="square light" id="c4"></div>
      <div class="square dark" id="d4"></div>
      <div class="square light" id="e4"></div>
      <div class="square dark" id="f4"></div>
      <div class="square light" id="g4"></div>
      <div class="square dark" id="h4"></div>

      <!-- Rangée 6 -->
      <div class="square dark" id="a3"></div>
      <div class="square light" id="b3"></div>
      <div class="square dark" id="c3"></div>
      <div class="square light" id="d3"></div>
      <div class="square dark" id="e3"></div>
      <div class="square light" id="f3"></div>
      <div class="square dark" id="g3"></div>
      <div class="square light" id="h3"></div>

      <!-- Rangée 7 pour les pions blancs -->
      <div class="square light" id="a2"><span class="white-piece">♙</span></div>
      <div class="square dark" id="b2"><span class="white-piece">♙</span></div>
      <div class="square light" id="c2"><span class="white-piece">♙</span></div>
      <div class="square dark" id="d2"><span class="white-piece">♙</span></div>
      <div class="square light" id="e2"><span class="white-piece">♙</span></div>
      <div class="square dark" id="f2"><span class="white-piece">♙</span></div>
      <div class="square light" id="g2"><span class="white-piece">♙</span></div>
      <div class="square dark" id="h2"><span class="white-piece">♙</span></div>

      <!-- Rangée 8 pour les pièces blanches -->
      <div class="square dark" id="a1"><span class="white-piece">♖</span></div>
      <div class="square light" id="b1"><span class="white-piece">♘</span></div>
      <div class="square dark" id="c1"><span class="white-piece">♗</span></div>
      <div class="square light" id="d1"><span class="white-piece">♕</span></div>
      <div class="square dark" id="e1"><span class="white-piece">♔</span></div>
      <div class="square light" id="f1"><span class="white-piece">♗</span></div>
      <div class="square dark" id="g1"><span class="white-piece">♘</span></div>
      <div class="square light" id="h1"><span class="white-piece">♖</span></div>
    </div>

    <!-- Les autres éléments de la page -->
    <div id="game-status" style="display: none; color: red;"></div>
    <div id="game-message" style="display: none;"></div>
  </main>
  <script src="../public/js/main.js"></script>
</body>

</html>