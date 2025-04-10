let lastCheckStatus = null;

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
      console.log("[BOARD reçu]", data.board);
      if (data.error) {
        console.error("Erreur :", data.error);
        return;
      }

      renderBoard(data.board);
      updateTurnIndicator(data.turn);
      currentTurn = data.turn;

      if (!playerColor && data.player_color) {
        playerColor = data.player_color;
        rotateBoardIfNeeded();
      }

      // Nouvelle vérification ajoutée ici :
      const opponent = currentTurn === "white" ? "black" : "white";
      checkGameStatus(currentTurn);
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

      const movingPiece = selectedSquare.querySelector("span");

      if (!movingPiece) return;

      // Vérifie la validité du déplacement AVANT l’envoi
      if (!isValidMove(movingPiece, selectedSquare, square)) {
        showMessage("Mouvement invalide.");
        selectedSquare.classList.remove("selected");
        selectedSquare = null;
        return;
      }
      // En multijoueur, on saute la vérif JS pour le roque
      const isCastling =
        getPieceType(movingPiece) === "king" &&
        Math.abs(from[0].charCodeAt(0) - to[0].charCodeAt(0)) === 2;

      // En multijoueur, on ne vérifie pas localement si le roi est en échec après un coup.
      // On laisse le serveur décider.

      // Si tout est bon, envoie le coup au serveur
      // Si tout est bon, envoie le coup au serveur
      fetch("../controller/move_controller.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `game_id=${gameId}&from=${from}&to=${to}`,
      })
        .then((res) => res.text()) // Utilise .text() pour obtenir la réponse brute
        .then((data) => {
          console.log("Réponse brute du serveur : ", data); // Affiche la réponse brute du serveur

          try {
            const jsonResponse = JSON.parse(data); // Essayez de parser le JSON manuellement
            if (jsonResponse.success) {
              selectedSquare.classList.remove("selected");
              selectedSquare = null;
              getGameStateWithCallback(() => {
                checkGameStatus(currentTurn);
              });
            } else {
              alert(jsonResponse.error);
              selectedSquare.classList.remove("selected");
              selectedSquare = null;
            }
          } catch (error) {
            console.error("Erreur de parsing JSON :", error);
            alert(
              "Une erreur est survenue : La réponse du serveur n'est pas au format attendu."
            );
          }
        })
        .catch((err) => {
          console.error("Erreur AJAX :", err);
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
function showMessage(message) {
  const box = document.getElementById("game-message");
  if (!box) return;

  box.textContent = message;
  box.style.display = "block";
  box.classList.add("fade-up");

  setTimeout(() => {
    box.style.display = "none";
    box.classList.remove("fade-up");
  }, 3000);
}

// Rafraîchit automatiquement toutes les 2 secondes
setInterval(getGameState, 2000);
getGameState(); // initial
function getGameStateWithCallback(callback) {
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

      if (!playerColor && data.player_color) {
        playerColor = data.player_color;
        rotateBoardIfNeeded();
      }

      if (typeof callback === "function") {
        callback(); // Appelle la vérification des règles après update
      }
    })
    .catch((err) => {
      console.error("Erreur AJAX :", err);
    });
}
function checkGameStatus(color) {
  const gameStatusMessage = document.getElementById("game-status");
  console.log(`[DEBUG CHECK] Analyse du roi ${color}`);

  if (isKingInCheck(color)) {
    if (lastCheckStatus !== color + "_check") {
      showMessage("Échec");
      lastCheckStatus = color + "_check";
    }
  } else {
    lastCheckStatus = null;
  }

  if (!canKingEscape(color) && !canBlockCheck(color)) {
    if (isKingInCheck(color)) {
      const winner = color === "white" ? "Noir" : "Blanc";
      showMessage(`Échec et mat ! ${winner} gagne !`);
    } else {
      showMessage("Pat ! Partie nulle.");
    }
  }
}
