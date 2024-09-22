<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X-Tic-Tac-Toe-O</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-md-8 game_board">
                <div class="row align-items-center justify-content-center mb-2 text-center bg-secondary">
                    <div class="col-md-12">
                        <h1>PLAYER - <span id="player_turn"></span></h1>
                    </div>
                </div>
                <input type="hidden" id="pointTableId" value="0" />
                <div class="row">
                    <button class="col-4 board_cell" id="b1" onclick="markCell('1')"></button>
                    <button class="col-4 board_cell" id="b2" onclick="markCell('2')"></button>
                    <button class="col-4 board_cell" id="b3" onclick="markCell('3')"></button>
                </div>
                <div class="row">
                    <button class="col-4 board_cell" id="b4" onclick="markCell('4')"></button>
                    <button class="col-4 board_cell" id="b5" onclick="markCell('5')"></button>
                    <button class="col-4 board_cell" id="b6" onclick="markCell('6')"></button>
                </div>
                <div class="row">
                    <button class="col-4 board_cell" id="b7" onclick="markCell('7')"></button>
                    <button class="col-4 board_cell" id="b8" onclick="markCell('8')"></button>
                    <button class="col-4 board_cell" id="b9" onclick="markCell('9')"></button>
                </div>
                <div class="row align-items-center justify-content-center mt-2">
                    <div class="col-4">
                        <button type="button" onclick="resetCell()" class="btn btn-danger w-100">New Game</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 sidecard">
                <div id="score_card">
                    
                </div>
                <div id="past_match">

                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="./script.js"></script>
</html>