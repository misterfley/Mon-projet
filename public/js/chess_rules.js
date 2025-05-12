function getPieceColor(piece) {
  if (!piece) return null;
  if (piece.classList.contains("white-piece")) return "white";
  if (piece.classList.contains("black-piece")) return "black";
  return null;
}
// fonction utilitaires
function getPieceType(piece) {
  const pieceChar = piece?.textContent;
  const pieceMap = {
    "♟": "pawn",
    "♙": "pawn",
    "♜": "rook",
    "♖": "rook",
    "♞": "knight",
    "♘": "knight",
    "♝": "bishop",
    "♗": "bishop",
    "♛": "queen",
    "♕": "queen",
    "♚": "king",
    "♔": "king",
  };
  return pieceMap[pieceChar] || null;
}

function isPathClear(fromFile, fromRank, toFile, toRank) {
  if (fromFile === toFile && fromRank === toRank) return true;

  let fileStep = fromFile < toFile ? 1 : fromFile > toFile ? -1 : 0;
  let rankStep = fromRank < toRank ? 1 : fromRank > toRank ? -1 : 0;

  let file = fromFile.charCodeAt(0) + fileStep;
  let rank = fromRank + rankStep;

  console.log(`[DEBUG PATH] from ${fromFile}${fromRank} to ${toFile}${toRank}`);

  while (file !== toFile.charCodeAt(0) || rank !== toRank) {
    if (
      file < "a".charCodeAt(0) ||
      file > "h".charCodeAt(0) ||
      rank < 1 ||
      rank > 8
    ) {
      console.warn(
        `[PATH ERROR] Coordonnée hors échiquier : ${String.fromCharCode(
          file
        )}${rank}`
      );
      return false;
    }

    const coord = `${String.fromCharCode(file)}${rank}`;
    let square = document.getElementById(coord);
    const occupied = square?.querySelector("span");
    console.log(` → ${coord} ${occupied ? "bloqué" : "libre"}`);
    if (occupied) return false;

    file += fileStep;
    rank += rankStep;
  }

  return true;
}

//gestion deplacements
function isValidMove(piece, fromSquare, toSquare) {
  if (!piece) return false;

  const pieceType = getPieceType(piece);
  const fromId = fromSquare.id,
    toId = toSquare.id;
  const fromFile = fromId[0],
    fromRank = parseInt(fromId[1], 10);
  const toFile = toId[0],
    toRank = parseInt(toId[1], 10);
  const targetPiece = toSquare.querySelector("span");

  // Les rois ne peuvent pas s'attaquer entre eux
  if (pieceType === "king" && getPieceType(targetPiece) === "king") {
    return false;
  }

  // Interdit de capturer ses propres pièces
  if (targetPiece && getPieceColor(targetPiece) === getPieceColor(piece)) {
    return false;
  }

  switch (pieceType) {
    case "pawn":
      return isValidPawnMove(
        piece,
        fromFile,
        fromRank,
        toFile,
        toRank,
        targetPiece
      );

    case "rook":
      return isValidRookMove(fromFile, fromRank, toFile, toRank);

    case "knight":
      return isValidKnightMove(fromFile, fromRank, toFile, toRank);

    case "bishop":
      return isValidBishopMove(fromFile, fromRank, toFile, toRank);

    case "queen":
      return isValidQueenMove(fromFile, fromRank, toFile, toRank);

    case "king":
      const fileDiff = Math.abs(toFile.charCodeAt(0) - fromFile.charCodeAt(0));
      const rankDiff = Math.abs(toRank - fromRank);

      // Autorise le roque : déplacement horizontal de 2 cases sans changement de rang
      if (fileDiff === 2 && rankDiff === 0) {
        return true; // on laisse le serveur vérifier les règles complètes du roque
      }

      // Tout autre mouvement : déplacement classique du roi
      return isValidKingMove(fromFile, fromRank, toFile, toRank);

    default:
      return false;
  }
}

