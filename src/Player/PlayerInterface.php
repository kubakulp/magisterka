<?php

namespace App\Player;

use App\AiChatModel\AiChatModelInterface;

interface PlayerInterface
{
    public function getModel(): AiChatModelInterface;
}
