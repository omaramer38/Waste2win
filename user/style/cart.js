document.addEventListener("DOMContentLoaded", () => {
  const qtyValue = document.querySelector(".qty-value");
  const totalPointsEl = document.querySelector(".total-points");
  const badge = document.querySelector(".cart-badge");

  const summaryItems = document.getElementById("summary-items");
  const summaryQuantity = document.getElementById("summary-quantity");
  const summaryTotal = document.getElementById("summary-total");
  const summaryBalance = document.getElementById("summary-balance");
  const remainingPoints = document.getElementById("remaining-points");

  const unitPoints = 200;
  let quantity = 1;
  let balance = 345;

  function update() {
    const total = quantity * unitPoints;

    qtyValue.textContent = quantity;
    totalPointsEl.textContent = total;

    summaryItems.textContent = 1;
    summaryQuantity.textContent = quantity;
    summaryTotal.textContent = total;

    badge.textContent = quantity;
    remainingPoints.textContent = balance - total;
  }

  document.querySelectorAll(".qty-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      if (btn.dataset.action === "increase") quantity++;
      if (btn.dataset.action === "decrease" && quantity > 1) quantity--;

      update();
    });
  });

  update();
});