function isValidRookMove(fromFile, fromRank, toFile, toRank) {
  return (
    (fromFile === toFile || fromRank === toRank) &&
    isPathClear(fromFile, fromRank, toFile, toRank)
  );
}

function isValidBishopMove(fromFile, fromRank, toFile, toRank) {
  return (
    Math.abs(toFile.charCodeAt(0) - fromFile.charCodeAt(0)) ===
      Math.abs(toRank - fromRank) &&
    isPathClear(fromFile, fromRank, toFile, toRank)
  );
}

function isValidQueenMove(fromFile, fromRank, toFile, toRank) {
  return (
    isValidRookMove(fromFile, fromRank, toFile, toRank) ||
    isValidBishopMove(fromFile, fromRank, toFile, toRank)
  );
}

function isValidKnightMove(fromFile, fromRank, toFile, toRank) {
  const fileDiff = Math.abs(toFile.charCodeAt(0) - fromFile.charCodeAt(0));
  const rankDiff = Math.abs(toRank - fromRank);
  return (
    (fileDiff === 2 && rankDiff === 1) || (fileDiff === 1 && rankDiff === 2)
  );
}

function isValidKingMove(fromFile, fromRank, toFile, toRank) {
  return (
    Math.abs(toFile.charCodeAt(0) - fromFile.charCodeAt(0)) <= 1 &&
    Math.abs(toRank - fromRank) <= 1
  );
}
function isValidPawnMove(
  piece,
  fromFile,
  fromRank,
  toFile,
  toRank,
  targetPiece
) {
  const direction = getPieceColor(piece) === "white" ? 1 : -1;
  const startRank = getPieceColor(piece) === "white" ? 2 : 7;

  // une case vers l'avant
  if (fromFile === toFile && toRank === fromRank + direction && !targetPiece) {
    return true;
  }

  // deux cases depuis la position initiale
  if (
    fromFile === toFile &&
    fromRank === startRank &&
    toRank === fromRank + 2 * direction &&
    !targetPiece &&
    isPathClear(fromFile, fromRank, toFile, toRank)
  ) {
    return true;
  }

  // Capture  diagonale
  if (
    Math.abs(toFile.charCodeAt(0) - fromFile.charCodeAt(0)) === 1 &&
    toRank === fromRank + direction &&
    targetPiece
  ) {
    return true;
  }

  return false;
}
function isKingInCheck(color) {
  const kingSquare = [...document.querySelectorAll(".square")].find(
    (square) => {
      const piece = square.querySelector("span");
      return (
        piece &&
        getPieceColor(piece) === color &&
        getPieceType(piece) === "king"
      );
    }
  );

  if (!kingSquare) {
    console.warn(`[CHECK] Aucun roi trouvé pour ${color}`);
    return false;
  }

  console.log(
    `[CHECK] Recherche des attaquants pour le roi ${color} en ${kingSquare.id}`
  );

  const allSquares = [...document.querySelectorAll(".square")];

  const attackers = allSquares.filter((square) => {
    const piece = square.querySelector("span");

    if (
      piece &&
      getPieceColor(piece) !== color &&
      isValidMove(piece, square, kingSquare)
    ) {
      console.log(
        `[ATTAQUE] ${piece.textContent} (${square.id}) menace le roi en ${kingSquare.id}`
      );
      return true;
    }

    return false;
  });

  if (attackers.length === 0) {
    console.log(`[CHECK DEBUG] Aucun attaquant détecté pour le roi ${color}`);
    console.log(`[CHECK DEBUG] Etat des pièces adverses :`);

    allSquares.forEach((sq) => {
      const p = sq.querySelector("span");
      if (p && getPieceColor(p) !== color) {
        console.log(
          `[ATTAQUE ?] ${p.textContent} (${sq.id}) → vers roi en ${kingSquare.id}`
        );
        console.log(" → Mouvement valide ?", isValidMove(p, sq, kingSquare));
      }
    });

    return false;
  }

  console.warn(
    `[CHECK DEBUG] ${color} king on ${kingSquare.id} | Attaquants :`,
    attackers.map((s) => `${s.id}=${s.querySelector("span")?.textContent}`)
  );

  return true;
}
function isKingInCheckAfterMove(fromSquare, toSquare, color) {
  //Sauvegarde l'état initial
  const tempContent = toSquare.innerHTML;
  toSquare.innerHTML = fromSquare.innerHTML;
  fromSquare.innerHTML = "";

  const kingInCheck = isKingInCheck(color);

  // état initial
  fromSquare.innerHTML = toSquare.innerHTML;
  toSquare.innerHTML = tempContent;

  return kingInCheck;
}

