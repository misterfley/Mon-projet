window.addEventListener("beforeunload", () => {
  navigator.sendBeacon(
    "../controller/forfeit_controller.php",
    new URLSearchParams({ game_id: gameId })
  );
});

console.log("isKingInCheck existe ?", typeof isKingInCheck);

let lastCheckStatus = null;
let lastNoMoves = false;

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

let playerCardPositioned = false;
let playerColor = window.gameConfig?.playerColor || null;

let currentTurn = "white";
let selectedSquare = null;
function rotateBoardIfNeeded() {
  console.log("[DEBUG] Rotation du plateau ?", playerColor);

  if (playerColor === "black") {
    // Rotation de l'échiquier
    boardElement.style.transform = "rotate(180deg)";
    document.querySelectorAll(".square").forEach((square) => {
      square.style.transform = "rotate(180deg)";
    });
  } else {
    // Réinitialisation au cas où le joueur redevient blanc (évite un bug visuel)
    boardElement.style.transform = "rotate(0deg)";
    document.querySelectorAll(".square").forEach((square) => {
      square.style.transform = "rotate(0deg)";
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

  // Debug : vérifie nombre de rois blancs et noirs affichés après rendu
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
    .then((res) => res.json())
    .then((data) => {
      if (data.error) {
        console.error("Erreur :", data.error);
        return;
      }
      // Partie terminée ?
      if (data.status === "finished") {
        clearInterval(window.gamePolling);

        showMessage(
          data.winner === playerColor
            ? "Votre adversaire a abandonné. Vous avez gagné !"
            : "Vous avez perdu la partie.",
          true
        );
        // 1. Mets à jour la couleur et les cartes
        if (data.player_color) {
          playerColor = data.player_color;
        }

        if (playerColor === "white") {
          document.getElementById("self-name").textContent =
            data.white_nick ?? "Vous";
          document.getElementById("self-avatar").src = `../public/img/${
            data.white_avatar ?? "default.png"
          }`;
          document.getElementById("opponent-name").textContent =
            data.black_nick ?? "Libre";
          document.getElementById("opponent-avatar").src = `../public/img/${
            data.black_avatar ?? "default.png"
          }`;
        } else if (playerColor === "black") {
          document.getElementById("self-name").textContent =
            data.black_nick ?? "Vous";
          document.getElementById("self-avatar").src = `../public/img/${
            data.black_avatar ?? "default.png"
          }`;
          document.getElementById("opponent-name").textContent =
            data.white_nick ?? "Libre";
          document.getElementById("opponent-avatar").src = `../public/img/${
            data.white_avatar ?? "default.png"
          }`;
        }

        document.querySelectorAll(".square").forEach((sq) => {
          sq.style.pointerEvents = "none";
        });

        const buttons = document.getElementById("end-buttons");
        if (buttons) {
          buttons.style.display = "block";
          const forfeitBtn = document.getElementById("forfeit-btn");
          if (forfeitBtn) forfeitBtn.style.display = "none";

          const lobbyLink = document.getElementById("lobby-link");
          if (lobbyLink) {
            lobbyLink.textContent =
              data.winner === playerColor
                ? "Retourner en salle après victoire"
                : "Retourner en salle";
          }
        }

        return;
      }

      // 1. Mise à jour du plateau
      renderBoard(data.board);

      // 3. Mise à jour du tour
      currentTurn = data.turn;
      updateTurnIndicator(currentTurn);

      // 4. Rotation du plateau selon la couleur
      rotateBoardIfNeeded();
      // 5. Mise à jour des cartes joueurs pendant la partie
      if (playerColor === "white") {
        document.getElementById("self-name").textContent =
          data.white_nick ?? "Vous";
        document.getElementById("self-avatar").src = `../public/img/${
          data.white_avatar ?? "default.png"
        }`;
        document.getElementById("opponent-name").textContent =
          data.black_nick ?? "Libre";
        document.getElementById("opponent-avatar").src = `../public/img/${
          data.black_avatar ?? "default.png"
        }`;
      } else if (playerColor === "black") {
        document.getElementById("self-name").textContent =
          data.black_nick ?? "Vous";
        document.getElementById("self-avatar").src = `../public/img/${
          data.black_avatar ?? "default.png"
        }`;
        document.getElementById("opponent-name").textContent =
          data.white_nick ?? "Libre";
        document.getElementById("opponent-avatar").src = `../public/img/${
          data.white_avatar ?? "default.png"
        }`;
      }

      // 5. Vérifie l’état du jeu
      setTimeout(() => {
        requestAnimationFrame(() => {
          checkGameStatus(currentTurn);
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

    //  Re-clic sur la case déjà sélectionnée → désélection
    if (selectedSquare === targetSquare) {
      selectedSquare.classList.remove("selected");
      selectedSquare = null;
      console.log("Désélection via re-clic sur la même case");
      return;
    }

    //  Si une pièce est déjà sélectionnée, tentative de déplacement
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

        //  Envoie au serveur

        fetch("../controller/move_controller.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `game_id=${gameId}&from=${from}&to=${to}`,
        })
          .then((res) => res.json())
          .then((jsonResponse) => {
            if (jsonResponse.success) {
              lastCheckStatus = null;
              lastNoMoves = false;

              selectedSquare?.classList.remove("selected");
              selectedSquare = null;

              getGameStateWithCallback(checkGameStatus);
            } else {
              alert(jsonResponse.error);
              selectedSquare?.classList.remove("selected");
              selectedSquare = null;
              getGameState();
            }
          })
          .catch((err) => {
            console.error("Erreur AJAX :", err);
          });

        return;
      } else {
        selectedSquare.classList.remove("selected");
        selectedSquare = null;
        return;
      }
    }

    if (piece) {
      const isWhite = piece.classList.contains("white-piece");
      const pieceColor = isWhite ? "white" : "black";

      if (pieceColor !== playerColor || playerColor !== currentTurn) {
        return;
      }

      if (selectedSquare) selectedSquare.classList.remove("selected");
      selectedSquare = targetSquare;
      selectedSquare.classList.add("selected");
      console.log("Nouvelle sélection");
    }
  });
});

function showMessage(message, persistent = false) {
  const box = document.getElementById("game-message");
  if (!box) return;
  box.textContent = message;
  box.style.display = "block";
  box.classList.add("fade-up");

  if (!persistent) {
    // Message temporaire
    setTimeout(() => {
      box.style.display = "none";
      box.classList.remove("fade-up");
    }, 3000);
  }
}

// Rafraîchit automatiquement toutes les 2 secondes
window.gamePolling = setInterval(getGameState, 2000);
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

      playerColor = data.player_color ?? playerColor;

      rotateBoardIfNeeded();

      if (typeof callback === "function") {
        callback(currentTurn);
      }
    })
    .catch((err) => {
      console.error("Erreur AJAX :", err);
    });
}

