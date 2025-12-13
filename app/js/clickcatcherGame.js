(() => {
  const target = document.getElementById('target');
  const gameArea = document.getElementById('catcher-game');
  const startBtn = document.getElementById('startCatcherBtn');
  const saveBtn = document.getElementById('saveScoreBtn');
  const scoreEl = document.getElementById('catcherScore');
  const messageEl = document.getElementById('catcherMessage');
  const loginMsgEl = document.getElementById('catcherLoginMessage');

  let score = 0;
  let gameActive = false;
  let timer;

  // Gumb za shranjevanje je na začetku onemogočen
  saveBtn.disabled = true;

  function randomPosition() {
    const maxX = gameArea.clientWidth - target.clientWidth;
    const maxY = gameArea.clientHeight - target.clientHeight;
    const x = Math.floor(Math.random() * maxX);
    const y = Math.floor(Math.random() * maxY);
    return { x, y };
  }

  function showTarget() {
    const pos = randomPosition();
    target.style.left = pos.x + 'px';
    target.style.top = pos.y + 'px';
    target.style.display = 'block';

    timer = setTimeout(() => {
      target.style.display = 'none';
      if (gameActive) showTarget();
    }, 2000);
  }

  target.addEventListener('click', () => {
    if (!gameActive) return;
    clearTimeout(timer);
    score++;
    scoreEl.textContent = score;
    target.style.display = 'none';
    showTarget();
  });

  startBtn.addEventListener('click', () => {
    score = 0;
    scoreEl.textContent = score;
    messageEl.textContent = '';
    loginMsgEl.textContent = '';
    gameActive = true;
    startBtn.disabled = true;
    saveBtn.disabled = true; // onemogoči med igro

    showTarget();

    setTimeout(() => {
      gameActive = false;
      target.style.display = 'none';
      messageEl.textContent = `Konec igre! Dosegel si ${score} točk.`;
      startBtn.disabled = false;

      // Omogoči gumb za shranjevanje le, če je rezultat > 0
      saveBtn.disabled = score === 0;
    }, 15000);
  });

  saveBtn.addEventListener('click', () => {
    if (!userLoggedIn) {
      loginMsgEl.textContent = 'Za shranjevanje rezultata se moraš prijaviti.';
      return;
    }

    loginMsgEl.textContent = '';

    // Onemogoči gumb med pošiljanjem, da preprečimo več klikov
    saveBtn.disabled = true;

    fetch('submit_score.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ igra: 'ujemi', rezultat: score })
    }).then(res => res.json())
      .then(data => {
        if(data.success) {
          messageEl.textContent = 'Rezultat je bil shranjen na lestvico!';
        } else {
          messageEl.textContent = 'Napaka pri shranjevanju rezultata.';
          saveBtn.disabled = false;
        }
      }).catch(() => {
        messageEl.textContent = 'Napaka pri povezavi s strežnikom.';
        saveBtn.disabled = false;
      });
  });

})();
