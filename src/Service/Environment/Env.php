<?php

namespace App\Service\Environment;

class Env
{
    public function __construct()
    {
    }

    public static function isDev(): bool
    {
        return $_ENV['APP_ENV'] === 'dev';
    }
}