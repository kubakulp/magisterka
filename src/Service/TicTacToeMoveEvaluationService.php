<?php

namespace App\Service;

use InvalidArgumentException;

class TicTacToeMoveEvaluationService
{
    private static array $winningCombinations = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8],
        [0, 3, 6], [1, 4, 7], [2, 5, 8],
        [0, 4, 8], [2, 4, 6]
    ];

    // -1 - incorrect move
    // 0 - not giving change to force win by the opponent
    // 1 - giving change to force win by the opponent
    /**
     * @param int[] $moves
     * @return int
     */
    public static function calculateEvaluationMove(array $moves, bool $wasCorrectMove): int
    {
        if(!$wasCorrectMove){
            return -1;
        }

        $board = array_fill(0, 9, null);
        $player = 1;

        foreach ($moves as $move) {
            $board[$move] = $player;
            $player = 3 - $player;
        }

        $currentPlayer = count($moves) % 2 == 0 ? 1 : 2;
        if (self::canForceWin($board, $currentPlayer)) {
            return 1;
        }

        return 0;
    }

    private static function canForceWin(array &$board, int $player): bool
    {
        if (self::isWinningMove($board, 3 - $player)) {
            return false;
        }

        $availableMoves = self::getAvailableMoves($board);
        if (empty($availableMoves)) {
            return false;
        }

        foreach ($availableMoves as $move) {
            $board[$move] = $player;

            if (!self::canForceWin($board, 3 - $player)) {
                $board[$move] = null;
                return true;
            }

            $board[$move] = null;
        }

        return false;
    }

    private static function isWinningMove(array $board, int $player): bool
    {
        foreach (self::$winningCombinations as $combination) {
            if (
                $board[$combination[0]] === $player &&
                $board[$combination[1]] === $player &&
                $board[$combination[2]] === $player
            ) {
                return true;
            }
        }
        return false;
    }

    private static function getAvailableMoves(array $board): array
    {
        $moves = [];
        foreach ($board as $index => $value) {
            if ($value === null) {
                $moves[] = $index;
            }
        }
        return $moves;
    }
}
