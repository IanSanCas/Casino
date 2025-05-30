const wheel = document.getElementById('wheel');
const numbersContainer = document.getElementById('numbers');
const resultText = document.getElementById('result');
const balanceSpan = document.getElementById('balance');
const spinSound = document.getElementById('spin-sound');
const winSound = document.getElementById('win-sound');
const historyList = document.getElementById('history');

let balance = 1000;
let selectedChip = 10;
let deg = 0;
let history = [];

const numberColors = [
  'green', 'red', 'black', 'red', 'black', 'red', 'black', 'red', 'black', 'red',
  'black', 'black', 'red', 'black', 'red', 'black', 'red', 'black', 'red', 'red',
  'black', 'red', 'black', 'red', 'black', 'red', 'black', 'red', 'black', 'black',
  'red', 'black', 'red', 'black', 'red', 'black', 'red'
];

function generateWheel() {
  for (let i = 0; i <= 36; i++) {
    const angle = (360 / 37) * i;
    const number = document.createElement('div');
    number.className = `number ${numberColors[i]}`;
    number.style.transform = `rotate(${angle}deg)`;
    number.innerHTML = `<span style="--angle:-${angle}deg">${i}</span>`;
    numbersContainer.appendChild(number);
  }
}
generateWheel();

function selectChip(value) {
  selectedChip = value;
}

function spinWheel() {
  const betNumber = parseInt(document.getElementById('bet-number').value);
  const colorBet = document.querySelector('input[name="color"]:checked')?.value;
  const parityBet = document.querySelector('input[name="parity"]:checked')?.value;

  if (!betNumber && !colorBet && !parityBet) {
    alert('Selecciona al menos una apuesta');
    return;
  }

  if (selectedChip > balance) {
    alert('Saldo insuficiente');
    return;
  }

  const winningNumber = Math.floor(Math.random() * 37);
  const segmentDeg = 360 / 37;

 
  const spins = Math.floor(Math.random() * 5) + 8; // mínimo 8 vueltas, máximo 12
  const randomOffset = Math.random() * segmentDeg; 
  deg = (360 * spins) + ((36 - winningNumber) * segmentDeg) + randomOffset;

  wheel.style.transform = `rotate(${deg}deg)`;
  spinSound.play();
  resultText.textContent = "Girando...";

  setTimeout(() => {
    const winningColor = numberColors[winningNumber];
    const isEven = winningNumber !== 0 && winningNumber % 2 === 0;

    let totalWin = 0;

    if (betNumber === winningNumber) totalWin += selectedChip * 35;
    if (colorBet === winningColor) totalWin += selectedChip * 2;
    if (parityBet === "even" && isEven) totalWin += selectedChip * 2;
    if (parityBet === "odd" && !isEven && winningNumber !== 0) totalWin += selectedChip * 2;

    if (totalWin > 0) {
      resultText.textContent = `¡Salió ${winningNumber} (${winningColor})! Ganaste $${totalWin}`;
      balance += totalWin;
      winSound.play();
    } else {
      resultText.textContent = `¡Salió ${winningNumber} (${winningColor})! Perdiste $${selectedChip}`;
      balance -= selectedChip;
    }

    balanceSpan.textContent = balance;
    updateHistory(winningNumber, winningColor);
  }, 5000);
}


function updateHistory(num, color) {
  history.unshift({ num, color });
  if (history.length > 10) history.pop();

  historyList.innerHTML = history.map(item =>
    `<li style="color:${item.color}">${item.num} (${item.color})</li>`
  ).join('');
}
