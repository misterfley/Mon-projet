let selectedSquare = null;
let currentPlayer = "white"; //  joueur blanc commence
const board = document.querySelector(".board");
const squares = document.querySelectorAll(".square");

// Variables pour suivre  mouvements  roi et  tours
let whiteKingMoved = false,
  blackKingMoved = false;
let whiteRookLeftMoved = false,
  whiteRookRightMoved = false;
let blackRookLeftMoved = false,
  blackRookRightMoved = false;

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
  let fileStep = fromFile < toFile ? 1 : fromFile > toFile ? -1 : 0;
  let rankStep = fromRank < toRank ? 1 : fromRank > toRank ? -1 : 0;
  let file = fromFile.charCodeAt(0) + fileStep;
  let rank = fromRank + rankStep;

  while (file !== toFile.charCodeAt(0) || rank !== toRank) {
    let square = document.getElementById(String.fromCharCode(file) + rank);
    if (!square || square.querySelector("span")) return false;
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
      if (Math.abs(toFile.charCodeAt(0) - fromFile.charCodeAt(0)) === 2) {
        return isValidCastlingMove(piece, fromFile, fromRank, toFile, toRank);
      }
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

  if (!kingSquare) return false; // Le roi doit jamais disparaître

  return [...document.querySelectorAll(".square")].some((square) => {
    const piece = square.querySelector("span");
    return (
      piece &&
      getPieceColor(piece) !== color &&
      isValidMove(piece, square, kingSquare)
    );
  });
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
  } else if (piece && getPieceColor(piece) === currentPlayer) {
    console.log("Sélection de la pièce");
    if (selectedSquare) selectedSquare.classList.remove("selected");
    selectedSquare = square;
    selectedSquare.classList.add("selected");
  }
}

function showMessage(message) {
  const messageBox = document.getElementById("game-message");
  if (messageBox) {
    messageBox.textContent = message;
    messageBox.style.display = "block";
    setTimeout(() => {
      messageBox.style.display = "none";
    }, 2000);
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
squares.forEach((square) => square.addEventListener("click", handleClick));
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
