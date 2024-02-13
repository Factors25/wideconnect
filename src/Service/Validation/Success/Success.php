<?php

namespace App\Service\Validation\Success;

class Success
{
    public static function generate(array $extraData = []): array
    {
        return ['success' => true, 'errors' => [], ...$extraData];
    }
}