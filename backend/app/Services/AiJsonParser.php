<?php

namespace App\Services;

class AiJsonParser
{
    public static function parse(string $content): array
    {
        $decoded = json_decode($content, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        $repaired = self::repair($content);
        $decoded = json_decode($repaired, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('AI response is not valid JSON.');
        }

        return $decoded;
    }

    private static function repair(string $content): string
    {
        $start = strpos($content, '{');
        $end = strrpos($content, '}');

        if ($start === false || $end === false) {
            return $content;
        }

        return substr($content, $start, $end - $start + 1);
    }
}
