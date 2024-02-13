<?php

namespace App\Service\Validation\Error;

class Error
{
    public static function generate($errors = null, array $extraData = []): array
    {
        if (is_null($errors)) {
            return ['success' => false, 'errors' => [], ...$extraData];
        }

        if (is_array($errors)) {
            return ['success' => false, 'errors' => $errors, ...$extraData];
        }

        return ['success' => false, 'errors' => [$errors], ...$extraData];
    }
}