<?php

namespace App\AiChatModel;

use Exception;
use Orhanerday\OpenAi\OpenAi;

class ChatGPT4oMini implements AiChatModelInterface
{
    private OpenAi $chat;
    private array $messages = [];
    private string $identifier = "chatgpt4o-mini";

    public function __construct(
        private readonly string $openAiApiKey
    )
    {
        $this->chat = new OpenAi($this->openAiApiKey);
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

        $answer = json_decode($this->chat->chat([
            'model' => 'gpt-4o-mini',
            'messages' => $this->messages,
            'temperature' => 1.0,
            'max_tokens' => 60,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ]));

        $this->messages[] = [
            'role' => 'assistant',
            "content" => $answer->choices[0]->message->content
        ];

        return $answer->choices[0]->message->content;
    }

    public function getName(): string
    {
        return "ChatGPT 4o Mini";
    }

    public function getImageName(): string
    {
        return "chatgpt.jpg";
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
