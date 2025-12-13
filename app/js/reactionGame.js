let button = document.getElementById('reactionButton');
let info = document.getElementById('info');
let reactionTimeDisplay = document.getElementById('reactionTime');

let lastReactionTime = null;

let sendButton = document.getElementById('sendScoreButton');
if (!sendButton) {
    sendButton = document.createElement('button');
    sendButton.id = 'sendScoreButton';
    sendButton.textContent = 'Pošlji rezultat na lestvico';
    sendButton.style.marginTop = '15px';
    sendButton.style.padding = '10px 20px';
    sendButton.style.fontSize = '16px';
    sendButton.style.cursor = 'pointer';
    sendButton.disabled = true; // na začetku onemogočen

    // če uporabnik ni prijavljen, pokažemo opozorilo ob kliku
    sendButton.onclick = function () {
        if (!userLoggedIn) {
            alert('Za objavo rezultata se moraš prijaviti.');
            return;
        }
        if (lastReactionTime === null) {
            alert('Ni rezultata za pošiljanje.');
            return;
        }

        fetch('submit_score.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                rezultat: lastReactionTime,
                igra: 'reakcijaIgra'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Rezultat uspešno poslan!');
                sendButton.disabled = true;
            } else {
                alert('Napaka pri shranjevanju rezultata: ' + (data.error || 'Neznana napaka.'));
            }
        })
        .catch(() => {
            alert('Prišlo je do napake pri pošiljanju rezultata.');
        });
    };


    document.body.appendChild(sendButton);
}

function resetButton() {
    button.style.backgroundColor = '#777';
    button.textContent = 'Počakaj...';
    button.style.display = 'inline-block';
    button.disabled = true;
    info.textContent = 'Počakaj, da se gumb obarva...';
    reactionTimeDisplay.textContent = '-';
    lastReactionTime = null;

    sendButton.disabled = true; // onemogoči gumb za pošiljanje dokler ni rezultata
}

function showButtonToClick() {
    button.style.backgroundColor = '#28a745';
    button.textContent = 'Klikni me!';
    button.disabled = false;
    info.textContent = 'Klikni zdaj!';
    startTime = new Date().getTime();
}

function startGame() {
    resetButton();
    let delay = Math.floor(Math.random() * 3000) + 1000;
    setTimeout(showButtonToClick, delay);
}

button.onclick = function () {
    if (button.disabled) return;

    let endTime = new Date().getTime();
    let reactionTime = endTime - startTime;
    reactionTimeDisplay.textContent = reactionTime;
    info.textContent = "Čestitamo! Tvoj čas reakcije je " + reactionTime + " ms.";
    button.style.display = 'none';
    lastReactionTime = reactionTime;

    if (userLoggedIn) {
        sendButton.disabled = false;
    }

    showRestartButton();
};

function showRestartButton() {
    let restartButton = document.getElementById('restartButton');
    if (!restartButton) {
        restartButton = document.createElement('button');
        restartButton.id = 'restartButton';
        restartButton.textContent = 'Ponovna igra';
        restartButton.style.marginTop = '15px';
        restartButton.style.padding = '10px 20px';
        restartButton.style.fontSize = '16px';
        restartButton.style.cursor = 'pointer';

        restartButton.onclick = function () {
            button.style.display = 'inline-block';
            startGame();
        };

        document.body.appendChild(restartButton);
    } else {
        restartButton.style.display = 'inline-block';
    }
}

window.onload = startGame;
