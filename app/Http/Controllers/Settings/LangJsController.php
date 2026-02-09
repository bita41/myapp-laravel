<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Support\LanguageKeys;
use Illuminate\Http\Response;

final class LangJsController extends Controller
{
    public function admin(): Response
    {
        // aici tu poti face mapping la dictionarul tau (DB) sau lang files
        // deocamdata returnam o structura minima, stabila, ca in CI4
        $keys = LanguageKeys::admin();

        $payload = [];
        foreach ($keys as $key) {
            // TODO: inlocuiesti cu dictionarul real: dict($key) / __($key) / etc
            $payload[$key] = $key;
        }

        $js = 'window.LANG = window.LANG || {}; window.LANG.admin = ' . json_encode($payload, JSON_UNESCAPED_UNICODE) . ';';

        return response($js, 200)->header('Content-Type', 'application/javascript; charset=UTF-8');
    }
}

