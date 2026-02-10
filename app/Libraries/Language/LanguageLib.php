<?php

declare(strict_types=1);

namespace App\Libraries\Language;

final class LanguageLib
{
    /**
     * Default list of dictionary keys to expose to JS (window.l).
     * Merge of admin + dictionary-related keys.
     *
     * @return array<int, string>
     */
    public static function getLangKeys(): array
    {
        return array_values(array_unique(array_merge(
            LanguageKeys::admin(),
            [
                'a-dictionary',
                'a-add-dictionary',
                'a-dictionary-parameter',
                'a-save-succesfully',
                'a-edit-succesfully',
                'a-simple-edit',
                'a-user-profile',
            ]
        )));
    }

    /**
     * Keys per page (optional). Use when you want different key sets per route.
     *
     * @return array<int, string>
     */
    public static function getLangKeysForPage(string $page): array
    {
        $base = self::getLangKeys();

        return match ($page) {
            'settings.dictionaries' => array_values(array_unique(array_merge($base, [
                'a-dictionary',
                'a-add-dictionary',
                'a-dictionary-parameter',
            ]))),
            default => $base,
        };
    }
}
