
window.addEventListener("DOMContentLoaded", () => {
  const progress = document.getElementById("progress");
  const points = parseInt(document.getElementById("points").innerText);
  const percent = (points / 1000) * 100;
  progress.style.width = percent + "%";
});

