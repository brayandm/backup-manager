<?php

namespace App\Helpers;

class Formatting
{
    public static function formatBytes($bytes)
    {
        if ($bytes === 0) {
            return '0 Bytes';
        }

        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $i = floor(log($bytes) / log($k));

        return round($bytes / pow($k, $i), 2).' '.$sizes[$i];
    }

    public static function formatText($text)
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9 ]/', '', $text);
        $text = str_replace(' ', '_', $text);

        return $text;
    }
}
