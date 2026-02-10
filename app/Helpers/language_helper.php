<?php

declare(strict_types=1);

use App\Models\Language;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (!function_exists('get_languages')) {
    /**
     * Return languages from DB, filtered to those that exist as columns in dictionaries table.
     *
     * @return array<int, array{language_id:int, name:string, code:string, file:string}>
     */
    function get_languages(): array
    {
        return Cache::remember('languages:active', 3600, function (): array {
            $langs = Language::query()
                ->select(['language_id', 'name', 'code', 'file'])
                ->orderBy('language_id')
                ->get()
                ->toArray();

            $filtered = [];
            foreach ($langs as $lang) {
                $file = (string) ($lang['file'] ?? '');
                if ($file !== '' && Schema::hasColumn('dictionaries', $file)) {
                    $filtered[] = $lang;
                }
            }

            return $filtered;
        });
    }
}

if (!function_exists('app_language_file')) {
    /**
     * Current language file (e.g. "romanian"/"english").
     * Session -> fallback romanian.
     */
    function app_language_file(): string
    {
        $lang = session('lang_file');
        if (is_string($lang) && $lang !== '') {
            return $lang;
        }

        return 'romanian';
    }
}

if (!function_exists('l')) {
    /**
     * Dictionary lookup by parameter key, from DB dictionaries table.
     * Uses cache per language file. CI4-style: html_entity_decode on value, fallback to key.
     */
    function l(string $key, ?string $langFile = null): string
    {
        $langFile = $langFile ?: app_language_file();

        $dict = Cache::remember("dict:{$langFile}", 3600, function () use ($langFile): array {
            if (!Schema::hasColumn('dictionaries', $langFile)) {
                return [];
            }
            $rows = DB::table('dictionaries')
                ->select(['parameter', $langFile])
                ->whereNotNull('parameter')
                ->get();

            $map = [];
            foreach ($rows as $row) {
                $param = (string) ($row->parameter ?? '');
                if ($param === '') {
                    continue;
                }
                $map[$param] = (string) ($row->{$langFile} ?? '');
            }

            return $map;
        });

        $value = $dict[$key] ?? '';

        return $value !== '' ? html_entity_decode($value) : $key;
    }
}

if (!function_exists('invalidate_dictionary_cache')) {
    /**
     * Call after editing dictionaries (add/edit/delete) so l() and get_languages() reflect changes.
     */
    function invalidate_dictionary_cache(): void
    {
        Cache::forget('languages:active');
        $langs = Language::query()->pluck('file')->filter()->unique();
        foreach ($langs as $file) {
            Cache::forget("dict:{$file}");
        }
    }
}
