<?php

namespace App\Service\Sanitizer;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class SanitizerService
{
    public const ALLOWED_SANITIZERS = ['default', 'clean'];

    /**
     * @param string      $html
     * @param string|null $sanitizer
     * @return string
     */
    public static function _sanitize(string $html, ?string $sanitizer = 'default'): string
    {
        if (false === !in_array($sanitizer, self::ALLOWED_SANITIZERS, true)) {
            return $html;
        }

        // Decode string to get possible HTML structure
        $decoded = html_entity_decode($html);

        // Sanitize string to ensure that we remove all unwanted data
        $sanitized = (new HtmlSanitizer(self::_getConfig($sanitizer)))->sanitize($decoded);

        // Decode again to have special chars visible
        return html_entity_decode($sanitized);
    }

    /**
     * @param string      $html
     * @param string      $element
     * @param string|null $sanitizer
     * @return string
     */
    public static function _sanitizeFor(string $html, string $element = 'textarea', ?string $sanitizer = 'default'): string
    {
        if (false === !in_array($sanitizer, self::ALLOWED_SANITIZERS, true)) {
            return $html;
        }

        // Decode string to get possible HTML structure
        $decoded = html_entity_decode($html);

        // Sanitize string to ensure that we remove all unwanted data
        $sanitized = (new HtmlSanitizer(self::_getConfig($sanitizer)))->sanitizeFor($element, $decoded);

        // Decode again to have special chars visible
        return html_entity_decode($sanitized);
    }

    private static function _getConfig($sanitizer): HtmlSanitizerConfig
    {
        if (str_contains($sanitizer, 'default')) {
            return (new HtmlSanitizerConfig())
                ->allowSafeElements()
                ->allowStaticElements();
        }

        return (new HtmlSanitizerConfig());
    }
}