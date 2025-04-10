console.log("isKingInCheck existe ?", typeof isKingInCheck);

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
  // Supprime tout contenu de chaque case
  document.querySelectorAll(".square").forEach((square) => {
    while (square.firstChild) {
      square.removeChild(square.firstChild);
    }
  });

  // Ajoute les pièces à leur place selon boardData
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

  // ✅ Debug : vérifie le nombre de rois blancs et noirs affichés après rendu
  const allSpans = [...document.querySelectorAll(".square span")];
  const whiteKings = allSpans.filter((el) => el.textContent === "♔");
  const blackKings = allSpans.filter((el) => el.textContent === "♚");

  console.log(
    "[DEBUG ROIS] ♔ blancs :",
    whiteKings.map((el) => el.parentElement.id)
  );
  console.log(
    "[DEBUG ROIS] ♚ noirs :",
    blackKings.map((el) => el.parentElement.id)
  );
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

      // On attend une frame + 50ms pour s'assurer que le DOM est bien prêt

      setTimeout(() => {
        requestAnimationFrame(() => {
          checkGameStatus(currentTurn); // ← on vérifie le roi du joueur qui va jouer
        });
      }, 50);
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
  square.addEventListener("click", (e) => {
    const targetSquare = e.currentTarget; // ← assure que c’est la case cliquée (pas le span)
    const piece = targetSquare.querySelector("span");

    // 🟢 Re-clic sur la case déjà sélectionnée → désélection
    if (selectedSquare === targetSquare) {
      selectedSquare.classList.remove("selected");
      selectedSquare = null;
      console.log("Désélection via re-clic sur la même case");
      return;
    }

    // 🎯 Si une pièce est déjà sélectionnée, tentative de déplacement
    if (selectedSquare && selectedSquare !== targetSquare) {
      const from = selectedSquare.id;
      const to = targetSquare.id;
      const movingPiece = selectedSquare.querySelector("span");

      if (!movingPiece) return;

      if (isValidMove(movingPiece, selectedSquare, targetSquare)) {
        if (
          typeof isKingInCheckAfterMove === "function" &&
          isKingInCheckAfterMove(selectedSquare, targetSquare, playerColor)
        ) {
          showMessage("Impossible : ce coup vous met en échec !");
          selectedSquare.classList.remove("selected");
          selectedSquare = null;
          getGameState();
          return;
        }

        // ✅ Envoie au serveur
        fetch("../controller/move_controller.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `game_id=${gameId}&from=${from}&to=${to}`,
        })
          .then((res) => res.text())
          .then((data) => {
            console.log("Réponse brute du serveur : ", data);
            try {
              const jsonResponse = JSON.parse(data);
              if (jsonResponse.success) {
                selectedSquare.classList.remove("selected");
                selectedSquare = null;
                getGameStateWithCallback((justMoved) => {
                  checkGameStatus(justMoved);
                });
              } else {
                alert(jsonResponse.error);
                selectedSquare.classList.remove("selected");
                selectedSquare = null;
                getGameState();
              }
            } catch (error) {
              console.error("Erreur de parsing JSON :", error);
              alert("Une erreur est survenue.");
              getGameState();
            }
          })
          .catch((err) => {
            console.error("Erreur AJAX :", err);
          });

        return;
      } else {
        // Mouvement invalide
        selectedSquare.classList.remove("selected");
        selectedSquare = null;
        return;
      }
    }

    // 🟢 Sélection d’une pièce jouable
    if (piece) {
      const isWhite = piece.classList.contains("white-piece");
      const pieceColor = isWhite ? "white" : "black";

      if (pieceColor !== playerColor || playerColor !== currentTurn) {
        return; // pas ta pièce ou pas ton tour
      }

      if (selectedSquare) selectedSquare.classList.remove("selected");
      selectedSquare = targetSquare;
      selectedSquare.classList.add("selected");
      console.log("Nouvelle sélection");
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
      setTimeout(() => {
        console.log(
          "[AFTER renderBoard] Position du roi blanc :",
          [...document.querySelectorAll(".square")].find(
            (s) => s.querySelector("span")?.textContent === "♔"
          )?.id
        );
      }, 100);

      const previousTurn = currentTurn;
      updateTurnIndicator(data.turn);
      currentTurn = data.turn;

      if (!playerColor && data.player_color) {
        playerColor = data.player_color;
        rotateBoardIfNeeded();
      }

      if (typeof callback === "function") {
        const justMoved = previousTurn; // ← le tour d'avant
        callback(justMoved);
      }
    })
    .catch((err) => {
      console.error("Erreur AJAX :", err);
    });
}
function checkGameStatus(color) {
  console.log(`[STATUT] Vérification pour le roi ${color}`);

  requestAnimationFrame(() => {
    setTimeout(() => {
      const gameStatusMessage = document.getElementById("game-status");

      if (isKingInCheck(color)) {
        showMessage("Échec");
      } else if (gameStatusMessage) {
        gameStatusMessage.style.display = "none";
      }

      if (!canKingEscape(color) && !canBlockCheck(color)) {
        if (isKingInCheck(color)) {
          const winner = color === "white" ? "Noir" : "Blanc";
          showMessage(`Échec et mat ! ${winner} gagne !`);
        } else {
          showMessage("Pat ! Partie nulle.");
        }

        const buttons = document.getElementById("end-buttons");
        if (buttons) buttons.style.display = "block";
      }
    }, 50); // ← petit délai pour laisser le DOM vraiment finir
  });
}
