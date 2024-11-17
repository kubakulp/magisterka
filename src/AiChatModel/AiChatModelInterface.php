<?php

namespace App\AiChatModel;

interface AiChatModelInterface
{
    public function getIdentifier(): string;

    public function sendMessage(string $prompt): string;

    public function getName(): string;

    public function getImageName(): string;

    public function getMessages(): array;
}
