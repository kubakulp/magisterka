<?php

namespace App\Licitation;

class Item
{
    public function __construct(
        private int $value
    ){
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
