document.addEventListener("DOMContentLoaded", () => {
  const squares = document.querySelectorAll(".square"); // Récupère toutes les cases du tableau une fois le DOM chargé

  squares.forEach((square) => {
    square.addEventListener("click", handleClick); // Ajoute un gestionnaire d'événements de clic pour chaque case
  });

  // Définir ici tes autres variables ou fonctions si nécessaire
});

function handleClick(e) {
  const square = e.target.closest(".square");
  if (!square) return;
  // Code pour traiter le clic sur une case
}
