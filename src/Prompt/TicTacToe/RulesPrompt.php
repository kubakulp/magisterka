<?php

namespace App\Prompt\TicTacToe;

class RulesPrompt
{
    public static function getPrompt(): string
    {
        return "You are going to play in a tic-tac-toe game.\n
        The board size will be 3x3 and you are going to make your move by selecting a position (0-8).\n 
        The board coordinates looks like this:\n
        0 1 2\n 
        3 4 5\n 
        6 7 8\n
        Please answer OK if you understand.";
    }
}
