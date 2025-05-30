const spinButton = document.getElementById('spinButton');
const reels = document.querySelectorAll('.reel');
const debug = document.getElementById('debug');

const symbols = ['🍒', '🍋', '🍊', '🍇', '🍉', '⭐', '🔔', '7️⃣'];

let balance = 1000;
let bet = 10; // Default bet

// Create UI for balance and bet
const uiContainer = document.createElement('div');
uiContainer.innerHTML = `
  <label>Apuesta:
    <select id="betSelect">
      <option value="10">$10</option>
      <option value="50">$50</option>
      <option value="100">$100</option>
    </select>
  </label>
  <p id="result"></p>
`;
document.body.insertBefore(uiContainer, document.querySelector('.slot-wrapper'));

const balanceSpan = document.getElementById('balance');
const resultText = document.getElementById('result');
const betSelect = document.getElementById('betSelect');
betSelect.addEventListener('change', () => {
  bet = parseInt(betSelect.value);
});

function spinSlots() {
  if (balance < bet) {
    resultText.textContent = 'Saldo insuficiente';
    return;
  }

  balance -= bet;
  balanceSpan.textContent = balance;
  resultText.textContent = 'Girando...';

  const results = [];

  reels.forEach((reel, i) => {
    reel.textContent = '';
    let counter = 0;
    const spinInterval = setInterval(() => {
      const symbol = symbols[Math.floor(Math.random() * symbols.length)];
      reel.textContent = symbol;
      counter++;
      if (counter > 20 + i * 10) {
        clearInterval(spinInterval);
        results[i] = symbol;

        // When all reels are done spinning
        if (results.length === 3 && results.every((val) => val)) {
          setTimeout(() => evaluateResult(results), 500);
        }
      }
    }, 100);
  });
}

function evaluateResult([s1, s2, s3]) {
  if (s1 === s2 && s2 === s3) {
    let prize = 0;
    if (s1 === '7️⃣') {
      prize = bet * 50;
    } else if (s1 === '⭐') {
      prize = bet * 20;
    } else {
      prize = bet * 10;
    }
    balance += prize;
    balanceSpan.textContent = balance;
    resultText.textContent = `¡Ganaste $${prize} con ${s1}${s2}${s3}!`;
  } else {
    resultText.textContent = `Perdiste. Salieron ${s1}${s2}${s3}`;
  }
}

spinButton.addEventListener('click', spinSlots);
