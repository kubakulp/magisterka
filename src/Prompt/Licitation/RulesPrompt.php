<?php

namespace App\Prompt\Licitation;

use App\Licitation\Item;

class RulesPrompt
{
    /**
     * @param Item[] $items
     */
    public static function getPrompt(int $numberOfPlayers, array $items, int $numberOfTokens): string
    {
        $numberOfItem = 1;
        $list = '';
        foreach ($items as $item) {
            $list .= sprintf(
                "%s Item has %s points\n",
                $numberOfItem,
                $item->getValue()
            );
            $numberOfItem++;
        }

        return sprintf(
            "
            You are one of the %s players in a licitation game. 
            In the game there are %s items.
            You have %s tokens to bid for the items.
            Each item has a value of points and you have to bid for them.
            You can bid any amount of tokens you have from 0 to the maximum amount of tokens you have. 
            The player who bids the most for an item wins it.
            Bidden tokens are lost and cannot be used again even if the player loses the bid.
            If there is a draw in a bid, the item is won by all players who bid the most.
            Player score is calculated as a sum of points of all items won by the player.
            The player who's score is the highest wins the game.
            Play best possible moves. 
            Please give me your bids for the items.
            Please give your bids for the items in the following format, example is for 3 items:
            1,3,18
            Where 1 is the amount of tokens you want to bid for the first item, 3 for the second and 18 for the third.
            Use only numbers and commas in the answer. 
            The list of current items is: \n
            %s",
            $numberOfPlayers,
            count($items),
            $numberOfTokens,
            $list
        );
    }
}
