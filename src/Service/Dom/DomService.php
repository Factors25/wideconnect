<?php

namespace App\Service\Dom;

class DomService
{
    public static function element(string $tag, array $attributes = [], mixed $content = ''): string
    {
        if ($tag === 'br') {
            return "<$tag />";
        }

        $html = "<$tag";

        foreach ($attributes as $name => $value) {
            $html .= ' ' . $name . '="' . htmlspecialchars($value) . '"';
        }

        $html .= ">";

        if (!empty($content)) {
            if (is_array($content)) {
                $html .= implode('', array_values($content));
            } else {
                $html .= $content;
            }
        }

        $html .= "</$tag>";

        return $html;
    }

    public static function icon($class, mixed $content = ''): string
    {
        return self::element(tag: 'i', attributes: ['class' => "fa-solid $class"]) . $content;
    }
}