document.addEventListener('DOMContentLoaded', function() {
    function updateBoard(board) {
        for (let i = 0; i < board.length; i++) {
            const cell = document.getElementById('b' + (i + 1));
            cell.textContent = board[i] || '';
        }
    }
});

document.getElementById('all-game-button').addEventListener('click', function () {
    fetch('/tic-tac-toe/all-game', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            const boardArray = data.board;
            const cells = ['b1', 'b2', 'b3', 'b4', 'b5', 'b6', 'b7', 'b8', 'b9'];
            const flatBoard = boardArray.flat();

            flatBoard.forEach((cellValue, index) => {
                const cellElement = document.getElementById(cells[index]);
                cellElement.textContent = cellValue === "" ? " " : cellValue;
            });
        });
});

document.getElementById('next-move-button').addEventListener('click', function () {
    fetch('/tic-tac-toe/next-move', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            const boardArray = data.board;
            const cells = ['b1', 'b2', 'b3', 'b4', 'b5', 'b6', 'b7', 'b8', 'b9'];
            const flatBoard = boardArray.flat();

            flatBoard.forEach((cellValue, index) => {
                const cellElement = document.getElementById(cells[index]);
                cellElement.textContent = cellValue === "" ? " " : cellValue;
            });

            let messages = data.models_info[0]['messages'];
            let messages2 = data.models_info[1]['messages'];
            let structuredArray = messages.map((msg, index) => {
                return { [index]: msg.content };
            });
            let structuredArray2 = messages2.map((msg, index) => {
                return { [index]: msg.content };
            });
            let htmlContent = '';
            structuredArray.forEach(finalMessage => {
                const values = Object.values(finalMessage);
                htmlContent += `<br><br>${values}`;
            });
            let htmlContent2 = '';
            structuredArray2.forEach(finalMessage => {
                const values = Object.values(finalMessage);
                htmlContent2 += `<br><br>${values}`;
            });

            document.getElementById('mess1').innerHTML = htmlContent;
            document.getElementById('mess2').innerHTML = htmlContent2;
            if(checkIfGameIsOver(flatBoard)) {
                document.getElementById('next-move-button').disabled = true;
            }

            function checkOneLine(a, b, c, board)
            {
                return board[a] === board[b] && board[b] === board[c] && board[c] !== '';
            }

            function checkIfGameIsOver(board)
            {
                let someOneWin = checkOneLine(0, 1, 2, board) || checkOneLine(3, 4, 5, board) || checkOneLine(6, 7, 8, board) ||
                    checkOneLine(0, 3, 6, board) || checkOneLine(1, 4, 7, board) || checkOneLine(2, 5, 8, board) ||
                    checkOneLine(0, 4, 8, board) || checkOneLine(2, 4, 6, board);

                if(someOneWin) {
                    return true;
                } else {
                    for (let i = 0; i < board.length; i++) {
                        if(board[i] === '') {
                            return false;
                        }
                    }
                    return true;
                }
            }
        });
});
