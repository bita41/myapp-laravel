<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class LangJsController extends Controller
{
    /**
     * Lang JS endpoint: window.l + all_languages (keys from query, values from dictionary cache).
     * Used by layout: route('admin.lang.js', ['keys' => $langKeys])
     */
    public function __invoke(): Response
    {
        $keys = request()->input('keys', []);
        if (!is_array($keys)) {
            $keys = [];
        }

        $langFile = app_language_file();

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
                $map[$param] = html_entity_decode((string) ($row->{$langFile} ?? ''));
            }

            return $map;
        });

        $pairs = [];
        foreach ($keys as $key) {
            $k = (string) $key;
            if ($k === '') {
                continue;
            }
            $pairs[$k] = $dict[$k] ?? $k;
        }

        $allLanguages = [];
        foreach (get_languages() as $lng) {
            $allLanguages[] = ['code' => (string) ($lng['code'] ?? '')];
        }

        $js = 'window.l = window.l || {};' . "\n";
        foreach ($pairs as $k => $v) {
            $js .= 'l[' . json_encode($k) . ']=' . json_encode($v, JSON_UNESCAPED_UNICODE) . ";\n";
        }
        $js .= 'var all_languages=' . json_encode($allLanguages, JSON_UNESCAPED_UNICODE) . ";\n";

        return response($js, 200)->header('Content-Type', 'application/javascript; charset=UTF-8');
    }
}

