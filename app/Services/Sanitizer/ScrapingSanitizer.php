<?php

namespace App\Services\Sanitizer;

class ScrapingSanitizer
{
    public static function sanitizeText(?string $text): string
    {
        if (!$text) {
            return '';
        }

        $text = strip_tags($text, '<p><strong><em><ul><li><br><a>');

        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    public static function sanitizeUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }
}
