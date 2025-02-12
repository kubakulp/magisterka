<?php

namespace App\Prompt\TicTacToe;

use App\TicTacToe\Board;

class FirstPlayerMovePrompt
{
    public static function getPrompt(Board $board): string
    {
        return "You are going to play in a tic-tac-toe game, you are the first player (O) and the board status looks like this:\n\n" . $board->formatBoard() . "\n\nMake your move by selecting a position (0-8). Please answer only using a number from 0 to 8.";
    }

    public static function getBestMovePrompt(Board $board): string
    {
        return "You are going to play in a tic-tac-toe game and your goal is to have best possible score, so play best possible move, you are the first player (O) and the board status looks like this:\n\n" .
            $board->formatBoard() . "\n\nMake your move by selecting a position (0-8). Please answer only using a number from 0 to 8.";
    }
}
