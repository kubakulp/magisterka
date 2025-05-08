<?php

namespace App\AiChatModel;

use Exception;
use LucianoTonet\GroqPHP\Groq;

class Mixtral8x7B implements AiChatModelInterface
{
    private Groq $chat;
    private array $messages = [];
    private string $identifier = "Mixtral 8x7B";

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
            'model' => 'mixtral-8x7b-32768',
            'messages' => $this->messages,
            'max_tokens' => 20,
        ]);

        $this->messages[] = [
            'role' => 'assistant',
            "content" => $answer['choices'][0]['message']['content']
        ];

        return $answer['choices'][0]['message']['content'];
    }

    public function getName(): string
    {
        return "Mixtral 8x7B";
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