//gestion interactions joueur
function handleClick(e) {
  const square = e.target.closest(".square");
  if (!square) return;

  console.log(`Case cliquée: ${square.id}`);

  if (selectedSquare === square) {
    console.log("Désélection de la case");
    selectedSquare.classList.remove("selected");
    selectedSquare = null;
    return;
  }

  const piece = square.querySelector("span");

  if (selectedSquare) {
    const movingPiece = selectedSquare.querySelector("span");
    if (!movingPiece) return;

    if (isValidMove(movingPiece, selectedSquare, square)) {
      console.log("Mouvement valide !");
      if (isKingInCheckAfterMove(selectedSquare, square, currentPlayer)) {
        showMessage("Impossible : votre roi serait en échec !");
        selectedSquare.classList.remove("selected");
        selectedSquare = null;
        return;
      }

      if (
        getPieceType(movingPiece) === "king" &&
        Math.abs(
          selectedSquare.id[0].charCodeAt(0) - square.id[0].charCodeAt(0)
        ) === 2
      ) {
        handleCastlingMove(selectedSquare, square);
      } else {
        square.innerHTML = selectedSquare.innerHTML;
        selectedSquare.innerHTML = "";
      }

      updateCastlingRights(movingPiece, selectedSquare.id);
      selectedSquare.classList.remove("selected");
      selectedSquare = null;

      if (isKingInCheck(currentPlayer)) showMessage("Échec !");
      currentPlayer = currentPlayer === "white" ? "black" : "white";
      updatePlayerIndicator();
      checkGameStatus();
    } else {
      console.log("Mouvement invalide !");
      selectedSquare.classList.remove("selected");
      selectedSquare = null;
    }
  } else {
    if (selectedSquare) selectedSquare.classList.remove("selected");

    // Si la case contient une pièce jouable, on la sélectionne
    if (piece && getPieceColor(piece) === currentPlayer) {
      selectedSquare = square;
      selectedSquare.classList.add("selected");
      console.log("Nouvelle sélection");
    } else {
      // Sinon on annule la sélection
      selectedSquare = null;
      console.log("Aucune sélection possible");
    }
  }
}

function showMessage(message) {
  const messageBox = document.getElementById("game-message");
  if (messageBox) {
    // Effacer tous les anciens messages avant d'afficher un nouveau
    messageBox.style.display = "none";

    // Afficher le nouveau message
    messageBox.textContent = message;
    messageBox.style.display = "block";
    messageBox.classList.add("fade-up"); // Ajouter une animation d'entrée

    // Masquer le message après 4 secondes
    setTimeout(() => {
      messageBox.style.display = "none";
      messageBox.classList.remove("fade-up");
    }, 4000); // Augmenter le délai pour s'assurer que le message est bien visible
  }
}

function updatePlayerIndicator() {
  const indicator = document.querySelector(".player-indicator");
  if (!indicator) return;

  indicator.classList.add("fade-up");
  indicator.innerHTML = `${
    currentPlayer.charAt(0).toUpperCase() + currentPlayer.slice(1)
  }'s turn`;
  setTimeout(() => indicator.classList.remove("fade-up"), 500);
}

