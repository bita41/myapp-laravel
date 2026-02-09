<?php

declare(strict_types=1);

namespace App\Support;

final class Asset
{
    public static function v(string $path): string
    {
        $fullPath = public_path($path);
        $v = is_file($fullPath) ? (string) filemtime($fullPath) : (string) time();

        return asset($path) . '?v=' . $v;
    }
}
