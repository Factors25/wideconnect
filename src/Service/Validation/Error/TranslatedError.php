<?php

namespace App\Service\Validation\Error;

class TranslatedError
{
    private string $key;
    private array $params;

    public function __construct(string $key, ?array $params = [])
    {
        $this->key = $key;
        $this->params = $params;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getParams(): ?array
    {
        return $this->params;
    }
}