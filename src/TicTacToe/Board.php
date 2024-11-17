<?php

namespace App\TicTacToe;

class Board
{
    public array $board = ['', '', '', '', '', '', '', '', ''];

    public function formatBoard(): string
    {
        $formattedBoard = '';

        for ($i = 0; $i < 3; $i++) {
            $formattedBoard .= $this->formatCell($this->board[$i * 3]) . ' | ' .
                $this->formatCell($this->board[$i * 3 + 1]) . ' | ' .
                $this->formatCell($this->board[$i * 3 + 2]) . "\n";
            if ($i < 2) {
                $formattedBoard .= "---------\n";
            }
        }

        return $formattedBoard;
    }

    public function setCellValue(int $cell, string $value): void
    {
        $this->board[$cell] = $value;
    }

    private function formatCell($cell): string
    {
        return $cell !== null ? $cell : ' ';
    }
}
