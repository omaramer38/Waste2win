const cards = document.querySelectorAll('.card');
let selectedPoints = 0;

cards.forEach(card => {
  card.addEventListener('click', () => {
    cards.forEach(c => c.style.border = 'none');
    card.style.border = '2px solid #f07d5d';
    selectedPoints = parseInt(card.dataset.points);
  });
});

document.getElementById('calculate').addEventListener('click', () => {
  const amount = parseFloat(document.getElementById('amount').value);
  if (!selectedPoints || !amount || amount <= 0) {
    alert('Please select waste type and enter a valid weight.');
    return;
  }
  const totalPoints = amount * selectedPoints;
  document.getElementById('result').textContent = `You earn ${totalPoints} points!`;
});
