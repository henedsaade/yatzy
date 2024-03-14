<?php
session_start();

// Function to reset the game state
function resetGame() {
    $_SESSION['board'] = ['', '', '', '', '', '', '', '', ''];
    $_SESSION['currentPlayer'] = 'X';
}

// Function to place a mark on the board
function placeMark($index) {
    if ($_SESSION['board'][$index] == '') {
        $_SESSION['board'][$index] = $_SESSION['currentPlayer'];
        // Toggle current player
        $_SESSION['currentPlayer'] = ($_SESSION['currentPlayer'] == 'X') ? 'O' : 'X';
    }
}

// Function to check for a win
function checkWin() {
    $board = $_SESSION['board'];
    $winningCombinations = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // Rows
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // Columns
        [0, 4, 8], [2, 4, 6]             // Diagonals
    ];
    foreach ($winningCombinations as $combination) {
        [$a, $b, $c] = $combination;
        if ($board[$a] && $board[$a] === $board[$b] && $board[$a] === $board[$c]) {
            return true;
        }
    }
    return false;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle place mark request
    if (isset($_POST['action']) && $_POST['action'] === 'placeMark' && isset($_POST['index'])) {
        placeMark($_POST['index']);
        if (checkWin()) {
            echo json_encode(['status' => 'win', 'winner' => $_SESSION['currentPlayer']]);
        } elseif (array_filter($_SESSION['board'], function($cell) { return $cell == ''; }) == []) {
            echo json_encode(['status' => 'draw']);
        } else {
            echo json_encode(['status' => 'success']);
        }
        exit;
    }
    // Handle reset game request
    elseif (isset($_POST['action']) && $_POST['action'] === 'resetGame') {
        resetGame();
        echo json_encode(['status' => 'success']);
        exit;
    }
}

// Initialize the game
if (!isset($_SESSION['board'])) {
    resetGame();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic Tac Toe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Tic Tac Toe Game</h1>
    <div class="board" id="board">
        <?php for ($i = 0; $i < 9; $i++): ?>
            <div class="cell" data-cell></div>
        <?php endfor; ?>
    </div>
    <div class="winning-message" id="winningMessage">
        <div id="winningMessageText"></div>
        <button id="restartButton">Restart</button>
    </div>
    <script src="script.js" defer></script>
</body>
</html>
