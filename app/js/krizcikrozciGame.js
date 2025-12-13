document.addEventListener('DOMContentLoaded', () => {
    const cells = document.querySelectorAll('#board td');
    const status = document.getElementById('status');
    const resetBtn = document.getElementById('resetBtn');

    let currentPlayer = 'X';
    let board = Array(9).fill(null);
    let gameActive = true;

    const winningCombinations = [
        [0,1,2],
        [3,4,5],
        [6,7,8],
        [0,3,6],
        [1,4,7],
        [2,5,8],
        [0,4,8],
        [2,4,6]
    ];

    function checkWin() {
        return winningCombinations.some(combo => {
            const [a, b, c] = combo;
            return board[a] && board[a] === board[b] && board[a] === board[c];
        });
    }

    function checkDraw() {
        return board.every(cell => cell !== null);
    }

    function updateStatus() {
        if (checkWin()) {
            status.textContent = `Igralec ${currentPlayer} je zmagal! 🎉`;
            gameActive = false;
        } else if (checkDraw()) {
            status.textContent = "Neodločeno! Poskusi ponovno.";
            gameActive = false;
        } else {
            status.textContent = `Na potezi je igralec ${currentPlayer}`;
        }
    }

    function cellClicked(e) {
        const index = parseInt(e.target.getAttribute('data-cell'));

        if (!gameActive || board[index]) return;

        board[index] = currentPlayer;
        e.target.textContent = currentPlayer;

        if(currentPlayer === 'X') {
            e.target.style.color = '#007bff';  // modra
        } else {
            e.target.style.color = '#dc3545';  // rdeča
        }

        if (checkWin() || checkDraw()) {
            updateStatus();
        } else {
            currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
            updateStatus();
        }
    }

    function resetGame() {
        board.fill(null);
        cells.forEach(cell => {
            cell.textContent = '';
            cell.style.color = 'black';
        });
        currentPlayer = 'X';
        gameActive = true;
        updateStatus();
    }

    cells.forEach(cell => cell.addEventListener('click', cellClicked));
    resetBtn.addEventListener('click', resetGame);

    updateStatus();
});
