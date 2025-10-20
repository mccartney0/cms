<?php

namespace App\Core;

class Str
{
    public static function slug(string $value): string
    {
        $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        $value = preg_replace('/[^A-Za-z0-9-]+/', '-', $value);
        $value = strtolower(trim($value, '-'));

        return $value ?: 'item-' . time();
    }
}
