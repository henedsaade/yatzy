document.addEventListener('DOMContentLoaded', function() {
    const board = document.getElementById('board');
    let currentPlayer = 'X';

    startGame();

    function startGame() {
        board.innerHTML = '';
        for (let i = 0; i < 9; i++) {
            const cell = document.createElement('div');
            cell.classList.add('cell');
            cell.dataset.cell = '';
            cell.addEventListener('click', handleClick);
            board.appendChild(cell);
        }
    }

    function handleClick(e) {
        const cell = e.target;
        if (!cell.classList.contains('x') && !cell.classList.contains('circle')) {
            if (currentPlayer === 'X') {
                cell.classList.add('x');
            } else {
                cell.classList.add('circle');
            }
            cell.removeEventListener('click', handleClick); // Remove click listener after placing the mark
            if (checkWin(currentPlayer)) {
                setTimeout(() => {
                    alert(`${currentPlayer} wins!`);
                    startGame();
                }, 100);
            } else if (checkDraw()) {
                setTimeout(() => {
                    alert("It's a draw!");
                    startGame();
                }, 100);
            } else {
                currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
            }
        }
    }

    function checkWin(player) {
        const cells = document.querySelectorAll('.cell');
        const winningCombinations = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], // Rows
            [0, 3, 6], [1, 4, 7], [2, 5, 8], // Columns
            [0, 4, 8], [2, 4, 6]             // Diagonals
        ];
        return winningCombinations.some(combination => {
            const [a, b, c] = combination;
            return cells[a].classList.contains(player.toLowerCase()) &&
                   cells[b].classList.contains(player.toLowerCase()) &&
                   cells[c].classList.contains(player.toLowerCase());
        });
    }

    function checkDraw() {
        const cells = document.querySelectorAll('.cell');
        return Array.from(cells).every(cell => cell.classList.contains('x') || cell.classList.contains('circle'));
    }
});
