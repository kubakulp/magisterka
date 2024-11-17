<?php

namespace App\Service;

use App\AiChatModel\AiChatModelInterface;

class AiChatModelLocator
{
    private iterable $models;

    public function __construct(iterable $models)
    {
        $this->models = $models;
    }

    /**
     * @return AiChatModelInterface[]
     */
    public function getModels(): array
    {
        $modelsArray = [];
        foreach ($this->models as $model) {
            $modelsArray[] = $model;
        }
        return $modelsArray;
    }
}