function checkGameStatus(color) {
  const nowInCheck = isKingInCheck(color);

  // 1) Gestion de l'échec uniquement sur transition
  if (lastCheckStatus !== nowInCheck) {
    if (nowInCheck) {
      showMessage("Échec", true);
    } else {
      const box = document.getElementById("game-message");
      if (box) box.style.display = "none";
    }
    lastCheckStatus = nowInCheck;
  }

  // 2) Gestion du mat/pat uniquement sur transition
  const noMoves = !canKingEscape(color) && !canBlockCheck(color);
  if (lastNoMoves !== noMoves) {
    if (noMoves) {
      if (nowInCheck) {
        const winner = color === "white" ? "Noir" : "Blanc";
        showMessage(`Échec et mat ! ${winner} gagne !`, true);
      } else {
        showMessage("Pat ! Partie nulle.", true);
      }
      const buttons = document.getElementById("end-buttons");
      if (buttons) buttons.style.display = "block";
    } else {
      const box = document.getElementById("game-message");
      if (box) box.style.display = "none";
      const buttons = document.getElementById("end-buttons");
      if (buttons) buttons.style.display = "none";
    }
    lastNoMoves = noMoves;
  }
}

// Récupération et application du thème
document.addEventListener("DOMContentLoaded", () => {
  const themeSelect = document.getElementById("theme");
  if (!themeSelect) return;

  // Au changement
  themeSelect.addEventListener("change", (e) => {
    // Liste des thèmes possibles
    const THEMES = ["theme-sand", "theme-forest"];
    document.body.classList.remove(...THEMES);

    const theme = e.target.value;
    if (THEMES.includes(theme)) {
      document.body.classList.add(theme);
    }
  });
});
