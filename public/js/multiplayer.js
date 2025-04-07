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

function renderBoard(boardData) {
  // Efface toutes les pièces
  document.querySelectorAll(".square").forEach((square) => {
    square.innerHTML = ""; // on efface tout dans les cases
  });

  // Ajoute les pièces depuis le JSON
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

// Rafraîchit toutes les 2 secondes
setInterval(getGameState, 2000);

// Appel initial
getGameState();
let selectedSquare = null;

document.querySelectorAll(".square").forEach((square) => {
  square.addEventListener("click", () => {
    const piece = square.querySelector("span");

    if (selectedSquare && selectedSquare !== square) {
      const from = selectedSquare.id;
      const to = square.id;

      // Envoie le coup au serveur
      fetch("../controller/move_controller.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `game_id=${gameId}&from=${from}&to=${to}`,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            getGameState(); // Rafraîchir l’échiquier
            selectedSquare.classList.remove("selected");
            selectedSquare = null;
          } else {
            alert(data.error);
            selectedSquare.classList.remove("selected");
            selectedSquare = null;
          }
        });
    } else if (piece) {
      // Sélection initiale
      if (selectedSquare) selectedSquare.classList.remove("selected");
      selectedSquare = square;
      square.classList.add("selected");
    }
  });
});
