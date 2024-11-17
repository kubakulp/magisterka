<?php

namespace App\AiChatModel;

use Exception;
use LucianoTonet\GroqPHP\Groq;

class Gemma2With9B implements AiChatModelInterface
{
    private Groq $chat;
    private array $messages = [];
    private string $identifier = "Gemma 2 9B";

    public function __construct(
        private string $groqApiKey,
    )
    {
        $this->chat = new Groq($this->groqApiKey);
    }

    /**
     * @throws Exception
     */
    public function sendMessage(string $prompt): string
    {
        $message = [
            'role' => 'user',
            "content" => $prompt
        ];

        $this->messages[] = $message;

        $answer = $this->chat->chat()->completions()->create([
            'model' => 'gemma2-9b-it',
            'messages' => $this->messages,
        ]);

        $this->messages[] = [
            'role' => 'assistant',
            "content" => $answer['choices'][0]['message']['content']
        ];

        return $answer['choices'][0]['message']['content'];
    }

    public function getName(): string
    {
        return "Gemma 2 9B";
    }

    public function getImageName(): string
    {
        return "gemma.webp";
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