function updateCastlingRights(movingPiece, fromSquare) {
  const pieceType = getPieceType(movingPiece);
  const color = getPieceColor(movingPiece);

  if (pieceType === "king") {
    if (color === "white") whiteKingMoved = true;
    else blackKingMoved = true;
  }

  if (pieceType === "rook") {
    if (color === "white") {
      if (fromSquare === "a1") whiteRookLeftMoved = true;
      if (fromSquare === "h1") whiteRookRightMoved = true;
    } else {
      if (fromSquare === "a8") blackRookLeftMoved = true;
      if (fromSquare === "h8") blackRookRightMoved = true;
    }
  }
}

function handleCastlingMove(fromSquare, toSquare) {
  const king = fromSquare.querySelector("span");
  const fromFile = fromSquare.id[0];
  const toFile = toSquare.id[0];
  const rank = fromSquare.id[1];

  let rookSquare, newRookSquare;

  if (toFile === "g") {
    rookSquare = document.getElementById(`h${rank}`);
    newRookSquare = document.getElementById(`f${rank}`);
  } else if (toFile === "c") {
    rookSquare = document.getElementById(`a${rank}`);
    newRookSquare = document.getElementById(`d${rank}`);
  } else {
    return;
  }

  const rook = rookSquare?.querySelector("span");
  if (!rook) return;

  toSquare.appendChild(king);
  fromSquare.innerHTML = "";

  newRookSquare.appendChild(rook);
  rookSquare.innerHTML = "";
}
function checkGameStatus(color) {
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
}

function canKingEscape(color) {
  const kingSquare = [...document.querySelectorAll(".square")].find(
    (square) => {
      const piece = square.querySelector("span");
      return (
        piece &&
        getPieceColor(piece) === color &&
        getPieceType(piece) === "king"
      );
    }
  );

  if (!kingSquare) return false;

  const kingMoves = [
    [1, 0],
    [-1, 0],
    [0, 1],
    [0, -1],
    [1, 1],
    [-1, -1],
    [1, -1],
    [-1, 1],
  ];

  return kingMoves.some(([dx, dy]) => {
    const newFile = String.fromCharCode(kingSquare.id.charCodeAt(0) + dx);
    const newRank = parseInt(kingSquare.id[1], 10) + dy;
    const newSquare = document.getElementById(newFile + newRank);

    if (
      newSquare &&
      (!newSquare.querySelector("span") ||
        getPieceColor(newSquare.querySelector("span")) !== color)
    ) {
      return !isKingInCheckAfterMove(kingSquare, newSquare, color);
    }
    return false;
  });
}

function canBlockCheck(color) {
  const pieces = [...document.querySelectorAll(".square")].filter((square) => {
    const piece = square.querySelector("span");
    return piece && getPieceColor(piece) === color;
  });

  return pieces.some((square) => {
    const piece = square.querySelector("span");
    return [...document.querySelectorAll(".square")].some((target) => {
      if (
        target !== square &&
        (!target.querySelector("span") ||
          getPieceColor(target.querySelector("span")) !== color)
      ) {
        return (
          isValidMove(piece, square, target) &&
          !isKingInCheckAfterMove(square, target, color)
        );
      }
      return false;
    });
  });
}
function isStalemate(color) {
  return (
    !isKingInCheck(color) && !canKingEscape(color) && !canBlockCheck(color)
  );
}

function isValidCastlingMove(piece, fromFile, fromRank, toFile, toRank) {
  if (fromRank !== toRank) return false; // Roque seulement sur la même ligne

  const color = getPieceColor(piece);
  const isWhite = color === "white";
  const kingMoved = isWhite ? whiteKingMoved : blackKingMoved;
  const leftRookMoved = isWhite ? whiteRookLeftMoved : blackRookLeftMoved;
  const rightRookMoved = isWhite ? whiteRookRightMoved : blackRookRightMoved;

  if (kingMoved) return false;

  if (toFile === "g") {
    // Roque petit (côté roi)
    if (rightRookMoved) return false;
    return isPathClear(fromFile, fromRank, "h", fromRank);
  }

  if (toFile === "c") {
    // Roque grand (côté dame)
    if (leftRookMoved) return false;
    return isPathClear("a", fromRank, fromFile, fromRank);
  }

  return false;
}
