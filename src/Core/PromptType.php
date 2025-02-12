<?php

namespace App\Core;

enum PromptType: string
{
    case Normal = 'normal';
    case Best_move_only = 'best move only';
}
