document.addEventListener("DOMContentLoaded", () => {
  const squares = document.querySelectorAll(".square");

  squares.forEach((square) => {
    square.addEventListener("click", handleClick);
  });
});

function handleClick(e) {
  const square = e.target.closest(".square");
  if (!square) return;
}
