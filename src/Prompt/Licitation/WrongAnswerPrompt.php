<?php

namespace App\Prompt\Licitation;

class WrongAnswerPrompt
{
    public static function getPrompt(): string
    {
        return sprintf(
            "
            Your last answer was incorrect.
            Please give me your bids for the items.
            Please give your bids for the items in the following format, example is for 3 items:
            1,3,18
            Where 1 is the amount of tokens you want to bid for the first item, 3 for the second and 18 for the third.
            Use only numbers and commas in the answer."
        );
    }
}
