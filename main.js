let selectedSquare = null;
let currentPlayer = "white"; // Le joueur blanc commence
const board = document.querySelector(".board");
const squares = document.querySelectorAll(".square");

function getPieceColor(piece) {
  if (piece.classList.contains("white-piece")) {
    return "white";
  } else if (piece.classList.contains("black-piece")) {
    return "black";
  }
  return null;
}

function getPieceType(piece) {
  const pieceChar = piece.textContent;
  switch (pieceChar) {
    case "♟":
    case "♙":
      return "pawn";
    case "♜":
    case "♖":
      return "rook";
    case "♞":
    case "♘":
      return "knight";
    case "♝":
    case "♗":
      return "bishop";
    case "♛":
    case "♕":
      return "queen";
    case "♚":
    case "♔":
      return "king";
    default:
      return null;
  }
}

function isValidMove(piece, fromSquare, toSquare) {
  const pieceType = getPieceType(piece);
  const fromId = fromSquare.id;
  const toId = toSquare.id;

  const fromFile = fromId.charAt(0);
  const fromRank = parseInt(fromId.charAt(1), 10);
  const toFile = toId.charAt(0);
  const toRank = parseInt(toId.charAt(1), 10);

  const targetPiece = toSquare.querySelector("span");
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
      return isValidKingMove(fromFile, fromRank, toFile, toRank);
    default:
      return false;
  }
}

// Vérifie si le chemin est bloqué pour la tour, le fou et la dame
function isPathClear(fromFile, fromRank, toFile, toRank) {
  let fileStep = fromFile < toFile ? 1 : fromFile > toFile ? -1 : 0;
  let rankStep = fromRank < toRank ? 1 : fromRank > toRank ? -1 : 0;

  let file = fromFile.charCodeAt(0) + fileStep;
  let rank = fromRank + rankStep;

  while (file !== toFile.charCodeAt(0) || rank !== toRank) {
    let square = document.getElementById(String.fromCharCode(file) + rank);
    if (square && square.querySelector("span")) {
      return false;
    }
    file += fileStep;
    rank += rankStep;
  }
  return true;
}

// Vérification des mouvements
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

  if (fromFile === toFile && toRank === fromRank + direction && !targetPiece) {
    return true;
  }

  if (
    fromFile === toFile &&
    fromRank === startRank &&
    toRank === fromRank + 2 * direction
  ) {
    let midSquare = document.getElementById(fromFile + (fromRank + direction));
    return midSquare && !midSquare.querySelector("span") && !targetPiece;
  }

  if (
    Math.abs(toFile.charCodeAt(0) - fromFile.charCodeAt(0)) === 1 &&
    toRank === fromRank + direction &&
    targetPiece
  ) {
    return true;
  }

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

// Gestion du clic sur une case
function handleClick(e) {
  const square = e.target.closest(".square");
  if (!square) return;

  const piece = square.querySelector("span");
  if (selectedSquare) {
    if (
      isValidMove(selectedSquare.querySelector("span"), selectedSquare, square)
    ) {
      square.innerHTML = selectedSquare.innerHTML;
      selectedSquare.innerHTML = "";
      selectedSquare.classList.remove("selected");
      selectedSquare = null;

      currentPlayer = currentPlayer === "white" ? "black" : "white";
      updatePlayerIndicator();
    } else {
      selectedSquare.classList.remove("selected");
      selectedSquare = null;
    }
  } else if (piece && getPieceColor(piece) === currentPlayer) {
    if (selectedSquare) {
      selectedSquare.classList.remove("selected");
    }
    selectedSquare = square;
    selectedSquare.classList.add("selected");
  }
}

function updatePlayerIndicator() {
  const indicator = document.querySelector(".player-indicator");
  indicator.classList.add("fade-up");
  indicator.innerHTML = `${
    currentPlayer.charAt(0).toUpperCase() + currentPlayer.slice(1)
  }'s turn`;
  setTimeout(() => {
    indicator.classList.remove("fade-up");
  }, "500");
}

squares.forEach((square) => square.addEventListener("click", handleClick));
updatePlayerIndicator();
