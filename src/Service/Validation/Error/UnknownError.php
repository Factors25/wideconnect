<?php

namespace App\Service\Validation\Error;

class UnknownError
{
    public const DEFAULT_MESSAGE = 'Une erreur est survenue, veuillez contacter l\'administrateur';

    private string $message;

    public function __construct(string $message = self::DEFAULT_MESSAGE)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}