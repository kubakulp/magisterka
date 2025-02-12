<?php

namespace App\AiChatModel;

use Exception;
use LucianoTonet\GroqPHP\Groq;

class Llama3With8B implements AiChatModelInterface
{
    private Groq $chat;
    private array $messages = [];
    private string $identifier = "Llama 3 8B";

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
            'model' => 'llama3-8b-8192',
            'messages' => $this->messages,
            'max_tokens' => 1,
        ]);

        $this->messages[] = [
            'role' => 'assistant',
            "content" => $answer['choices'][0]['message']['content']
        ];

        return $answer['choices'][0]['message']['content'];
    }

    public function getName(): string
    {
        return "Llama 3 8B";
    }

    public function getImageName(): string
    {
        return "llama.jpg";
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
