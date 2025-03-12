let selectedSquare = null;
let currentPlayer = "white"; // Le joueur blanc commence
const board = document.querySelector(".board");
const squares = document.querySelectorAll(".square");

function getPieceColor(piece) {
  if (!piece) return null;
  if (piece.classList.contains("white-piece")) return "white";
  if (piece.classList.contains("black-piece")) return "black";
  return null;
}

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

function isValidMove(piece, fromSquare, toSquare) {
  if (!piece) return false;

  const pieceType = getPieceType(piece);
  const fromId = fromSquare.id;
  const toId = toSquare.id;

  const fromFile = fromId.charAt(0);
  const fromRank = parseInt(fromId.charAt(1), 10);
  const toFile = toId.charAt(0);
  const toRank = parseInt(toId.charAt(1), 10);

  const targetPiece = toSquare.querySelector("span");
  if (targetPiece && getPieceColor(targetPiece) === getPieceColor(piece))
    return false;

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
      return isValidKingMove(fromFile, fromRank, toFile, toRank);
    default:
      return false;
  }
}

// Vérifie si le chemin est bloqué (tour, fou, reine)
function isPathClear(fromFile, fromRank, toFile, toRank) {
  let fileStep = fromFile < toFile ? 1 : fromFile > toFile ? -1 : 0;
  let rankStep = fromRank < toRank ? 1 : fromRank > toRank ? -1 : 0;

  let file = fromFile.charCodeAt(0) + fileStep;
  let rank = fromRank + rankStep;

  while (file !== toFile.charCodeAt(0) || rank !== toRank) {
    let square = document.getElementById(String.fromCharCode(file) + rank);
    if (square?.querySelector("span")) return false;
    file += fileStep;
    rank += rankStep;
  }
  return true;
}

// Vérif  mouvements spécifiques
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

  if (fromFile === toFile && toRank === fromRank + direction && !targetPiece)
    return true;
  if (
    fromFile === toFile &&
    fromRank === startRank &&
    toRank === fromRank + 2 * direction &&
    !targetPiece
  )
    return true;
  if (
    Math.abs(toFile.charCodeAt(0) - fromFile.charCodeAt(0)) === 1 &&
    toRank === fromRank + direction &&
    targetPiece
  )
    return true;

  return false;
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

function isValidKingMove(fromFile, fromRank, toFile, toRank) {
  return (
    Math.abs(toFile.charCodeAt(0) - fromFile.charCodeAt(0)) <= 1 &&
    Math.abs(toRank - fromRank) <= 1
  );
}

function isValidKnightMove(fromFile, fromRank, toFile, toRank) {
  const fileDiff = Math.abs(toFile.charCodeAt(0) - fromFile.charCodeAt(0));
  const rankDiff = Math.abs(toRank - fromRank);
  return (
    (fileDiff === 2 && rankDiff === 1) || (fileDiff === 1 && rankDiff === 2)
  );
}
function updatePlayerIndicator() {
  console.log("Mise à jour de l'indicateur de joueur...");

  const indicator = document.querySelector(".player-indicator");
  if (!indicator) {
    console.error("L'élément .player-indicator n'a pas été trouvé !");
    return;
  }

  indicator.classList.add("fade-up");
  indicator.innerHTML = `${
    currentPlayer.charAt(0).toUpperCase() + currentPlayer.slice(1)
  }'s turn`;

  setTimeout(() => {
    indicator.classList.remove("fade-up");
  }, 500);
}

// Gestion  clic sur une case
function handleClick(e) {
  const square = e.target.closest(".square");
  if (!square) return;

  const piece = square.querySelector("span");

  if (selectedSquare) {
    if (
      isValidMove(selectedSquare.querySelector("span"), selectedSquare, square)
    ) {
      //  le mouvement laisse le roi en échec?
      if (isKingInCheckAfterMove(selectedSquare, square, currentPlayer)) {
        alert("Mouvement illégal : votre roi serait en échec !");
        selectedSquare.classList.remove("selected");
        selectedSquare = null;
        return;
      }

      // Déplacement valide, maj echiquier
      square.innerHTML = selectedSquare.innerHTML;
      selectedSquare.innerHTML = "";
      selectedSquare.classList.remove("selected");
      selectedSquare = null;

      // Vérif après chaque coup
      if (isKingInCheck(currentPlayer)) {
        alert("Échec !");
      }

      currentPlayer = currentPlayer === "white" ? "black" : "white";
      updatePlayerIndicator();
      checkGameStatus();
    } else {
      // Déplacement invalide
      selectedSquare.classList.remove("selected");
      selectedSquare = null;
    }
  } else if (piece && getPieceColor(piece) === currentPlayer) {
    // Sélection de la pièce
    if (selectedSquare) selectedSquare.classList.remove("selected");
    selectedSquare = square;
    selectedSquare.classList.add("selected");
  }
}

function isKingInCheck(color) {
  // Trouver la position du roi du joueur actuel
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

  if (!kingSquare) return false; // Sécurité : le roi ne devrait jamais disparaître

  // Vérifier si une pièce adverse peut capturer le roi
  return [...document.querySelectorAll(".square")].some((square) => {
    const piece = square.querySelector("span");
    if (piece && getPieceColor(piece) !== color) {
      return isValidMove(piece, square, kingSquare);
    }
    return false;
  });
}
function isKingInCheckAfterMove(fromSquare, toSquare, color) {
  const tempContent = toSquare.innerHTML;
  toSquare.innerHTML = fromSquare.innerHTML;
  fromSquare.innerHTML = "";

  const kingInCheck = isKingInCheck(color);

  // Restaurer l'état initial
  fromSquare.innerHTML = toSquare.innerHTML;
  toSquare.innerHTML = tempContent;

  return kingInCheck;
}

function checkGameStatus() {
  if (isKingInCheck(currentPlayer)) {
    if (!canKingEscape(currentPlayer) && !canBlockCheck(currentPlayer)) {
      alert(
        `Échec et mat ! ${currentPlayer === "white" ? "Noir" : "Blanc"} gagne !`
      );
    } else {
      alert("Échec !");
    }
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
    const newRank = parseInt(kingSquare.id.charAt(1), 10) + dy;
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

squares.forEach((square) => {
  square.addEventListener("click", handleClick);
});
