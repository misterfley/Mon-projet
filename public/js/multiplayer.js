const urlParams = new URLSearchParams(window.location.search);
const gameId = urlParams.get("game_id");

if (!gameId) {
  alert("Aucune partie trouvée.");
  throw new Error("game_id manquant");
}

const boardElement = document.querySelector(".board");

const pieceUnicodeMap = {
  // white
  wr: "♖",
  wn: "♘",
  wb: "♗",
  wq: "♕",
  wk: "♔",
  wp: "♙",
  // black
  br: "♜",
  bn: "♞",
  bb: "♝",
  bq: "♛",
  bk: "♚",
  bp: "♟",
};

let playerColor = null;
let currentTurn = "white";
let selectedSquare = null;
function rotateBoardIfNeeded() {
  if (playerColor === "black") {
    boardElement.style.transform = "rotate(180deg)";
    document.querySelectorAll(".square").forEach((square) => {
      square.style.transform = "rotate(180deg)";
    });
  }
}

function renderBoard(boardData) {
  document.querySelectorAll(".square").forEach((square) => {
    square.innerHTML = "";
  });

  for (const squareId in boardData) {
    const pieceCode = boardData[squareId];
    const square = document.getElementById(squareId);
    if (square && pieceUnicodeMap[pieceCode]) {
      const span = document.createElement("span");
      span.classList.add(
        pieceCode.startsWith("w") ? "white-piece" : "black-piece"
      );
      span.textContent = pieceUnicodeMap[pieceCode];
      square.appendChild(span);
    }
  }
}

function getGameState() {
  fetch(`../controller/get_game_state.php?game_id=${gameId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        console.error("Erreur :", data.error);
        return;
      }

      renderBoard(data.board);
      updateTurnIndicator(data.turn);
      currentTurn = data.turn;

      // Une seule fois : on définit la couleur du joueur et on tourne l'échiquier si besoin
      if (!playerColor && data.player_color) {
        playerColor = data.player_color;
        rotateBoardIfNeeded();
      }
    })
    .catch((err) => {
      console.error("Erreur AJAX :", err);
    });
}

function updateTurnIndicator(turn) {
  const indicator = document.querySelector(".player-indicator");
  if (indicator) {
    indicator.textContent = `${turn === "white" ? "Blanc" : "Noir"} joue`;
  }
}

document.querySelectorAll(".square").forEach((square) => {
  square.addEventListener("click", () => {
    const piece = square.querySelector("span");

    // Si un pion est déjà sélectionné
    if (selectedSquare && selectedSquare !== square) {
      const from = selectedSquare.id;
      const to = square.id;

      fetch("../controller/move_controller.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `game_id=${gameId}&from=${from}&to=${to}`,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            selectedSquare.classList.remove("selected");
            selectedSquare = null;
            getGameState();
          } else {
            alert(data.error);
            selectedSquare.classList.remove("selected");
            selectedSquare = null;
          }
        });

      return;
    }

    // Si aucune pièce n'est sélectionnée, on en sélectionne une
    if (piece) {
      const isWhite = piece.classList.contains("white-piece");
      const pieceColor = isWhite ? "white" : "black";

      if (pieceColor !== playerColor) return; // pas ta couleur
      if (playerColor !== currentTurn) return; // pas ton tour

      if (selectedSquare) selectedSquare.classList.remove("selected");
      selectedSquare = square;
      selectedSquare.classList.add("selected");
    }
  });
});

// Rafraîchit automatiquement toutes les 2 secondes
setInterval(getGameState, 2000);
getGameState(); // initial
