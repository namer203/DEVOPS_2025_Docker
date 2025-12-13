(() => {
  const startBtn = document.getElementById('startTimerBtn');
  const stopBtn = document.getElementById('stopTimerBtn');
  const resultEl = document.getElementById('timerResult');
  const messageEl = document.getElementById('timerMessage');
  const saveScoreBtn = document.getElementById('saveScoreBtn');

  let startTime = 0;
  let timerRunning = false;
  let lastScore = null;

  saveScoreBtn.disabled = true;

  startBtn.addEventListener('click', () => {
    startTime = performance.now();
    timerRunning = true;
    resultEl.textContent = '-';
    messageEl.textContent = 'Poskusi klikniti gumb točno po 5 sekundah.';
    startBtn.disabled = true;
    stopBtn.disabled = false;
    lastScore = null;

    saveScoreBtn.disabled = true;
  });

  stopBtn.addEventListener('click', () => {
    if (!timerRunning) return;
    const endTime = performance.now();
    const diff = endTime - startTime;
    timerRunning = false;
    resultEl.textContent = diff.toFixed(0);
    startBtn.disabled = false;
    stopBtn.disabled = true;

    const diffFrom5s = Math.abs(diff - 5000);
    lastScore = diffFrom5s; // manjša vrednost je boljši rezultat

    if (diffFrom5s <= 500) {
      messageEl.textContent = `Super! Bil si blizu 5 sekund (${diff.toFixed(0)} ms).`;
    } else {
      messageEl.textContent = `Poskusi znova! Tvoj čas se razlikuje za ${diffFrom5s.toFixed(0)} ms od 5 sekund.`;
    }

    if (userLoggedIn && lastScore !== null) {
      saveScoreBtn.disabled = false;
    }
  });

  saveScoreBtn.addEventListener('click', () => {
    if (!userLoggedIn) {
      alert('Za shranjevanje rezultata se moraš prijaviti.');
      return;
    }
    if (lastScore === null) {
      alert('Ni rezultata za shranjevanje.');
      return;
    }

    saveScoreBtn.disabled = true;

    fetch('submit_score.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        igra: 'timergame',
        rezultat: Math.round(lastScore)
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Rezultat uspešno shranjen!');
      } else {
        alert('Napaka pri shranjevanju rezultata.');
        saveScoreBtn.disabled = false;
      }
    })
    .catch(() => {
      alert('Napaka pri povezavi z serverjem.');
      saveScoreBtn.disabled = false;
    });
  });
})();
